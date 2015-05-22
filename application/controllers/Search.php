<?php
class SearchController extends Yaf_Controller_Abstract
{

    public function init()
    {

    }
    public function  indexAction()
    {

    }
    public function  majorlistAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    public function  majorbrowseAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    public function  gradelistAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    public function  gradebrowseAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    public function  classlistAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    public function  classbrowseAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }

}