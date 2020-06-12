<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 10/06/20 18.10
 * @File name           : Loans.php
 */

namespace Messenger;

class Loans
{

    use Commons;

    protected $auth;

    static function init()
    {
        return new static();
    }

    public function __construct()
    {
        $this->auth = Auth::verify();
    }

    function loans()
    {
        $limit = $this->getLimit();
        $offset = $this->getOffset();

        $query = \ORM::forTable('loan')
            ->join('item', ['loan.item_code', '=', 'item.item_code']);
        $count = $query->count('loan.loan_id');
        $biblios = $query
            ->select('item.item_id', 'id')
            ->select('item.item_code', 'code')
            ->select('mct.coll_type_name', 'type')
            ->join('mst_coll_type', ['item.coll_type_id', '=', 'mct.coll_type_id'], 'mct')
            ->limit($limit)->offset($offset)
            ->findArray();

        return json_encode([
            'slims' => [
                'total' => $count,
                'per_page' => $limit,
                'current_page' => $this->getPage(),
                'data' => $biblios,
                'token' => $this->auth->getToken()
            ]
        ]);
    }

    function overdue()
    {
        $limit = $this->getLimit();
        $offset = $this->getOffset();

        $query = \ORM::forTable('loan')
            ->join('item', ['loan.item_code', '=', 'item.item_code'])
            ->where('loan.is_lent', 1)
            ->where('loan.is_return', 0)
            ->whereRaw('(loan.due_date < now())');
        $count = $query->count('loan.loan_id');
        $loans = $query
            ->select('loan.loan_id')
            ->select('loan.loan_date')
            ->select('loan.due_date')
            ->select('loan.member_id')
            ->select('item.item_code')
            ->select('mct.coll_type_name', 'collection_type')
            ->select('b.biblio_id', 'biblio_id')
            ->select('b.title', 'title')
            ->selectExpr('DATEDIFF(now(), loan.due_date)', 'overdue')
            ->selectExpr('(CASE WHEN (loan.loan_rules_id > 0) 
            THEN 
                DATEDIFF(now(), loan.due_date) * mlr.fine_each_day
            ELSE
                DATEDIFF(now(), loan.due_date) * mmt.fine_each_day
            END)', 'fines')
            ->join('biblio', ['item.biblio_id', '=', 'b.biblio_id'], 'b')
            ->join('mst_coll_type', ['item.coll_type_id', '=', 'mct.coll_type_id'], 'mct')
            ->join('member', ['loan.member_id', '=', 'm.member_id'], 'm')
            ->join('mst_member_type', ['m.member_type_id', '=', 'mmt.member_type_id'], 'mmt')
            ->leftOuterJoin('mst_loan_rules', ['loan.loan_rules_id', '=', 'mlr.loan_rules_id'], 'mlr')
            ->limit($limit)->offset($offset)->findMany();

        $rows = [];
        foreach ($loans as $loan) {
            $tmp = $loan->asArray();
            $tmp['authors'] = $this->getAuthors($tmp['biblio_id']);
            unset($tmp['biblio_id']);
            $rows[] = $tmp;
        }

        return json_encode([
            'slims' => [
                'total' => $count,
                'per_page' => $limit,
                'current_page' => $this->getPage(),
                'data' => $rows,
                'token' => $this->auth->getToken()
            ]
        ]);
    }

    function duedate($days = 3)
    {
        $limit = $this->getLimit();
        $offset = $this->getOffset();

        $query = \ORM::forTable('loan')
            ->join('item', ['loan.item_code', '=', 'item.item_code'])
            ->where('loan.is_lent', 1)
            ->where('loan.is_return', 0)
            ->whereRaw('(loan.due_date = ?)', [date("Y-m-d", strtotime("+" . $days . " day"))]);
        $count = $query->count('loan.loan_id');
        $loans = $query
            ->select('loan.loan_id')
            ->select('loan.loan_date')
            ->select('loan.due_date')
            ->select('loan.member_id')
            ->select('item.item_code')
            ->select('mct.coll_type_name', 'collection_type')
            ->select('b.biblio_id', 'biblio_id')
            ->select('b.title', 'title')
            ->join('biblio', ['item.biblio_id', '=', 'b.biblio_id'], 'b')
            ->join('mst_coll_type', ['item.coll_type_id', '=', 'mct.coll_type_id'], 'mct')
            ->limit($limit)->offset($offset)->findMany();

        $rows = [];
        foreach ($loans as $loan) {
            $tmp = $loan->asArray();
            $tmp['authors'] = $this->getAuthors($tmp['biblio_id']);
            unset($tmp['biblio_id']);
            $rows[] = $tmp;
        }

        return json_encode([
            'slims' => [
                'total' => $count,
                'per_page' => $limit,
                'current_page' => $this->getPage(),
                'data' => $rows,
                'token' => $this->auth->getToken()
            ]
        ]);
    }

    function getAuthors($biblio_id, $simple = true)
    {
        $authors = \ORM::forTable('biblio_author')
            ->tableAlias('ba')
            ->join('mst_author', ['ba.author_id', '=', 'ma.author_id'], 'ma')
            ->select('ma.author_name', 'name')
            ->select('ba.level')
            ->where('ba.biblio_id', $biblio_id)
            ->findMany();

        $rows = [];
        foreach ($authors as $author) {
            if ($simple) {
                $author = $author->name;
            } else {
                $author = $author->asArray();
                $author['level'] = $this->config('authority_level.' . $author['level']);
            }
            $rows[] = $author;
        }
        return $rows;
    }

    /**
     * @return mixed
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param Auth $auth
     * @return Loans
     */
    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
        return $this;
    }
}