<?php
class IndexController extends Yaf_Controller_Abstract
{

    public function init()
    {

    }

    public function  indexAction()
    {
        return true;
    }

    public function  mainAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        return true;
    }
    public function  attendAction()
    {
        $user = Yaf_Session::getInstance()->get("user");
        $this -> getView() -> assign("user",$user);
        return true;
    }
    public function addinfoAction(){
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        $rs = $dbh->query("select * from classroom order by  convert(classroom using gb2312) ");
        $classrooms = $rs->fetchAll();
        $this -> getView() -> assign("classrooms",$classrooms);
        $rs = $dbh->query("select * from collage order by  convert(name using gb2312) ");
        $collages = $rs->fetchAll();
        $this -> getView() -> assign("collages",$collages);
        $rs = $dbh->query("select * from class order by  convert(cid using gb2312) ");
        $classes = $rs->fetchAll();
        $this -> getView() -> assign("classes",$classes);
        $rs = $dbh->query("select * from teacher order by  convert(username using gb2312) ");
        $teachers = $rs->fetchAll();
        $this -> getView() -> assign("teachers",$teachers);

        return true;

    }
    public function doinfoAction() {
        $aid = Yaf_Session::getInstance()->get("user");
        $req = $this -> getRequest();
        $date = $req -> getPost('date');
        $date = $date[6].$date[7].$date[8].$date[9].$date[0].$date[1].$date[3].$date[4];
        $time=date('Ymd',time())+0;
        $datei =($date)+0;
        if(!($datei <= $time && $datei >= ($time - 2)))
        {
            echo "<script>alert('只允许输入两天以内的听课记录');window.history.back();</script>";exit;
        }
        $tid = $req -> getPost('teacher');
        $sector = $req -> getPost('sector');
        $classes = $req -> getPost('classes');
        $classroom = $req -> getPost('classroom');
        $classname = $req -> getPost('classname');
        $collage = $req -> getPost('collage');
        $dbh = Yaf_Registry::get('_db');
        if($classes==null){
            echo "<script>alert('请选择上课班级');window.history.back();</script>";exit;
        }
        if($classname==null){
            echo "<script>alert('请输入课程名称');window.history.back();</script>";exit;
        }
        if($dbh -> exec("insert into attend(aid, tid, time, sector, classroom, classname,college,part1) value('{$aid}', {$tid}, '{$date}', '{$sector}', '{$classroom}','{$classname}', '{$collage}',1)")){
            $attendid=$dbh -> lastInsertId();
            foreach($classes as $class){
                $dbh -> exec("insert into ctoa value('$class',$attendid)");
            }
            Yaf_Session::getInstance()->set("attendediting",$attendid);
            echo "<script>window.location.assign(\"/index.php?c=index&a=addattend\");</script>";
            exit;
        }
    }
    public function addattendAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);//;
        $rs = $dbh->query("select sum(count) from ctoa inner join class where ctoa.cid=class.cid AND ctoa.aid= $attendid");
        $stunum = $rs->fetch();
        $this -> getView() -> assign("stunum",$stunum);
    }
    public function doattendAction() {
        $req = $this -> getRequest();
        $latetime = $req -> getPost("latetime");
        $earlytime = $req -> getPost("earlytime");
        $other = $req -> getPost("other");
        $stunum = $req -> getPost("stunum")+0;
        $comenum = $req -> getPost("comenum")+0;
        $latenum = $req -> getPost("latenum")+0;
        $assess[] = $req -> getPost("assess1");
        $assess[] = $req -> getPost("assess2");
        $assess[] = $req -> getPost("assess3");
        $assess[] = $req -> getPost("assess4");
        $assess[] = $req -> getPost("assess5");
        $assess[] = $req -> getPost("assess6");
        $assess[] = $req -> getPost("assess7");
        $assess[] = $req -> getPost("assess8");
        $assess[] = $req -> getPost("assess9");
        $assess[] = $req -> getPost("assess10");
        $assess[] = $req -> getPost("assess");
        if($comenum < 0 || $latenum < 0)
        {
            echo "<script>alert('实际上课人数或迟到不能小于0');window.history.back();</script>";exit;
        }
        if($comenum > $stunum || $latenum > $stunum)
        {
            echo "<script>alert('实际上课人数或迟到不能大于学生人数');window.history.back();</script>";exit;
        }
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if($attendid){
            $dbh = Yaf_Registry::get('_db');
            $dbh -> exec("update attend set latetime={$latetime},earlytime={$earlytime},other='{$other}',stunum={$stunum},latenum={$latenum},comenum={$comenum},assess1='{$assess[0]}',assess2='{$assess[1]}',assess3='{$assess[2]}',assess4='{$assess[3]}',assess5='{$assess[4]}',assess6='{$assess[5]}',assess7='{$assess[6]}',assess8='{$assess[7]}',assess9='{$assess[8]}',assess10='{$assess[9]}',assess={$assess[10]},part2=1 where id={$attendid}");
            echo "<script>window.location.assign(\"/index.php?c=index&a=addsug\");</script>";
        }else{
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
    }

    public function addsugAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
    }
    public function dosugAction() {
        $req = $this -> getRequest();
        $suggeststudent = $req -> getPost("suggeststudent");

        if( strlen($suggeststudent) < 90) {
            echo "<script>alert('对学生听课情况评价字数不能少于30个字');window.history.go(-1);;</script>";exit;
        }
        if( strlen($suggeststudent) > 900) {
            echo "<script>alert('对学生听课情况评价字数不能大于300个字');window.history.go(-1);;</script>";exit;
        }
        $suggestteacher = $req -> getPost("suggestteacher");
        if( strlen($suggestteacher) < 90) {
            echo "<script>alert('对教师教学情况评价字数不能少于30个字');window.history.go(-1);;</script>";exit;
        }
        if( strlen($suggestteacher) > 900) {
            echo "<script>alert('对教师教学情况评价字数不能大于300个字');window.history.go(-1);;</script>";exit;
        }
        $suggesteach = $req -> getPost("suggesteach");
        if( strlen($suggesteach) < 90) {
            echo "<script>alert('对教学的建议字数不能少于30个字');window.history.go(-1);;</script>";exit;
        }
        if( strlen($suggesteach) > 900) {
            echo "<script>alert('对教学的建议字数不能大于300个字');window.history.go(-1);;</script>";exit;
        }
        $dbh = Yaf_Registry::get('_db');
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        $dbh -> exec("update attend set suggeststudent='{$suggeststudent}',suggestteacher='{$suggestteacher}',suggesteach='{$suggesteach}',part3=1  where id={$attendid}");
        echo "<script>window.location.assign(\"/index.php?c=index&a=addphoto\");</script>";
    }
    public function addphotoAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
    }
    public function dophotoAction() {
        $req = $this -> getRequest();
        $note = $req -> getPost("note");
        var_dump($note);
        var_dump($_FILES);
        exit;
    }
}