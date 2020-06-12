<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 10/06/20 17.27
 * @File name           : index.php
 */

/*
|--------------------------------------------------------------------------
| Key to authenticate
|--------------------------------------------------------------------------
*/

use Messenger\Loans;

define('INDEX_AUTH', 1);

/*
|--------------------------------------------------------------------------
| Required library
|--------------------------------------------------------------------------
*/
require_once '../../sysconfig.inc.php';
require_once LIB . 'klaras/autoload.php';
require_once 'idiorm.php';
require_once 'Commons.php';
require_once 'Loans.php';
require_once 'Auth.php';

/*
|--------------------------------------------------------------------------
| Configure database connection
|--------------------------------------------------------------------------
*/
ORM::configure('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME);
ORM::configure('username', DB_USERNAME);
ORM::configure('password', DB_PASSWORD);
ORM::configure('return_result_sets', true);
ORM::configure('id_column_overrides', array(
    'biblio' => 'biblio_id',
    'item' => 'item_id',
    'member' => 'member_id',
    'loan' => 'loan_id',
    'setting' => 'setting_id',
));
ORM::configure('error_mode', PDO::ERRMODE_WARNING);
ORM::configure('logging', true);

/*
|--------------------------------------------------------------------------
| All response is json
|--------------------------------------------------------------------------
*/
header('Content-Type: application/json');

/*
|--------------------------------------------------------------------------
| Mapping request
|--------------------------------------------------------------------------
*/
$request = isset($_GET['r']) ? $_GET['r'] : '';
switch ($request) {
    case 'loans':
        echo Loans::init()->loans();
        break;
    case 'overdue':
        echo Loans::init()->overdue();
        break;
    case 'duedate':
        $days = isset($_GET['d']) && ((int)$_GET['d'] > 0) ? (int)$_GET['d'] : 3;
        echo Loans::init()->duedate($days);
        break;
    default:
        $auth = \Messenger\Auth::verify(false);

        $response = [
            'message' => 'Messenger API for SLiMS',
            'version' => '1.0.0',
        ];

        if ($auth->getToken())
            $response['token'] = $auth->getToken();

        echo json_encode($response);
}
