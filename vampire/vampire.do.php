<?php
ini_set('display_errors', false);
require_once (dirname(__FILE__).'/../conf/conn.php');
require_once (dirname(__FILE__).'/../model/debug.php');
require_once (dirname(__FILE__).'/vampire.tips.php');

class vampire{
	private $debug = false;
	//加密后的游戏ID
	private $gameID=0;
	private $secretGameID=0;
	//裁判的微信ID
	private $judge = '';
	//游戏总人数
	private $totalNum = 0;
	//游戏已经获取角色的人数
	private $finished = 0;
	//游戏关键字，先人后鬼
	private $words =array();
	//所有人角色的随即序列
	private $roleSquence = '';
	//所有人的角色的信息
	private $roleInfo = Array();
	//游戏状态
	private $gameState = 0;
	//游戏状态
	private $roleNum = Array(
		4=>Array(3,1),
		5=>Array(4,1),
		6=>Array(4,2),
		7=>Array(5,2),
		8=>Array(6,2),
		9=>Array(6,3),
		10=>Array(7,3),
		11=>Array(8,3),
		12=>Array(8,4),
		13=>Array(9,4),
		14=>Array(10,4),
		15=>Array(10,5),
		16=>Array(11,5),
		17=>Array(12,5),
		18=>Array(13,5),
		19=>Array(14,5),
		20=>Array(15,5)
	);
	//游戏中的命令
	private $commandKey = Array(
		"name"=>Array("1","NICHENG","NICHEN","NC","NAME","N"),
		"start"=>Array("2","KAISHI","KS","START","S"),
		"kill"=>Array("3","SHAREN","SR","KILL","K"),
		"over"=>Array("4","JIESHU","JS","OVER","O"),
		"help"=>Array("BANGZHU","BZ","HELP","H"),//10
		"morehelp"=>Array("GDBZ","GENGDUOBANGZHU","MORE","M"),//11
		"introduction"=>Array("YXZS","YOUXIJIESHAO","INTRODUCTION","I"),//12
		"gamerule"=>Array("SFGZ","SHENGFUGUIZE","GAMERULE","G"),//13
		"liverule"=>Array("CHGZ","CUNHUOGUIZE","LIVERULE","L"),//14
		"rolenum"=>Array("TJRS","TUIJIANRENSHU","ROLENUM","R"),//15
	);
	
	public function __construct($debug = false)	{
		$this->debug = $debug;
		//获取所有中文tips
		$this->tips = $GLOBALS['vampire_tips'];
		if($this->debug)logger("Vampire Tips",$this->tips['system']['ok']);
		
		$this->_dbConnect($GLOBALS['vampire_config']);
		if($this->debug)logger("Vampire Data", "DB is OK!");
	}
	
