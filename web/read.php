<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'inc/bookClass.php';
require('inc/smarty/Smarty.class.php');
require_once 'inc/config.php';

$book = new bookClass($db);

if ($_REQUEST['book']) $bookId = $_REQUEST['book'];
else{
    echo "Book ID is invalid.";
    exit;
}
if ($_REQUEST['cid']) $chapter = $_REQUEST['cid'];
else{
    echo "Chapter ID is invalid.";
    exit;
}

$bookInfo = $book->getBookInfo($bookId);
$article = $book->getArticleInfo($bookId,$chapter);
//var_dump($bookInfo);
$smarty->assign('bookInfo',$bookInfo);
$smarty->assign('article',$article);
$smarty->display("content.html");

