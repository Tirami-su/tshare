
#目录
[项目背景](##user-content-项目背景)
[我们的解决方案](###user-content-Tshare致力于为资源共享提供一个更好的解决方案)
[概要设计](##user-content-概要设计)
[详细设计](##user-content-详细设计)
[阶段计划](##user-content-阶段计划)

软件工程课程重点
1. 学习相关知识，技能，完成软件整个生命周期的工作（需求分析，架构设计，实现，测试，发布，维护），积累经验
2. 项目管理，合理分工，安排计划，团队合作，保证项目进度。利用迭代，敏捷开发策略，更好地完成项目

项目的选择要求:真实、可用的软件
架构要求：B/S

---
#Tshare校园资源共享平台
为学生提供有效，高效的分享资源的途径
主旨：立足校园，服务学生

##项目背景
有些资源对学习很有帮助但比较难获取，比如课件，大学学习中，课件比较重要，少数课程获取课件比较困难；往年试卷，对于复习很重要，但获取比较困难，尤其对于人脉少的学生
类似的还有课设要求，老师特点
等到学完也考完了一门课，收集的很多资料，自己制作的复习资料都没有用了，一年后，认识的学弟学妹问有没有复习资料，都删了，问课设要求，考试特点，都忘了

这些有价值、可复用的学习资源，在传播的过程中遇到两个问题
1. 人脉。资源的传播局限于个人的社交网络，有的新生想获取却找不到人，同时老生的资源进了回收站并永久清除
2. 时间。课程的资源一年被需要一次，实体资源保存一年没有问题，但有些资源一般是记忆形式的，容易遗忘，比如课程特点，课设要求，某个老师的评分偏好，近年考试题型（一般能获取的往年试卷都是10年前的）

资源共享协会对于资源共享做了一些努力，但比较有限，仅提供往年试卷，仅包含部分学科

###Tshare致力于为资源共享提供一个更好的解决方案
- 依托互联网，所有资源以电子版分享，方便、快捷、低成本
- 每一个学生甚至教师都可以免费注册账号，通过平台，所有用户彼此连接，分享和获取资源不受人脉限制
- 鼓励用户将非实体资源转化为回忆录（文档或语音），然后上传，解决时间问题

这里有几个关键的问题
1. 平台如何有持续的大量的资源流入
我们设计了一个有效的激励机制，鼓励用户上传资源：
xxx
xxx
xxx
2. 用户利用平台传播非法资源
我们设计一个内容审核机制，禁止上传任何形式的色情，盗版，反动资源，禁止发表低俗，反动言论
3. 上传者拥有的资源可能存在大量的重复
我们设计一个内容查重机制，重复内容只保留一份

##概要设计
网站首先要有注册登录模块，上传/下载文件模块，老生主动上传的资源未必能满足新生的所有需求，新生可能有别的需求，为此，增加问答模块做补充
####拓展功能
为充分发挥互联网平台的优势，我们在不改变立足校园，服务学生的主旨的条件下，增加几个功能，同时这些功能可以提高网站的影响力和用户黏性
- 闲置交易
	买卖二手书、乐器、生活用品、会员卡、虚拟商品（比如会员账号）
- 失物招领
- 表白墙
	qq表白墙以说说的形式发布内容，各种内容混合在一起，且极易被后发的内容淹没，宣传效果有限
- 宣传
	为学生会，社团提供一个宣传活动的途径
- 商业推广
	为学生创业项目（比如小蜗系列）提供商业推广渠道
	为其他商家提供广告位

##详细设计
####用户管理和个人中心
注册，实名认证
登录，退出登录
找回密码
编辑昵称，头像及其他个人资料
消息通知
####网站首页
登录前首页：功能宣传
登录后首页：综合搜索，标签云
####资料分享
上传，搜索，下载资料
征求资料
####问答
搜索问题
提问，回答，评论，回复
####闲置交易
上架，下架
在售，已售标签
联系卖家
####失物招领
发布，删除
联系拾取者
####表白墙
发布，删除
联系表白者
####宣传和商业推广
美观不影响网站浏览体验的广告位
##阶段计划
####第2周：开发前准备
前后端程序员学习相关技术，熟悉Git
设计师设计网站商标，和界面初稿，至少包括注册登录，上传搜索下载功能
####第3-5周：第一阶段
完成用户管理，资料，问答，个人中心，网站首页
不必完成资料的查重
不必完成资料和发言的内容审核
####第6-8周：第二阶段
完成查重和审核
完成闲置交易，失物招领，表白墙
####第9-11周：第三阶段
补充细节