	public function startApp($userInput){
		if($this->debug)logger("Vampire Start",$userInput);
		//解析玩家的命令
		$userInputInfo =  json_decode($userInput, true);
		//获取到玩家的微信ID
		$this->userID = $userInputInfo["fromUsername"][0];
		//获取玩家请求的命令
		$userCommand = explode(" ",$userInputInfo["keyword"]);
		$commandID = $this->_checkCommand($userCommand);
		switch($commandID){
			case 0:
				//玩家重获取角色
				$tips = $this->_getRole($this->userID);
				break;
			case 1:
				//玩家重命名
				$nickName = $userCommand[1];
				$tips = $this->_setNickName($this->userID,$nickName);
				var_dump($tips);
				break;
			case 21:
				//开始游戏(自己出词)
				$numOne=intval($userCommand[1]);
				$numTwo=intval($userCommand[3]);
				$this->words[0] = $userCommand[2];
				$this->words[1] = $userCommand[4];
				$this->_addRandWords($this->words);
				if($numOne>$numTwo){
					$people = $numOne;
					$vampire = $numTwo;
				}else{
					$people = $numTwo;
					$vampire = $numOne;
				}
				$tips = $this->_startGame($this->userID,$people,$vampire,$this->words);
				break;
			case 22:
				//开始游戏(系统出词)
				$numOne=intval($userCommand[1]);
				$numTwo=intval($userCommand[2]);
				$this->words = $this->_getRandWords();
				if($numOne>$numTwo){
					$people = $numOne;
					$vampire = $numTwo;
				}else{
					$people = $numTwo;
					$vampire = $numOne;
				}
				$tips = $this->_startGame($this->userID,$people,$vampire,$this->words);
				break;
			case 23:
					//开始游戏(系统出词,确定人数)
					$totalNum =intval($userCommand[0]);
					if($totalNum < 5 || $totalNum > 20){
						$tips =$this->tips['game']['badPlayerNum'];
					}else{
						$this->words = $this->_getRandWords();
						$people = $this->roleNum[$totalNum][0];
						$vampire = $this->roleNum[$totalNum][1];
						$tips = $this->_startGame($this->userID,$people,$vampire,$this->words);
					}
					break;
			case 31:
				//杀人
				$finishedPlayer = $userCommand[2];
				$tips = $this->_killPlayer($this->userID,$finishedPlayer);
				break;
			case 32:
				//快速杀人
				$finishedPlayer = $userCommand[1];
				$tips = $this->_killPlayer($this->userID,$finishedPlayer);
				break;
			case 4:
				//结束游戏
				$tips = $this->_overGame($this->userID);
				break;
			case 10:
				//通用帮助
				$tips = $this->_getHelp($this->userID);
				break;
			default:
				//彩蛋帮助
				$tips = $this->_getSpecialHelp($commandID);
				break;
		}
		//echo $tips;
		if($this->debug)logger("Vampire Finished", $tips);
		return $tips;
	}
	
	//检测玩家输入的命令是否和合法
	public function _checkCommand($userCommand){
		$command = $userCommand[0];
		if($this->debug)logger("Vampire command",$command);
		if($command > 4 && $command < 21){
			return 23;//通过直接输入参与人数开始游戏
		}else if($command > 20){
			$id = $this->_parseConfig($command);
			if($id == 0){
				if(sizeof($userCommand) == 2){
					return 32;//快速杀人
				}else{
					return 0;//输入游戏ID获取角色或者查询信息
				}
			}else{
				return -2;//数据库木有找到本局游戏数据
			}
		}else if(in_array(strtoupper($command), $this->commandKey["name"]) ||$command == "昵称"){
			if(sizeof($userCommand) != 2 || $userCommand[1] == ""){
				return -1;
			}else{
				return 1;
			}
		}else if(in_array(strtoupper($command), $this->commandKey["start"]) || $command == "开始"){
			if((sizeof($userCommand) != 5 && sizeof($userCommand) != 3) ||$userCommand[1] == ""||$userCommand[2] == ""){
				return -1;
			}else{
				if(sizeof($userCommand) == 5){
					if($userCommand[3] == "" || $userCommand[4] == ""){
						return -1;
					}else{
						return 21;//输入详细的人鬼数目和词汇开始游戏
					}
				}else if(sizeof($userCommand) == 3){
					return 22;//输入详细的人鬼数目开始游戏
				}else{
					return -1;
				}
			}
			
		}else if(in_array(strtoupper($command), $this->commandKey["kill"]) || $command == "杀人"){
			if(sizeof($userCommand) != 3 || $userCommand[1] == "" || $userCommand[2] == ""){
				return -1;
			}else{
				$id = $this->_parseConfig($userCommand[1]);
				if($id == 0){
					return 31;//输入详细的信息踢玩家出局
				}else{
					return -2;//数据库木有找到本局游戏数据
				}
			}
		}else if(in_array(strtoupper($command), $this->commandKey["over"]) || $command == "结束"){
			if(sizeof($userCommand) != 2 || $userCommand[1] == ""){
				return -1;
			}else{
				$id = $this->_parseConfig($userCommand[1]);
				if($id == 0){
					return 4;
				}else{
					return -2;//数据库木有找到本局游戏数据
				}
			}
		}else if(in_array(strtoupper($command), $this->commandKey["help"]) || $command == "帮助" || $command == "？" || $command == "?"){
			return 10;
		}else if(in_array(strtoupper($command), $this->commandKey["introduction"]) || $command == "游戏介绍" || $command == "游戏简介" || $command == "介绍" || $command == "简介"){
			return 12;
		}else if(in_array(strtoupper($command), $this->commandKey["gamerule"]) || $command == "胜负规则" || $command == "胜负" || $command == "淘汰"){
			return 13;
		}else if(in_array(strtoupper($command), $this->commandKey["liverule"]) || $command == "存活规则" || $command == "存活"){
			return 14;
		}else if(in_array(strtoupper($command), $this->commandKey["rolenum"]) || $command == "推荐人数" || $command == "人数"){
			return 15;
		}else if(in_array(strtoupper($command), $this->commandKey["morehelp"]) || $command == "更多帮助" || $command == "更多" || $command == "？？" || $command == "??"){
			return 11;
		}else{
			return 10;
		}
	}
	//开始一局新的游戏，返回加密后的游戏ID
	public function _startGame($judge,$people,$vampire,$words){
		$totalNum = $people + $vampire;
		$roleSquence = $this->_getRandRoleSquence($people, $vampire);
		$sql = "insert into `t_vampire`(`judge`,`totalNum`,`keyWords`,`roleSquence`,`gameState`) values ('".
					$judge."',".$totalNum.",'".$words[0]." ".$words[1]."','".$roleSquence."',0);";
		$result=mysql_query($sql);
		$gameID = mysql_insert_id();
		if($gameID >　0){
			$this->gameID = $gameID;
			$this->secretGameID = $this->_encryptGameID($this->gameID);
			//加密
			$tips = "";
			if($totalNum > 14){
				$tips .= $this->tips['game']['muchPlayer'];
			}
			$tips .= str_replace("SB", $this->secretGameID, $this->tips['game']['start']);
			$tips = str_replace("WORD1", $this->words[0] , $tips);
			$tips = str_replace("WORD2", $this->words[1] , $tips);
			return $tips;
		}else{
			return $this->tips['system']['nodata'];
		}
	}
	
