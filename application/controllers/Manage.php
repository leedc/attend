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
    public function mpioneerAction(){
        $dbh = Yaf_Registry::get('_db');
        $tid=$_GET['tid'];
        $rs = $dbh->query("select * from teacher  where tid='{$tid}'");
        $teacher = $rs->fetch();
        if($teacher){
            $val = $teacher['ispioneer']=='1'?0:1;
            $dbh->exec("update teacher set ispioneer={$val} where tid='{$tid}'");
            if($teacher['ispioneer']=='1'){
                $dbh->exec("update class set pid='' where pid='{$tid}'");
            }

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

        $rs = $dbh->query("select * from class inner join major where major.id=class.mid   ");
        $classes = $rs->fetchAll();
        $i=0;
        foreach($classes as $class){
            $rs = $dbh->query("select username from teacher where tid='{$class['hid']}'");
            $name = $rs->fetch();
            $class['hteacher']=$name['username'];
            $rs = $dbh->query("select username from teacher where tid='{$class['pid']}'");
            $name = $rs->fetch();
            $class['pteacher']=$name['username'];
            $classes[$i]=$class;$i++;
        }
        $this->getView()->assign("classes", $classes);
        return true;
    }
    public function maddclassAction(){
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from major ");
        $majors=$rs->fetchAll();
        $rs = $dbh->query("select * from teacher ");
        $teacher=$rs->fetchAll();
        $this->getView()->assign("teachers", $teacher);
        $this->getView()->assign("majors", $majors);
    }
    public function mdoclassAction(){
        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $cid = $req -> getPost('cid');
        $mid = $req -> getPost('mid');
        $hid = $req -> getPost('hid');
        $pid = $req -> getPost('pid');
        $year = $req -> getPost('year');
        $count = $req -> getPost('count');

        $rs = $dbh->query("select * from class  where cid='{$cid}'");
        $class = $rs->fetch();
        if($class){
            echo "<script>alert('班级已存在');window.history.back();</script>";exit;
        }
        $dbh->exec("insert into class(cid,hid,pid,mid,year,count) value('{$cid}','{$hid}','{$pid}',{$mid},{$year},{$count})");
        $dbh->exec("update teacher set isheadtea=1 where tid='{$hid}'");

        echo "<script>window.location.assign(\"/index.php?c=manage&a=mclass\");</script>";exit;
    }
    public function mdelclassAction(){
        $dbh = Yaf_Registry::get('_db');
        $cid = $_GET['cid'];
        $rs = $dbh->query("select * from class  where cid='{$cid}'");
        $class = $rs->fetch();
        $dbh->exec("delete from class where cid='{$cid}'");
        $dbh->exec("update teacher set isheadtea=0 where tid='{$class['hid']}'");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mclass\");</script>";exit;
    }
    public function meditclassAction(){


        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $rs = $dbh->query("select * from major ");
        $majors=$rs->fetchAll();
        $rs = $dbh->query("select * from teacher ");
        $teacher=$rs->fetchAll();
        $this->getView()->assign("teachers", $teacher);
        $this->getView()->assign("majors", $majors);

        $cid = $_GET['cid'];
        $rs = $dbh->query("select * from class  where cid='{$cid}'");
        $class = $rs->fetch();
        if($class){
            $this->getView()->assign("class", $class);
        }else{
            echo "<script>window.location.assign(\"/index.php?c=manage&a=mclass\");</script>";exit;
        }
    }
    public function mdoeditclassAction(){

        $dbh = Yaf_Registry::get('_db');
        $req = $this -> getRequest();
        $cid = $req -> getPost('cid');
        $mid = $req -> getPost('mid');
        $hid = $req -> getPost('hid');
        $pid = $req -> getPost('pid');
        $year = $req -> getPost('year');
        $count = $req -> getPost('count');
        $rs = $dbh->query("select * from class  where cid='{$cid}'");
        $class = $rs->fetch();
        $dbh->exec("update class set hid='{$hid}',pid='{$pid}',mid={$mid},year={$year},count={$count} where cid='{$cid}'");
        $dbh->exec("update teacher set isheadtea=0 where tid='{$class['hid']}'");
        $dbh->exec("update teacher set isheadtea=1 where tid='{$hid}'");
        echo "<script>window.location.assign(\"/index.php?c=manage&a=mclass\");</script>";exit;

    }
    public function classesAction(){
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        return true;
    }

}