<?php
class UserPlugin extends Yaf_Plugin_Abstract {
    public function  routerStartup ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {
        /* 在路由之前执行,这个钩子里，你可以做url重写等功能 */
    }
    public function  routerShutdown ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {




        /* 路由完成后，在这个钩子里，你可以做登陆检测等功能*/
        $user='root';
        $pass='root';
        $_opts_values = array(PDO::ATTR_PERSISTENT=>true,PDO::ATTR_ERRMODE=>2,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
        $dbh = new PDO('mysql:host=localhost;dbname=lecture', $user, $pass, $_opts_values);
        Yaf_Registry::set('_db', $dbh);

        if($request->getControllerName() != 'Login'){
            if(!Yaf_Session::getInstance()->has("user")){

                if($_COOKIE['user']){
                    Yaf_Session::getInstance()->set("user",$_COOKIE['user']);
                }else{
                    echo "<script>window.location.assign(\"/index.php?c=login\");</script>";
                    exit;
                }
            }
            $userid = Yaf_Session::getInstance()->get("user");
            $rs = $dbh->query("select * from teacher where tid='{$userid}'");
            $user=$rs->fetch();
            if(!$user){
                echo "<script>window.location.assign(\"/index.php?c=login\");</script>";exit;
            }
            Yaf_Registry::set('user', $user);
            if($user['ispioneer']=='1'){
                $rs = $dbh->query("select * from class where pid='{$userid}'");
                $pclasses = $rs->fetchAll();
                Yaf_Registry::set('pclass', $pclasses);
            }
            if($user['isheadtea']=='1'){
                $rs = $dbh->query("select * from class where hid='{$userid}'");
                $hclass = $rs->fetch();
                Yaf_Registry::set('hclass', $hclass);
            }
            if($request -> getControllerName() == 'Pioneer'){
                if(!$pclasses){
                    echo "<script>window.location.assign(\"/index.php\");</script>";exit;
                }
            }
            if($request -> getControllerName() == 'Head'){
                if(!$hclass){
                    echo "<script>window.location.assign(\"/index.php\");</script>";exit;
                }
            }

            if($request -> getControllerName() == 'Search'){
                if($user['ispioneer']!='1' && $user['isadmin']!='1'){
                    echo "<script>window.location.assign(\"/index.php\");</script>";exit;
                }
            }
            if($request -> getControllerName() == 'Manage'){
                if($user['isadmin']!='1'){
                    echo "<script>window.location.assign(\"/index.php\");</script>";exit;
                }
            }
        }

    }
    public function  dispatchLoopStartup ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {


    }
    public function  preDispatch ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {

    }
    public function  postDispatch ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {

    }
    public function  dispatchLoopShutdown ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {
        /* final hoook
          in this hook user can do loging or implement layout */
        $dbh = Yaf_Registry::get('_db');
        $dbh = null;
    }
}
