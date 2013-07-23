<?php
$vampire_tips = array();
$vampire_tips['system']['ok'] ="Tips is OK！";
$vampire_tips['system']['nodata'] ="没有获取到游戏数据！";
$vampire_tips['command']['help'] ="1.开始游戏。裁判回复如\n“10”开始一局10人参与的捉鬼游戏。系统返回房间号。\n".
								 "2.参与游戏。玩家(含裁判)回复房间号参与游戏。\n".
								 "3.踢人出局。裁判回复如\n“183 2” 将序号为2的玩家从183房游戏踢出局。\n".
								 "4.公布结果。裁判回复如\n“over 183” 结束183房间游戏，所有人可回复房间号查看信息。\n".
								 "--更多帮助，回复“??”\n";
$vampire_tips['help']['orderList'] = "常用游戏命令：\n".
									"1.开始游戏：\n“10”"." 或 “s 7 3”"." 或\n“s 7 长江 3 黄河”\n".
									"2.踢人出局：\n“k 183 test”"."或“183 2”\n".
									"3.结束游戏：\n“o 183”\n";
$vampire_tips['help']['introduce'] = "《捉鬼》是一个比拼语言表述能力、知识面与想象力的游戏。每局游戏中有两个不同".
									"的词汇和身份（人和鬼）。游戏中除裁判之外每人将获得一个词汇，所有人不知道其余玩家身份".
									"和词汇内容，玩家只能通过语言描述自己的词汇寻找自己的同伴并分析找出敌人，".
									"阐述过程中出现该词汇中的字词则被判出局。每轮阐述结束需要有一人被投票出局。\n".
									"详细胜负规则请回复：“SFGZ 或  13”查看\n".
									"推荐人数规则请回复：“TJRS 或  15”查看\n";
$vampire_tips['help']['gamerule'] = "1. 当鬼全部被踢出局，人赢，游戏结束。\n".
							  	   "2. 当鬼正确分析出人的词汇，鬼赢，游戏结束。\n".
							  	   "3. 当人的存活数量少于鬼的存活数量，鬼赢，游戏结束。\n".
								   "详细出局规则请回复：“CHGZ 或  14”查看\n";
$vampire_tips['help']['liverule'] =  "1. 人不能分析并说出鬼的词汇，即人不可跳鬼。\n".
									"2. 鬼如果分析后不能说出人正确的词汇，鬼出局。\n".
  								    "3. 当被投票数量超过半数，该角色死亡并出局。\n";
$vampire_tips['help']['roleNum'] =  "不同玩家数量的人和鬼的配置如下：\n".
							  	   "玩家数    “人”数     “鬼”数\n".
							  	   "     4           3            1\n".
							  	   "     5           4            1\n".
							  	   "     6           4            2\n".
							  	   "     7           5            2\n".
							  	   "     8           6            2\n".
							  	   "     9           6            3\n".
							  	   "     10         7            3\n".
							  	   "     11         8            3\n".
							  	   "     12         8            4\n".
							  	   "     13         9            4\n".
							  	   "     14         10          4\n".
							  	   "     15         10          5\n".
							  	   "     16         11          5\n".
							  	   "     17         12          5\n".
							  	   "     18         13          5\n".
							  	   "     19         14          5\n".
							  	   "     20         15          5\n";
$vampire_tips['help']['list'] ="帮助：\n".
								"--回复对应指令可以查看详细说明。\n".
								"--操作方法  ？或 10\n".
								"--游戏介绍  YXJS 或  12\n".
								"--胜负规则  SFGZ 或  13\n".
								"--存活规则  CHGZ 或  14\n".
								"--人数规则  TJRS 或  15\n";
$vampire_tips['game']['nogame'] =  "您要加入的房间不存在！";
$vampire_tips['game']['muchPlayer'] =  "为了更好的游戏体验，建议您一局游戏的参与人数不要超过20人！\n";
$vampire_tips['game']['badPlayerNum'] =  "当同一局游戏玩家低于5人或者超过20人需要裁判输入对应的人鬼数目！\n";
$vampire_tips['game']['start'] =  "本局游戏的房间号为： SB。\n人的词汇为：WORD1；\n鬼的词汇为：WORD2。";  														
$vampire_tips['game']['notIn'] =  "您并未参加本局游戏，无法查看游戏信息！";
$vampire_tips['game']['roleFinished'] = "游戏 SB 角色分配已经结束，请与裁判确认信息！";
$vampire_tips['game']['getword'] = "您的词语是：SB";
$vampire_tips['game']['noPlayer'] = "该玩家没有参加本局游戏！\n";
$vampire_tips['game']['notJudege'] = "你不是本局游戏的裁判，不能踢玩家出局！\n";

$vampire_tips['gameover']['tips'] = "游戏 SB 已经结束，所有玩家均可回复房间号查看角色信息！";
$vampire_tips['gameover']['notJudege'] = "你不是本局游戏的裁判，不能结束游戏！\n";

$vampire_tips['name']['noName'] = "为了在游戏中更好的识别角色，请先回复：“1 你的名字”准备开始惊险刺激之旅！";
$vampire_tips['name']['null'] = "请输入可以被大家认可的昵称哈（至少不能为空吧）！";
$vampire_tips['name']['used'] = "昵称 SB 被抢了哦，赶快想个更好听的吧……";
$vampire_tips['name']['finished'] = "SB ，欢迎加入吸血鬼大家庭。\n";
$vampire_tips['name']['failed'] = "改个名字也能出错了？再试试吧！";
?>