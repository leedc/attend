<?php
class ManageController extends Yaf_Controller_Abstract {

    public function init ()
    {

    }

    public function  indexAction()
    {
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }
    public function  mcollegeAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from collage ");
        $collagees = $rs->fetchAll();
        $this->getView()->assign("collagees", $collagees);
        return true;
    }
    public function  maddcollegeAction()
    {
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }
    public function mdocollegeAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $name = $req -> getPost('name');
        if($name){
            $rs = $dbh->query("select * from collage where name='{$name}'");
            $collagees = $rs->fetchAll();
            if(!$collagees){
                $dbh -> exec("insert into collage(name) value('{$name}')");
            }
        }
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mcollege\");</script>";exit;
    }

    public function mdelcollegeAction(){
        $dbh = Yaf_Registry::get('_db');
        $id = $_GET['id'];
        $dbh->exec("delete from collage where id={$id}");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mcollege\");</script>";exit;
    }
    public function  mclassroomAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from classroom ");
        $classrooms = $rs->fetchAll();
        $this->getView()->assign("classrooms", $classrooms);
        return true;
    }
    public function  maddclassroomAction()
    {
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }
    public function mdoclassroomAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $name = $req -> getPost('name');
        if($name){
            $rs = $dbh->query("select * from classroom where classroom='{$name}'");
            $collagees = $rs->fetchAll();
            if(!$collagees){
                $dbh -> exec("insert into classroom(classroom) value('{$name}')");
            }
        }
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mclassroom\");</script>";exit;
    }

    public function mdelclassroomAction(){
        $dbh = Yaf_Registry::get('_db');
        $id = $_GET['id'];
        $dbh->exec("delete from classroom where id={$id}");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mclassroom\");</script>";exit;
    }
    public function  mmajorAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from major ");
        $major = $rs->fetchAll();
        $this->getView()->assign("majors", $major);
        return true;
    }
    public function  maddmajorAction()
    {
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }
    public function mdomajorAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $name = $req -> getPost('name');
        if($name){
            $rs = $dbh->query("select * from major where majorname='{$name}'");
            $collagees = $rs->fetchAll();
            if(!$collagees){
                $dbh -> exec("insert into major(majorname) value('{$name}')");
            }
        }
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mmajor\");</script>";exit;
    }

    public function mdelmajorAction(){
        $dbh = Yaf_Registry::get('_db');
        $id = $_GET['id'];
        $dbh->exec("delete from major where id={$id}");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mmajor\");</script>";exit;
    }
    public function mteacherAction(){
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from teacher  order by tid");
        $teachers = $rs->fetchAll();
        $i=0;
        foreach($teachers as $teacher){
            $rs = $dbh->query("select * from class where hid={$teacher['tid']} ");
            $class = $rs->fetch();
            $htclass='';
            if($class){
                $htclass.=$class['cid'];
            }
            $teacher['hclass']=$htclass;
            $teachers[$i]=$teacher;
            $i++;
        }
        $this->getView()->assign("teachers", $teachers);
    }
    public function madminAction(){
        $dbh = Yaf_Registry::get('_db');
        $tid=$_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            $val = $teacher['isadmin']=='1'?0:1;
            $dbh->exec("update teacher set isadmin={$val} where tid='{$tid}'");
        }


        echo "<script>window.location.assign(\"/index.php?c=manage&a=mteacher\");</script>";exit;
    }
    public function mheadAction(){
        $dbh = Yaf_Registry::get('_db');
        $tid=$_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            $val = $teacher['isheadtea']=='1'?0:1;
            $dbh->exec("update teacher set isheadtea={$val} where tid='{$tid}'");
        }


        echo "<script>window.location.assign(\"/index.php?c=manage&a=mteacher\");</script>";exit;
    }
    public function msuperAction(){
        $dbh = Yaf_Registry::get('_db');
        $tid=$_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            $val = $teacher['issupervisor']=='1'?0:1;
            $dbh->exec("update teacher set issupervisor={$val} where tid='{$tid}'");
        }


        echo "<script>window.location.assign(\"/index.php?c=manage&a=mteacher\");</script>";exit;
    }
    public function resetpwdAction(){
        $dbh = Yaf_Registry::get('_db');
        $tid=$_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            $val = md5($teacher[tid]);
            $dbh->exec("update teacher set password='{$val}' where tid='{$tid}'");
        }
    }
    public  function maddteacherAction(){
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }
    public function mdoteacherAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $tid = $req -> getPost('tid');
        $name = $req -> getPost('name');
        $email = $req -> getPost('email');
        $ntid=$tid+0;
        if(!($ntid/100000>1&&$ntid/1000000<1)){
            echo "<script>alert('学工号必须是6位数字');window.history.back();</script>";exit;
        }
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            echo "<script>alert('学工号已存在');window.history.back();</script>";exit;
        }
        $paswd=md5($tid);
        $dbh->exec("insert into teacher(tid,username,password,email) value('{$tid}','{$name}','{$paswd}','{$email}')");

        echo "<script>window.location.assign(\"/index.php?c=manage&a=mteacher\");</script>";exit;
    }
    public function meditteacherAction(){
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);


        $tid = $_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        $this->getView()->assign("teacher", $teacher);
        return true;
    }
    public function mdoeditteacherAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $tid = $req -> getPost('tid');
        $name = $req -> getPost('name');
        $email = $req -> getPost('email');
        $dbh->exec("update teacher set username='{$name}',email='{$email}' where tid='{$tid}'");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mteacher\");</script>";exit;
    }
    public function mclassAction(){
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);


        $tid = $_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        $this->getView()->assign("teacher", $teacher);
        return true;
    }
}