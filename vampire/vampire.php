<?php
ini_set('display_errors', false);

require_once (dirname(__FILE__).'/../model/debug.php');
require_once (dirname(__FILE__).'/../model/wechatApi.php');
include (dirname(__FILE__).'/vampire.do.php');

define("TOKEN", "zixie");
define("DEBUG", false);

if(DEBUG)traceHttp();
$wechatObj = new wechatCallbackApi(TOKEN);
//$wechatObj->valid();
$userInput = $wechatObj->getUserInput();
if(DEBUG)logger("Wechat UserInPut",$userInput);

$vampireObj = new vampire(DEBUG);
$resultMsg = $vampireObj->startApp($userInput);
if(DEBUG)logger("Wechat ResponseMsg",$resultMsg);

$wechatObj->responseMsg($resultMsg);
exit;
?>