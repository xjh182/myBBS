<?php
session_start();
header("Content-type:text/html;charset=utf-8");
//定义常量
define('DB_HOST','127.0.0.1');
define('DB_USER','xjhbbs');
define('DB_PASSWORD','xjhbbs');
define('DB_DATABASE','bbs');
define('DB_PORT',3306);
//程序在服务器上的绝对路径
define('SA_PATH',dirname(dirname(__FILE__)));
define('SUB_URL',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',SA_PATH)).'/');
?>