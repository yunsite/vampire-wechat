<?php
ini_set('display_errors', false);

class wechatCallbackApi
{
	private $token = 'wechat';
	
	public function __construct($token = 'wechat')	{
		$this->token = $token;
	}
	public function valid()    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        }
    }
    public function responseMsg($str)
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
           $msgType = "text";
           $contentStr = $str;
           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
           echo $resultStr;
           exit;
        }else {
        	echo "";
        	exit;
        }
    }
		
    public function getUserInput()
    {
    	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    	if (!empty($postStr)){
    		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    		$userInput = array();
    		$userInput["fromUsername"] = $postObj->FromUserName;
    		$userInput["keyword"] = trim($postObj->Content);
			return json_encode($userInput);
    	}else {
    		echo "";
    		exit;
    	}
    }
    
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return true;
		}
	}
}
?>