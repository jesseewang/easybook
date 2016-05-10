<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'inc/bookClass.php';
require('inc/smarty/Smarty.class.php');
require_once 'inc/config.php';

$book = new bookClass($db);

$page = 1;
if (isset($_REQUEST['p'])) $page = $_REQUEST['p'];
if ($page < 1) $page =1;
$pageSize = 50;
$total = $book->countBook();
$totalPage = ceil($total/$pageSize);
if ($page > $totalPage) $page =$totalPage;

$list = $book->getBookList($page, $pageSize);
//var_dump($list);
$smarty->assign('count',$total);
$smarty->assign('page',$page);
$smarty->assign("totalPage",$totalPage);
$smarty->assign('bookList',$list);
$smarty->display("list.html");

