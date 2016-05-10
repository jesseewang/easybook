<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dbClass
 *
 * @author Jessee
 */
class bookClass {
    public $db;
    public $book;
    public $articles = array();

    function __construct($db=0) {
        if (!is_object($db)){
            $this->db = new mysqli('localhost','root','mysql','easybook');
        }else{
            $this->db = $db;
        }
    }

    function saveBook($bookInfo){
        if (empty($bookInfo)){
            return 4; // data is invalid
        }
        $isHot = $bookInfo['isHot']=='New'?0:1;
        $score = 0;
        switch($bookInfo['tag']){
            case 'hot':
                $score = 20;
                break;
            case 'h':
                $score = 10;
                break;
            case 'n':
                $score = 30;
                break;
        }
        $url = $this->db->escape_string($bookInfo['url']);
        $name = $this->db->escape_string($bookInfo['name']);
        $author = $this->db->escape_string($bookInfo['author']);

        // check whether the book exists in database or not
        $result = $this->db->query("select book_id,book_name from book where book_url='$url'");
        list($id,$bookName) = $result->fetch_array(MYSQLI_NUM);
        if ($result->num_rows >= 1){
            $this->book = $id;
            $result->close();
            if ($bookName != $bookInfo['name']){
                $this->db->query("update book set book_name='$name' where book_id=$id");
            }
            return 3; // book exists
        }
        $result->close();

        // insert the new book info into database
        $now = date("Y-m-d H:i:s");
        $sql = "insert into book (book_name,book_url,author,status,isHot,create_date) "
                ."values('$name','$url','$author',1,$score,'$now')";
        //echo $sql;
        if ($this->db->query($sql)){
            $this->book = $this->db->insert_id;
            return 1; // Save successfully
        }else{
            return 2; // Failed to save data into database
        }
    }

    function setCompleteBook($bookInfo){
        if (empty($bookInfo)){
            return 4; // data is invalid
        }
        $url = $this->db->escape_string($bookInfo['url']);
        $classify = $this->db->escape_string($bookInfo['classify']);

        // check whether the book exists in database or not
        if ($this->db->query("update book set status=0,classify='$classify' where book_url='$url'")){
            return 1; // Save successfully
        }else{
            return 2; // Failed to save data into database
        }
    }

    function setBookChapter($bookId, $total){
        if (!$bookId || !$total){
            return false;
        }
        if ($this->db->query("update book set total=$total where book_id=$bookId")){
            return true;
        }else{
            return false;
        }
    }

    function getBookChapter($id){
        if (!$id){
            return false;
        }
        $result = $this->db->query("select total from book where book_id=$id");
        list($total) = $result->fetch_array(MYSQL_NUM);
        return $total;
    }

    function getBookIdByUrl($url){
        if (!$url){
            return false;
        }
        $result = $this->db->query("select book_id from book where book_url='$url'");
        list($id) = $result->fetch_array(MYSQL_NUM);
        return $id;
    }

    function saveContentLink($bookId, $urlAry, $exist = false){
        if (!is_array($urlAry)||empty($bookId)){
            return false;
        }
        if ($exist){
            $result = $this->db->query("select current,total from book where book_id=$bookId");
            list($current,$total) = $result->fetch_array(MYSQL_NUM);
            /*if ($total >= count($urlAry)){
                return true;
            }*/
        }
        for($i=0;$i<count($urlAry);$i++){
            if ($exist && $i+1 <= $current){
                continue;
            }
            $result = $this->db->query("select count(*) from article where article_url='".$urlAry[$i]."'");
            list($num) = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            if ($num == 1)continue; // article exists
            $sql = "insert into article (book_id,chapter,article_url,create_date) "
                    ."values($bookId,".($i+1).",'".$urlAry[$i]."','".date("Y-m-d H:i:s")."')";
            //echo "$sql\n";
            $this->db->query($sql);
        }
    }

    function saveArticleContent($articleId,$content){
        if (empty($articleId)||empty($content)) return false;

        $content = $this->db->escape_string($content);
        $sql = "update article set content='$content',isUpdate=1 where article_id=$articleId";
        if ($this->db->query($sql)){
            $this->db->query("update book set current=current+1 where book_id=(select book_id from article where article_id=$articleId)");
            return true;
        }else{
            return false;
        }
    }

    function exportBook($bookId){
        if (empty($bookId)) return false;

        $sql = "select * from article where book_id=$bookId order by chapter asc";
        $result = $this->db->query($sql);
        if ($result->num_rows == 0){
            $result->close();
            return false;
        }
        $content = '';
        while ($row = $result->fetch_assoc()){
            $content.= $row['content'];
        }
        $content = iconv('UTF-8', 'GBK', $content);
        return $content;
    }

    function getBookList($page,$pageSize){
        $start = ($page -1)*$pageSize;
        $sql = "select * from book limit $start,$pageSize";
        $bookList = array();
        $result = $this->db->query($sql);
        while($row = $result->fetch_assoc()){
            $bookList[] = $row;
        }
        return $bookList;
    }

    function getArticleList($id){
        $sql = "select article_id,book_id,chapter from article where book_id=$id";
        $list = array();
        $result = $this->db->query($sql);
        while($row = $result->fetch_assoc()){
            $list[] = $row;
        }
        return $list;
    }

    function countBook(){
        $sql = "select count(*) from book";
        $result = $this->db->query($sql);
        list($num) = $result->fetch_array();
        return $num;
    }

    function getBookInfo($id){
        $sql = "select * from book where book_id=$id";
        $result = $this->db->query($sql);
        $info = $result->fetch_assoc();
        return $info;
    }

    function getArticleInfo($id,$chapter){
        $sql = "select * from article where book_id=$id and chapter=$chapter";
        $result = $this->db->query($sql);
        $info = $result->fetch_assoc();
        return $info;
    }
}
?>
