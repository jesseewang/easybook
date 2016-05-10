<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'inc/bookClass.php';
require('inc/smarty/Smarty.class.php');
require_once 'inc/config.php';

$book = new bookClass($db);

if (isset($_REQUEST['book'])) $bookId = $_REQUEST['book'];
else{
    echo "Book ID is invalid.";
    exit;
}

$list = $book->getArticleList($bookId);
//var_dump($list);
$smarty->assign('articleList',$list);
$smarty->display("directory.html");

