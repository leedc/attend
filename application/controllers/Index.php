<?php
class IndexController extends Yaf_Controller_Abstract
{

    public function init()
    {

    }

    public function  indexAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);


        return true;
    }
    //home page
    public function  mainAction()
    {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        $rs = $dbh->query("select count(*) as num from attend where aid='{$userid}'");
        $attendtime = $rs->fetch();
        $attend['time'] = $attendtime['num'];
        if($user['isadmin'] == '1'){
            $attend['must'] = 16;
        }else if($user['issupervisor'] == '1'){
            $attend['must'] = 8;
        }else{
            $attend['must'] = 4;
        }
        $this -> getView() -> assign("attend",$attend);

        if($user['isheadtea'] == '1'){
            $rs = $dbh->query("select * from class inner join teacher  inner join major where major.id=class.mid and teacher.tid=class.pid and  class.hid='{$userid}'");
            $head = $rs->fetch();
            $this -> getView() -> assign("head",$head);

        }
        if($user['ispioneer'] == '1'){
            $rs = $dbh->query("select * from class inner join teacher  inner join major where major.id=class.mid and teacher.tid=class.hid and  class.pid='{$userid}'");
            $pioneers = $rs->fetchAll();
            $this -> getView() -> assign("pioneers",$pioneers);
        }
        return true;
    }

    //add lecture info page
    public function addinfoAction(){
        $attendid = $_GET['id'];
        $dbh = Yaf_Registry::get('_db');
        $userid = Yaf_Session::getInstance()->get("user");

        if($attendid){
            $rs = $dbh->query("select * from attend where id='{$attendid}' and aid='$userid'");
            $attend = $rs->fetch();
            if($attend){
                $this -> getView() -> assign("attend",$attend);
                Yaf_Session::getInstance()->set("attendediting",$attendid);
                $rs = $dbh->query("select * from ctoa where aid ='{$attendid}'");
                $aclasses=$rs->fetchAll();
                $i=0;
                foreach($aclasses as $class){
                    $aclasses[$i] = $class['cid'];
                    $i++;
                }
                $this -> getView() -> assign("aclasses",$aclasses);
            }
        }else{
            Yaf_Session::getInstance()->del("attendediting");
        }

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

    //add lecture info
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
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        $userid = Yaf_Session::getInstance()->get("user");
        $rs = $dbh->query("select * from attend where id='{$attendid}' and aid='$userid'");
        $attend = $rs->fetch();
        if($attend) {//(aid, tid, time, sector, classroom, classname,college,part1)
            $flag = $dbh -> exec("update attend set aid='{$aid}',tid='{$tid}',time='{$date}',sector='{$sector}',classroom='{$classroom}',classname='{$classname}',college='{$collage}',part1=1 where id={$attendid}");

            $dbh -> exec("delete from ctoa where aid={$attendid}");
            foreach($classes as $class){
                $dbh -> exec("insert into ctoa value('$class',$attendid)");
            }
            Yaf_Session::getInstance()->set("attendediting",$attendid);

            echo "<script>window.location.assign(\"/index.php?c=index&a=addattend\");</script>";
            exit;
        }else{
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


    }

    //add attend content  page
    public function addattendAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
        $dbh = Yaf_Registry::get('_db');
        $userid = Yaf_Session::getInstance()->get("user");


        $rs = $dbh->query("select * from attend where id='{$attendid}' and aid='$userid'");
        $attend = $rs->fetch();
        $this -> getView() -> assign("attend",$attend);


        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);//;
        $rs = $dbh->query("select sum(count) from ctoa inner join class where ctoa.cid=class.cid AND ctoa.aid= $attendid");
        $stunum = $rs->fetch();
        $this -> getView() -> assign("stunum",$stunum);
    }

    //add attend content
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

    //add sug page
    public function addsugAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }
        $dbh = Yaf_Registry::get('_db');
        $userid = Yaf_Session::getInstance()->get("user");


        $rs = $dbh->query("select * from attend where id='{$attendid}' and aid='$userid'");
        $attend = $rs->fetch();
        $this -> getView() -> assign("attend",$attend);

        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
    }

    //add sug
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

    //add phpto page
    public function addphotoAction(){
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if(!$attendid){
            echo "<script>alert('不存在需要继续编辑或添加的听课记录,将为您跳转到添加课程信息,您也可以通过查看记录选择为提交的记录进行编辑');window.location.assign(\"/index.php?c=index&a=addinfo\");</script>";
        }

        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');

        $rs = $dbh->query("select * from attend where id='{$attendid}' and aid='$userid'");
        $attend = $rs->fetch();
        $this -> getView() -> assign("attend",$attend);

        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
    }

    //add page
    public function dophotoAction() {
        $attendid = Yaf_Session::getInstance()->get("attendediting");
        if($_FILES["photo"]["error"] == UPLOAD_ERR_OK){
            $ext= strtolower(end(explode('.',$_FILES['photo']['name'])));
            $tmp_name = $_FILES["photo"]["tmp_name"];
            $photo= 'photo/'.date("Y_mm_dd",time())."_".$attendid.'_photo.'.$ext;//date("YY-mm-dd",time())."-".$attendid.
            move_uploaded_file($tmp_name, "$photo");
        }
        if($_FILES["note"]["error"] == UPLOAD_ERR_OK){
            $ext= strtolower(end(explode('.',$_FILES['photo']['name'])));
            $tmp_name = $_FILES["note"]["tmp_name"];
            $note= 'photo/'.date("Y_mm_dd",time())."_".$attendid.'_note.'.$ext;//date("YY-mm-dd",time())."-".$attendid.
            move_uploaded_file($tmp_name, "$note");
        }
        $dbh = Yaf_Registry::get('_db');
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select photo,note from attend where id='{$attendid}' and aid='$userid'");
        $attend = $rs->fetch();


        $attendid = Yaf_Session::getInstance()->get("attendediting");
        $dbh -> exec("update attend set photo='{$photo}',note='{$note}',part4=1  where id={$attendid}");
        Yaf_Session::getInstance()->del("attendediting");
        echo "<script>window.location.assign(\"/index.php?c=index&a=main\");</script>";
    }

    //list all attend page
    public function listallAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);

        $rs = $dbh->query("select * from attend inner join teacher where attend.tid=teacher.tid and attend.aid='{$userid}'");
        $attends = $rs->fetchAll();
        $i=0;
        foreach($attends as $attend){
            $rs = $dbh->query("select * from ctoa where aid ='{$attend['id']}'");
            $classes=$rs->fetchAll();
            foreach($classes as $class){
                $attend['classes'].=$class['cid'].=', ';
            }
            $attend['classes']=substr($attend['classes'],0,strlen($attend['classes'])-2);

            $attends[$i]=$attend;
            $i++;
        }
        $this -> getView() -> assign("attends",$attends);
    }

    //list all attend page
    public function listpushAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);

        $rs = $dbh->query("select * from attend inner join teacher where attend.tid=teacher.tid and attend.aid='{$userid}' and attend.status='1'");
        $attends = $rs->fetchAll();
        $i=0;
        foreach($attends as $attend){
            $rs = $dbh->query("select * from ctoa where aid ='{$attend['id']}'");
            $classes=$rs->fetchAll();
            foreach($classes as $class){
                $attend['classes'].=$class['cid'].=', ';
            }
            $attends[$i]=$attend;
            $i++;
        }
        $this -> getView() -> assign("attends",$attends);
    }
    public function listnotpushAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);

        $rs = $dbh->query("select * from attend inner join teacher where attend.tid=teacher.tid and attend.aid='{$userid}' and attend.status='0'");
        $attends = $rs->fetchAll();
        $i=0;
        foreach($attends as $attend){
            $rs = $dbh->query("select * from ctoa where aid ='{$attend['id']}'");
            $classes=$rs->fetchAll();
            foreach($classes as $class){
                $attend['classes'].=$class['cid'].=', ';
            }
            $attends[$i]=$attend;
            $i++;
        }
        $this -> getView() -> assign("attends",$attends);
    }
    public function delattendAction(){

        $attendid = $_GET['id'];
        if($attendid) {
            $userid = Yaf_Session::getInstance()->get("user");
            $dbh = Yaf_Registry::get('_db');
            $dbh->exec("delete from attend where status='0' and aid={$userid} and id='$attendid'");
        }
        echo "<script>window.location.assign(\"/index.php?c=index&a=listall\");</script>";
    }
    public function pushttendAction(){

        $attendid = $_GET['id'];
        if($attendid) {
            $userid = Yaf_Session::getInstance()->get("user");
            $dbh = Yaf_Registry::get('_db');
            $rs = $dbh->query("select * from attend  where aid='{$userid}' and id='{$attendid}'");
            $attends = $rs->fetch();

            $date = $attends['time'];
            $date = ($date[0].$date[1].$date[2].$date[3].$date[5].$date[6].$date[8].$date[9]);
            $time=date('Ymd',time())+0;
            $datei =($date)+0;
            if(!($datei <= $time && $datei >= ($time - 2)))
            {
                echo "<script>alert('只允许提交两天以内的听课记录');window.history.back();</script>";exit;
            }
            $dbh->exec("update attend set status=1 where status='0' and aid={$userid} and id='$attendid'");
        }
        echo "<script>window.location.assign(\"/index.php?c=index&a=listall\");</script>";
    }
    public function listteacherAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        $rs = $dbh->query("select * from attend where tid='{$userid}'");
        $attends = $rs->fetchAll();
        $this -> getView() -> assign("attends",$attends);

    }
    public function listteachAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        $rs = $dbh->query("select * from attend where tid='{$userid}'");
        $attends = $rs->fetchAll();
        $this -> getView() -> assign("attends",$attends);

    }


    public function browseAction() {
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        $attendid = $_GET['id'];
        if($attendid) {
            $rs = $dbh->query("select * from attend  where aid='{$userid}' and id='{$attendid}'");
            $attend = $rs->fetch();
            if($attend){
                $rs = $dbh->query("select * from ctoa where aid ='{$attend['id']}'");
                $classes=$rs->fetchAll();
                foreach($classes as $class){
                    $attend['classes'].=$class['cid'].=', ';
                }
            }
            $this -> getView() -> assign("attend",$attend);
            $rs = $dbh->query("select * from teacher  where tid='{$attend['tid']}'");
            $teacher = $rs->fetch();
            $this -> getView() -> assign("teacher",$teacher);
        }
        else
        {
            echo "<script>window.location.assign(\"/index.php?c=index&a=listall\");</script>";exit;
        }


    }
    public function logoutAction(){
        $_SESSION=array();
        echo "<script>window.location.assign(\"/index.php?c=login\");</script>";
        exit;
    }
    public function changepasswordAction(){
        $userid = Yaf_Session::getInstance()->get("user");
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}'");
        $user = $rs->fetch();
        $this -> getView() -> assign("user",$user);
        return true;
    }
    public function dopasswordAction(){
        $userid = Yaf_Session::getInstance()->get("user");
        $req = $this -> getRequest();
        $old = md5($req -> getPost('oldpasswd'));
        $new = $req -> getPost('newpasswd');
        $again = $req -> getPost('againpasswd');
        echo $old.$new.$again;
        if($new != $again){
            echo "<script>alert('两次输入的密码不一样');window.history.back();</script>";
        }
        if(strlen($new)<6){
            echo "<script>alert('密码长度不可以小于6位');window.history.back();</script>";
        }
        $dbh = Yaf_Registry::get('_db');
        $rs = $dbh->query("select * from teacher where tid='{$userid}' and password='{$old}'");
        $user = $rs->fetch();
        if(user){
            $new = md5($new);
            $dbh -> exec("update teacher set password={$new} where tid='{$userid}'");
            $_SESSION=array();
            echo "<script>alert('密码修改成功,请重新登录');</script>";
            echo "<script>window.location.assign(\"/index.php?c=login\");</script>";exit;
        }
        else{
            echo "<script>alert('您输入的密码不正确');window.history.back();</script>";exit;
        }
    }
}