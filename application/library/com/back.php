<?php
class com_back{
    function back(){
        $host = "localhost";
        $user = "root"; //数据库账号
        $password = "root"; //数据库密码
        $dbname = "lecture"; //数据库名称
// 这里的账号、密码、名称都是从页面传过来的
        if (!mysql_connect($host, $user, $password)) // 连接mysql数据库
        {
            return '数据库连接失败，请核对后再试';
        }
        if (!mysql_select_db($dbname)) // 是否存在该数据库
        {
            return  '不存在数据库:' . $dbname . ',请核对后再试';
        }
        mysql_query("set names 'utf8'");
        $mysql = "set charset utf8;\r\n";
        $q1 = mysql_query("show tables");
        while ($t = mysql_fetch_array($q1))
        {
            $table = $t[0];
            $q2 = mysql_query("show create table `$table`");
            $sql = mysql_fetch_array($q2);
            $mysql .= $sql['Create Table'] . ";\r\n";
            $q3 = mysql_query("select * from `$table`");
            while ($data = mysql_fetch_assoc($q3))
            {
                $keys = array_keys($data);
                $keys = array_map('addslashes', $keys);
                $keys = join('`,`', $keys);
                $keys = "`" . $keys . "`";
                $vals = array_values($data);
                $vals = array_map('addslashes', $vals);
                $vals = join("','", $vals);
                $vals = "'" . $vals . "'";
                $mysql .= "insert into `$table`($keys) values($vals);\r\n";
            }
        }
        $filename = './back/听课数据备份' . date('Y-m-d-G:i:s') . ".sql"; //存放路径，默认存放到项目最外层
        $fp = fopen($filename, 'w');
        fputs($fp, $mysql);
        fclose($fp);
        return "数据备份成功";
    }
}