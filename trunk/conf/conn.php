<?php
//捉鬼程序数据库链接等配置
$vampire_config = array();
//local
/*
$vampire_config['db']['host'] = '127.0.0.1';
$vampire_config['db']['user'] = 'user';
$vampire_config['db']['passwd'] = '';
$vampire_config['db']['dbname'] = 'db_wechat';
*/
//SAE

$vampire_config['db']['host'] = SAE_MYSQL_HOST_M;
$vampire_config['db']['port'] = SAE_MYSQL_PORT;
$vampire_config['db']['user'] = SAE_MYSQL_USER;
$vampire_config['db']['passwd'] = SAE_MYSQL_PASS;
$vampire_config['db']['dbname'] = SAE_MYSQL_DB;

?>