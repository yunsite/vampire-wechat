******************************************************************************
*                             游戏功能：                                     *
******************************************************************************

实现多人捉鬼游戏
智能取词、智能分配人数
根据玩家的身份和存活状态显示场上形势
裁判踢人出局、结束游戏

******************************************************************************
*                             更新记录：                                     *
******************************************************************************

版本1.0：
	实现捉鬼游戏的核心功能和代码架构
------------------------------------------------------------------------------
版本1.0.1：
	玩家角色分配以后裁判可以查看到所有人词汇
	玩家命令参数错误给出关于命令的快速帮助
------------------------------------------------------------------------------
版本1.1：
	增加游戏介绍和游戏规则介绍
	非同局游戏玩家无法查看游戏情况
	
	第一次大版本代码重构
	分离页面提示到单独文件
	重构游戏的入口
	分离输入命令的识别逻辑
	分离开始游戏的判断逻辑
------------------------------------------------------------------------------
版本1.2：
	游戏房间号加密
	所有玩家能看到场上玩家信息（词汇和存货状态），并且不同身份和存活状态显示信息不同
	裁判杀人
	
	优化游戏逻辑和页面细节
------------------------------------------------------------------------------
版本1.3：
	快速开始游戏：自动选词、自动分配人数
	快速杀人：通过输入玩家的序号杀人
------------------------------------------------------------------------------
版本2.0：
	丰富游戏提示信息
	
	增加Debug模式
------------------------------------------------------------------------------
版本2.0.1:
	
	重构代码框架，实现快速接入新的公共帐号
	优化程序debug模式，更加智能
------------------------------------------------------------------------------
版本2.0.2:
	
	重构代码框架，实现快速接入新的公共帐号
	优化程序debug模式，更加智能
------------------------------------------------------------------------------
版本3.0:
	
	平台迁移，平稳迁移到SAE

******************************************************************************
*                             开发团队                                     *
******************************************************************************
创意&产品：湄雅&相由心生  
开发&维护：子勰（bihe0832@foxmail.com 402998643）