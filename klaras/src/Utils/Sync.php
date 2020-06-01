<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 09.24
 * @File name           : Sync.php
 */

namespace Klaras\Utils;

class Sync
{
    protected $last_sync;

    function member() {
        $data = [
            'member_id', 'member_name', 'member_type', 'member_email', 'phone_number'
        ];
    }

    function biblio() {
        $data = [
            'title', 'author', 'isbn', 'items' => [
                'item_code'
            ]
        ];
    }

    function loan() {
        $data = [
            'member_id', 'item_code', 'loan_date', 'due_date', 'return_date'
        ];
    }
}