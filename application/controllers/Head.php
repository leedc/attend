<?php
class HeadController extends Yaf_Controller_Abstract {

    public function init ()
    {

    }
    public function  indexAction (){

        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $userid = $user['tid'];

        $rs = $dbh->query("select * from class where hid='{$userid}'");
        $class = $rs->fetch();
        $rs = $dbh->query("select * from attend inner join ctoa where ctoa.aid=attend.id and ctoa.cid='{$class['cid']}'");
        $attends = $rs->fetchAll();
        $this -> getView() -> assign("attends",$attends);

        return true;

    }


}