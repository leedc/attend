<?php
class LoginController extends Yaf_Controller_Abstract {

    public function init ()
    {

    }
    //show login page
    public function  indexAction (){
        return  true;
    }
    //do login and ture to home page
    public function  loginAction (){
        $req = $this -> getRequest();
        $id = $req -> getPost('username');
        $psw = md5($req -> getPost('password'));
        $rem = $req -> getPost('remember');
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$id}' and password='{$psw}'");
        $row = $rs->fetch();

        if($row) {
            if($rem){
                setcookie("user",$row[tid]);
            }
            Yaf_Session::getInstance()->set("user",$row[tid]);
            echo "<script>window.location.assign(\"/index.php\");</script>";
        }
        else {
            echo "<script>alert('用户名或密码错误');window.history.back();</script>";
        }


        return  true;
    }


}