<?php
class SearchController extends Yaf_Controller_Abstract
{

    public function init()
    {

    }
    public function browseAction(){


        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $attendid = $_GET['aid'];
        if($attendid) {
            $rs = $dbh->query("select * from attend  where id='{$attendid}'");
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
            echo "<script>window.location.assign(\"/index.php?a=main\");</script>";exit;
        }
    }
    public function  majorlistAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);

        $rs = $dbh->query("select * from major");
        $majors = $rs->fetchAll();
        $i=0;
        foreach($majors as $major){
            $rs = $dbh->query("select * from class where mid='{$major['id']}'");

            $classes = $rs -> fetchAll();
            $major['class']='';
            $major['num'] =0;
            foreach($classes as $class){
                $major['class'].=$class['cid'].=',';
                $major['num']+=$class['count'];
            }
            $majors[$i++]=$major;
        }
        $this -> getView() -> assign("majors",$majors);
        return true;
    }
    public function  majorbrowseAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);

        $mid = $_GET['mid'];
        if($mid) {
            $rs = $dbh->query("select aid from ctoa inner join class inner join major where major.id=class.mid and class.cid=ctoa.cid and major.id={$mid} group by aid");
            $attendids = $rs->fetchAll();
            $attends=array();
            foreach($attendids as $attendid){
                $rs = $dbh->query("select * from attend where id={$attendid['aid']} and status=1");
                $attend = $rs->fetch();
                if($attend){

                    $rs = $dbh->query("select * from teacher where tid='{$attend['tid']}'");
                    $teacher = $rs->fetch();
                    $attend['tname'] = $teacher['username'];

                    $rs = $dbh->query("select * from teacher where tid='{$attend['aid']}'");
                    $teacher = $rs->fetch();
                    $attend['aname'] = $teacher['username'];

                    $attends[]=$attend;
                }
            }


            $this -> getView() -> assign("attends",$attends);
        }
        else
        {
            echo "<script>window.location.assign(\"/index.php\");</script>";exit;
        }

        return true;
    }
    public function  gradelistAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);

        $rs = $dbh->query("select year from class group by year");
        $grades = $rs->fetchAll();
        $i=0;
        foreach($grades as $grade){
            $rs = $dbh->query("select * from class where year='{$grade['year']}'");

            $classes = $rs -> fetchAll();
            $grade['class']='';
            $grade['num'] =0;
            foreach($classes as $class){
                $grade['class'].=$class['cid'].=',';
                $grade['num']+=$class['count'];
            }
            $grades[$i++]=$grade;
        }
        $this -> getView() -> assign("grades",$grades);
        return true;
    }
    public function  gradebrowseAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);
        $gid = $_GET['gid'];
        if($gid) {$rs = $dbh->query("select aid from ctoa inner join class where class.cid=ctoa.cid and class.year='{$gid}' group by aid");
            $attendids = $rs->fetchAll();
            foreach($attendids as $attendid){
                $rs = $dbh->query("select * from attend where id={$attendid['aid']} and status=1");
                $attend = $rs->fetch();
                if($attend){

                    $rs = $dbh->query("select * from teacher where tid='{$attend['tid']}'");
                    $teacher = $rs->fetch();
                    $attend['tname'] = $teacher['username'];

                    $rs = $dbh->query("select * from teacher where tid='{$attend['aid']}'");
                    $teacher = $rs->fetch();
                    $attend['aname'] = $teacher['username'];

                    $attends[]=$attend;
                }
            }


            $this -> getView() -> assign("attends",$attends);
        }
        else
        {
            echo "<script>window.location.assign(\"/index.php\");</script>";exit;
        }

        return true;
    }
    public function  classlistAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);


        $rs = $dbh->query("select cid,year,majorname,count,pteacher.username as pname,head.username as hname from class inner join major inner join teacher as pteacher inner join teacher as head where major.id=class.mid and class.pid=pteacher.tid and head.tid=class.hid");
        $classes = $rs->fetchAll();
        $this -> getView() -> assign("classes",$classes);
        return true;
    }
    public function  classbrowseAction()
    {
        $dbh = Yaf_Registry::get('_db');
        $user = Yaf_Registry::get('user');
        $pclasses = Yaf_Registry::get('pclass');
        $hclass = Yaf_Registry::get('hclass');
        $this->getView()->assign("user", $user);
        $this->getView()->assign("pclasses", $pclasses);
        $this->getView()->assign("hclass", $hclass);


        $cid = $_GET['cid'];
        if($cid) {
            $rs=$dbh->query("select id,time,classname,classroom,tid,attend.aid from attend inner join ctoa where attend.id=ctoa.aid and ctoa.cid='{$cid}' and attend.status=1");
            $attends = $rs->fetchAll();

            $i=0;
            foreach($attends as $attend){

                $rs = $dbh->query("select * from teacher where tid='{$attend['tid']}'");
                $teacher = $rs->fetch();
                $attend['tname'] = $teacher['username'];
                $rs = $dbh->query("select * from teacher where tid='{$attend['aid']}'");
                $teacher = $rs->fetch();
                $attend['aname'] = $teacher['username'];

                $attends[$i]=$attend;
                $i++;
            }

            $this -> getView() -> assign("attends",$attends);
        }
        else
        {
            echo "<script>window.location.assign(\"/index.php\");</script>";exit;
        }


        return true;
    }

}