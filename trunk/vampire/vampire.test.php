<?php
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"start 3 长江 1 黄河"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"start 3 1"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"start 4"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjlGhYyzAPlzeGEbG_VFc6kw"},"keyword":"4404"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjlGhYyzAPlzeGEbG_VFc6kw"},"keyword":"1 12121"}';
// $userInput ='{"fromUsername":{"0":"o7P7pjhxVCAM-EgiUlAP__xcAZW0"},"keyword":"756"}';
// $userInput ='{"fromUsername":{"0":"o7P7pjquKuekQJ4XeHQusKBw30mg"},"keyword":"4404"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjsC6-roqsraTLnk_SVds-Nc"},"keyword":"4404"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjryi95dQGhfQEQ_Sm3GvI5o"},"keyword":"4404"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"4404 1"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"kill 756 matthew"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjlGhYyzAPlzeGEbG_VFc6kw"},"keyword":"756"}';
// $userInput ='{"fromUsername":{"0":"o7P7pjhxVCAM-EgiUlAP__xcAZW0"},"keyword":"756"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"kill 756 matthewliu"}';
// $userInput ='{"fromUsername":{"0":"o7P7pjquKuekQJ4XeHQusKBw30mg"},"keyword":"kill 756 bvnbnbn"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjryi95dQGhfQEQ_Sm3GvI5o"},"keyword":"kill 756 bvnbnbn"}';
// $userInput ='{"fromUsername":{"0":"o7P7pjquKuekQJ4XeHQusKBw30mg"},"keyword":"756"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjsC6-roqsraTLnk_SVds-Nc"},"keyword":"756"}';
// $userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"help"}';
$userInput ='{"fromUsername":{"0":"oZCvIjiK_hVkP4nGZwnoXs9d92ZQ"},"keyword":"5"}';

echo "<HR>DEBUG START!<BR>";
require_once (dirname(__FILE__).'/vampire.do.php');
$vampireObj = new vampire(true);
$resultMsg = $vampireObj->startApp($userInput);
echo $resultMsg;
echo "<BR>DEBUG FINISHED!<HR>";
?>