	//获取指定数目的人鬼随机序列
	public function _getRandRoleSquence($people,$vampire){
		$peopleArray = array_fill(0,$people,0);
		$vampireArray = array_fill(0,$vampire,1);
		$totalArray = array_merge($peopleArray,$vampireArray);
		shuffle($totalArray);
		$tempRoleSquence = "";
		foreach($totalArray as $v){
			$tempRoleSquence .=$v;
		}
		return $tempRoleSquence;
	}
	
	//结束游戏
	public function _overGame($userID){
		$sql = "SELECT `judge`,`gameState`,`totalNum`,`finished` FROM `t_vampire` WHERE `id` = ".$this->gameID.";";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		if($userID == $result["judge"]){
			$sql = "UPDATE `t_vampire` SET `gameState`= 2 WHERE `id` = ".$this->gameID.";";
			$result = mysql_query($sql);
			return str_replace("SB",$this->secretGameID, $this->tips['gameover']['tips']);
		}else{
			return $this->tips['gameover']['notJudege'];
		}
	}
	
	//将获得的游戏ID加密
	public function _encryptGameID($gameID){
		$key = rand(1,9);
		return $key.($gameID*3-$key);
	}
	//将玩家输入的ID解密为游戏ID
	public function _decryptGameID($gameID){
		$key = substr($gameID,0,1);
		return (substr($gameID,1) + $key) / 3;
	}
	//游戏中给玩家命名
	public function _setNickName($userID,$nickName){
		$sql = "SELECT `weChatName`, COUNT(`weChatName`) AS num FROM `t_vampireUser` WHERE `nickName` = '".$nickName."';";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		if($result["num"] > 0){
			if($result["weChatName"] == $userID){
				return str_replace("SB", $nickName, $this->tips['name']['finished']).$this->tips['command']['help'];
			}else{
				return str_replace("SB", $nickName, $this->tips['name']['used']);
			}
		}else{
			if($this->_getNickName($userID) == ""){
				$sql = "INSERT INTO `t_vampireUser` (`weChatName`,`nickName`) VALUES ('".$userID."','".$nickName."');";
				$result = mysql_query($sql);
			}else{
				$sql = "UPDATE `t_vampireUser` SET `nickName` = '".$nickName."' WHERE `weChatName` = '".$userID."';";
				$result = mysql_query($sql);
			}
			if($result){
				return str_replace("SB", $nickName, $this->tips['name']['finished']).$this->tips['command']['help'];
			}else{
				return $this->tips['name']['failed'];
			}
		}
	}
	//获取玩家在游戏中的昵称
	public function _getNickName($userID){
		$sql = "SELECT `nickName` FROM `t_vampireUser` WHERE `weChatName` = '".$userID."';";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		if($result){
			return $result["nickName"];
		}else{
			return "";
		}
	}
	//根据玩家在游戏中的昵称获取玩家微信ID
	public function _getUserID($nickName){
		$sql = "SELECT `weChatName` FROM `t_vampireUser` WHERE `nickName` = '".$nickName."';";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		if($result){
			return $result["weChatName"];
		}else{
			return "";
		}
	}
	//杀人
	public function _killPlayer($userID,$finishedPlayer){
		if($userID == $this->judge){
			if(intval($finishedPlayer)){
				$num = 0;
				$roleNum = intval($finishedPlayer)-1;
				foreach($this->roleInfo as $k=>$v ){
					if($num == $roleNum){
						$this->roleInfo[$k][state] = 1;
					}
					$num++;
				}
			}else{
				$killedPlayer = $this->_getUserID($finishedPlayer);
				if(in_array($killedPlayer, array_keys($this->roleInfo))){
					$this->roleInfo[$killedPlayer][state] = 1;
				}else{
					return $this->tips['game']['noPlayer'].$this->_getAllInfo($this->judge);
				}
			}
			$tempRoleInfo=str_replace("\\", "\\\\", json_encode($this->roleInfo));
			$sql = "UPDATE `t_vampire` SET `roleInfo`='".$tempRoleInfo."' WHERE `id` =".$this->gameID.";";
			$result = mysql_query($sql);
			return $this->_getAllInfo($this->judge);
		}else{
			return $this->tips['game']['notJudege'];
		}
	}
	//获取信息（玩家获取词条和场上的存活信息，裁判获取所有信息）
	public function _getRole($userID){
		if($this->gameState > 0){
			if($userID == $this->judge || in_array($userID, array_keys($this->roleInfo))){
				return $this->_GetAllInfo($userID);
			}else{
				return str_replace("SB",$this->secretGameID, $this->tips['game']['roleFinished']);
			}
			
		}else{
			if($userID == $this->judge || in_array($userID, array_keys($this->roleInfo))){
				return $this->_GetAllInfo($userID);
			}else{
				if($this->_getNickName($userID) == ""){
					return $this->tips['name']['noName'];
				}else{
					if($this->totalNum > $this->finished){
						return $this->_getRoleWord($userID);
					}else if($this->totalNum == $this->finished){
						$sql = "UPDATE `t_vampire` SET `gameState`=1 WHERE `id` = ".$this->gameID.";";
						$result = mysql_query($sql);
						return $this->_GetAllInfo($userID);
					}else{
						return str_replace("SB",$this->secretGameID,$this->tips['game']['roleFinished']);
					}
				}
			}
		}
	}
	//获取词条（玩家获取词条）
	public function _getRoleWord($userID){
		if(!in_array($userID, array_keys($this->roleInfo))){
			$roleWord = $this->words[$this->roleSquence[$this->finished]];
			$role=Array();
			$role[$userID]["word"] = $roleWord;
			$role[$userID]["state"] = 0;
			if($this->roleInfo != ""){
				$this->roleInfo = array_merge($role,$this->roleInfo);
				$tempRoleInfo=str_replace("\\", "\\\\", json_encode($this->roleInfo));
				$sql = "UPDATE `t_vampire` SET `finished`=`finished`+1,`roleInfo`='".$tempRoleInfo."' WHERE `id` =".$this->gameID.";";
				$result = mysql_query($sql);
				return str_replace("SB",$roleWord,$this->tips['game']['getword']);
			}
		}else{
			return $this->_getAllInfo($userID);
		}
	}
	
