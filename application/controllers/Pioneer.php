<?php
class PioneerController extends Yaf_Controller_Abstract {

    public function init ()
    {

    }
    //show login page
    public function  indexAction (){

        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
//        $rs = $dbh->query("select * from class where hid='{$userid}'");
//        $class = $rs->fetch();
        $classid= $_GET['cid'];
        $rs = $dbh->query("select * from attend inner join ctoa where ctoa.aid=attend.id and ctoa.cid='{$classid}'");
        $attends = $rs->fetchAll();
        $this -> getView() -> assign("attends",$attends);

        return true;
    }


}