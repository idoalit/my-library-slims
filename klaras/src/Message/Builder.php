<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 12.02
 * @File name           : Builder.php
 */

namespace Klaras\Message;


use Klaras\Utils\Token;

class Builder
{
    protected $data;
    protected $type;

    static function init()
    {
        return new static();
    }

    function receiptFromSession()
    {
        $receipt = new Receipt();

        if (!isset($_SESSION['receipt_record'])) return $this;

        $receipt->setMemberId($_SESSION['receipt_record']['memberID']);
        $receipt->setMemberName($_SESSION['receipt_record']['memberName']);
        $receipt->setStaff($_SESSION['uname']);

        // loans
        if (isset($_SESSION['receipt_record']['loan'])) {
            foreach ($_SESSION['receipt_record']['loan'] as $item) {
                $receipt->addLoanItem(
                    (new Item())
                        ->setItemCode($item['itemCode'])
                        ->setTitle($item['title'])
                        ->setLoanDate($item['loanDate'])
                        ->setDueDate($item['dueDate'])
                );
            }
        }

        // to remove extended items from return session list
        if (isset($_SESSION['receipt_record']['return']) AND isset($_SESSION['receipt_record']['extend'])) {
            foreach ($_SESSION['receipt_record']['extend'] as $key => $value) {
                if ($_SESSION['receipt_record']['extend'][$key]['itemCode'] == $_SESSION['receipt_record']['return'][$key]['itemCode']) {
                    $_SESSION['receipt_record']['extend'][$key]['overdues'] = $_SESSION['receipt_record']['return'][$key]['overdues'];
                    unset($_SESSION['receipt_record']['return'][$key]);
                }
            }
        }


        // extend
        $fines = 0;
        if (isset($_SESSION['receipt_record']['extend'])) {

            foreach ($_SESSION['receipt_record']['extend'] as $item) {
                $receipt->addExtendItem(
                    (new Item())
                        ->setItemCode($item['itemCode'])
                        ->setTitle($item['title'])
                        ->setLoanDate($item['loanDate'])
                        ->setDueDate($item['dueDate'])
                        ->setExtendDate($item['extendDate'])
                        ->setFines($item['overdues'])
                );

                if (!empty($item['overdues']))
                    $fines += (int)$item['overdues']['value'];
            }
        }

        // return
        if (isset($_SESSION['receipt_record']['return'])) {

            foreach ($_SESSION['receipt_record']['return'] as $item) {
                $receipt->addReturnItem(
                    (new Item())
                        ->setItemCode($item['itemCode'])
                        ->setTitle($item['title'])
                        ->setLoanDate($item['loanDate'])
                        ->setReturnDate($item['returnDate'])
                        ->setFines($item['overdues'])
                );

                if (!empty($item['overdues']))
                    $fines += (int)$item['overdues']['value'];
            }

        }

        $receipt->setFines($fines);
        $this->setData($receipt);

        return $this;
    }

    /**
     * @param mixed $data
     * @return Builder
     */
    public
    function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param mixed $type
     * @return Builder
     */
    public
    function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public
    function send()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://slims.web.id/messenger/api/v1/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer " . Token::getToken()
            ),
            CURLOPT_POSTFIELDS => json_encode([
                'type' => $this->type,
                'data' => $this->data
            ])
        ));

        $response = curl_exec($curl);
        var_dump($response);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return true;
        }
    }
}
