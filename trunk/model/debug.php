<?php
function traceHttp(){
	logger("Wechat Server","REMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].(
			strpos($_SERVER["REMOTE_ADDR"],"157.97")?" From Wechat" : " Unknow IP"));
	logger("Wechat Commond","QUERY_STRING:".$_SERVER["QUERY_STRING"]);
}

function logger($title,$content){
	if($title){
        	
		file_put_contents(dirname(__FILE__).'/../log/log.html', "[".date('Y-m-d H:i:s ')."] [".$title."] ".$content."<BR>", FILE_APPEND);
	}else{
		file_put_contents(dirname(__FILE__).'/../log/log.html', "[".date('Y-m-d H:i:s ')."] ".$content."<BR>", FILE_APPEND);
	}
}
function debug($file,$line,$type,$content){
	file_put_contents(dirname(__FILE__).'/../log/default.log', "[".date('Y-m-d H:i:s ')."] [$type] [$file:$line] $content",FILE_APPEND);
}
?>