	//将玩家的词汇添加到游戏词库（扩展）
	public function _addRandWords($word){
		$tempWord = $word[0]." ". $word[1];
		$sql = "SELECT word FROM `t_vampireWords` where word  = $tempWord;";
		$result = mysql_query($sql);
		if(!$result){
			$sql = "INSERT INTO `t_vampireWords` (`word`) VALUES ('".$tempWord."');";
			$result = mysql_query($sql);
		}		
	}
	
	//随机从游戏词库取词（扩展）
	public function _getRandWords(){
		//获取关键字的最大值编号
		$sql = "SELECT MAX(`id`) AS maxId  FROM `t_vampireWords`;";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		//获取随机数
		$wordId = rand(1,$result["maxId"]);
		//获取关键字
		$sql = "SELECT word  FROM `t_vampireWords` where id  = $wordId;";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		$word = explode(" ",$result["word"]);
		return $word;
	}
	//得到场上所有人的信息
	public function _getAllInfo($userID){
		$tips = "游戏".$this->secretGameID."有".$this->finished."人确定角色：\n 裁判：".$this->_getNickName($this->judge)."\n";
		$stateDesc =Array("O","X");
		$num = 1;
		foreach ($this->roleInfo as $id=>$info){
			$tempRole = $num.".".$this->_getNickName($id)."：";
			if($this->roleInfo[$userID]["state"] == 1 || $this->gameState == 2 || $userID == $this->judge || $userID == $id){
				$tempRole .=$info["word"];
			}else{
				$tempRole .="****";
			}
			$tempRole .="（".$stateDesc[$info["state"]]."）\n";
			$num++;
			$tips .= $tempRole;
		}
		return $tips;
	}
	//帮助主页（没有昵称先要起名）
	public function _getHelp($userID){
		if($this->_getNickName($userID) == ""){
			return $this->tips['name']['noName'];
		}else{
			return $this->tips['command']['help'];
		}
	}
	//帮助分条记录
	public function _getSpecialHelp($id){
		switch ($id){
			case -2:
				//常用命令
				return $this->tips["game"]["nogame"];
				break;
			case -1:
				//常用命令
				return $this->tips["help"]["orderList"];
				break;
			case 12:
				//游戏简介
				return $this->tips["help"]["introduce"];
				break;
			case 13:
				//胜负规则
				return $this->tips["help"]["gamerule"];
				break;
			case 14:
				//存活规则
				return $this->tips["help"]["liverule"];
				break;
			case 15:
				//存活规则
				return $this->tips["help"]["roleNum"];
				break;
			case 10:
				//帮助列表
				return $this->tips["help"]["list"];
				break;
			default:
				//帮助列表
				return $this->tips["help"]["list"];
				break;
		}
	}
	//初始化所有的配置信息,开始、帮助等均不需要
	public function _parseConfig($gameID){
		//先解密游戏ID
		$this->secretGameID = $gameID;
		$this->gameID = $this->_decryptGameID($this->secretGameID);
		$sql = "SELECT `judge`,`totalNum`,`finished`,`keyWords`,`roleSquence`,`roleInfo`,`gameState` FROM `t_vampire` WHERE `id` = ".$this->gameID.";";
		$result = mysql_query($sql);
		$result = mysql_fetch_assoc($result);
		if($result){
			$this->judge =  $result["judge"];
			$this->totalNum = $result["totalNum"];
			$this->finished = $result["finished"];
			$this->words = explode(" ",$result["keyWords"]);
			$this->roleSquence = $result["roleSquence"];
			if($result["roleInfo"] != ""){
				$this->roleInfo = json_decode($result["roleInfo"], true);
			}
			$this->gameState =  $result["gameState"];
			return 0;
		}else{
			return -2;
		}
	}
	
	public function _dbConnect($vampire_config){
		if($vampire_config['db']['port']){
			$link = mysql_connect($vampire_config['db']['host'].":".$vampire_config['db']['port'], $vampire_config['db']['user'], $vampire_config['db']['passwd']) or die("数据库连接失败: " . mysql_error());
		}else{
			$link = mysql_connect($vampire_config['db']['host'], $vampire_config['db']['user'], $vampire_config['db']['passwd']) or die("数据库连接失败: " . mysql_error());
		}
		mysql_select_db($vampire_config['db']['dbname'], $link);
		mysql_query("set names utf8");
	}
}
?>