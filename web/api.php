<?php
/**
 * Created by PhpStorm.
 * User: Jessee
 * Date: 2016/3/4
 * Time: 15:59
 */

require_once 'inc/bookClass.php';
require('inc/smarty/Smarty.class.php');
require_once 'inc/config.php';

$book = new bookClass($db);

$cmd = $_REQUEST['cmd'];

$response = array();

switch ($cmd){
    case 'book':
        $response = $book->getBookList(1, 50);
        $response['result'] = 0;
        $response['count'] = 50;
        break;
    case 'list':
        if (isset($_REQUEST['book'])){
            $bookId = $_REQUEST['book'];
            $response = $book->getArticleList($bookId);
            $response['count'] = count($response);
            $response['result'] = 1;
        }else{
            $response['result'] = 3;
            $response['msg'] = "Book ID is invalid.";
        }
        break;
    case 'read':
        if (!$_REQUEST['book']){
            $response['result'] = 3;
            $response['msg'] = "Book ID is invalid.";
            break;
        }
        if (!$_REQUEST['cid']) {
            $response['result'] = 3;
            $response['msg'] = "Chapter ID is invalid.";
            break;
        }

        $bookId = $_REQUEST['book'];
        $chapter = $_REQUEST['cid'];
        $response = $book->getArticleInfo($bookId,$chapter);
        $response['result'] = 2;
        break;
    default:
        $response = array('result'=>3,'msg'=>'Unknown error.');
}

echo '['.json_encode($response).']';