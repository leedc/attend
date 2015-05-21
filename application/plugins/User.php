<?php
class UserPlugin extends Yaf_Plugin_Abstract {
    public function  routerStartup ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {
        /* 在路由之前执行,这个钩子里，你可以做url重写等功能 */
    }
    public function  routerShutdown ( Yaf_Request_Abstract $request ,  Yaf_Response_Abstract $response ) {
        /* 路由完成后，在这个钩子里，你可以做登陆检测等功能*/
        $user='root';
        $pass='admin';
        $dbh = new PDO('mysql:host=localhost;dbname=lecture', $user, $pass);
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
        }
        if($request -> getControllerName() == 'Pioneer'){
            $userid = Yaf_Session::getInstance()->get("user");
            $rs = $dbh->query("select * from class where pid='{$userid}'");
            $classes = $rs->fetchAll();
            if(!$classes){
                echo "<script>window.location.assign(\"/index.php\");</script>";exit;
            }
        }
        if($request -> getControllerName() == 'Head'){
            $userid = Yaf_Session::getInstance()->get("user");
            $rs = $dbh->query("select * from class where hid='{$userid}'");
            $classes = $rs->fetchAll();
            if(!$classes){
                echo "<script>window.location.assign(\"/index.php\");</script>";exit;
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
