#!/usr/bin/php
<?php

require_once 'inc/bookClass.php';
require('inc/smarty/Smarty.class.php');
require_once 'inc/config.php';

$book = new bookClass($db);

$sql = "select book_id,book_name from book ";
//$sql.="where book_id=5409";
//$sql.="where status=0";

$result = $db->query($sql);
if ($result->num_rows == 0){
    $result->close();
    echo "No book exists.\n";
    exit;
}

$menu = '';
while($row = $result->fetch_assoc()){
    $content = $book->exportBook($row['book_id']);
    if (!file_exists(EXPORT_PATH)){
        mkdir(EXPORT_PATH);
    }
    if (strlen($row['book_id'])==1){
        $pre = '000';
    }elseif (strlen($row['book_id'])==2){
        $pre = '00';
    }elseif (strlen($row['book_id'])==3){
        $pre = '0';
    }else{
        $pre = '';
    }
	$row['book_name'] = iconv('UTF-8', 'GBK', $row['book_name']);
	$content = $row['book_id'].' '.$row['book_name']."\r\n".$content;
    file_put_contents("book/$pre".$row['book_id'].$row['book_name'].".txt", $content);
    $menu.= $row['book_id']."\t".$row['book_name']."\r\n";
    echo "book ".$row['book_id']." exported.\n";
    //exit;
}
file_put_contents(EXPORT_PATH."/menu.txt", $menu);
