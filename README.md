
#目录
[项目背景](###user-content-项目背景)

[解决方案](###user-content-tshare致力于为学习资源共享提供一个更好的解决方案)

[概要设计](###user-content-概要设计)

[详细设计](###user-content-详细设计)

[阶段计划](###user-content-阶段计划)

[产品推广](###user-content-产品推广)

软件工程课程
1. 学习相关知识，技能，完成软件整个生命周期的工作（需求分析，架构设计，实现，测试，发布，维护），积累经验
2. 项目管理，合理分工，安排计划，团队合作，保证项目进度。利用迭代，敏捷开发策略，更好地完成项目

项目的选择要求:真实、可用的软件

架构要求：B/S

---
#Tshare校园资源共享平台
为学生提供高效的分享资源的途径

宗旨：立足校园，服务学生

###项目背景
有些资源对学习很有帮助但比较难获取。比如往年试卷可以帮助我们更有针对性、更高效地复习，但获取比较困难，尤其对于人脉少的学生。课件也是很重要的资源，学生中一种比较流行的学习方式是在打印版课件或电子版课件上面做笔记，但有些课的课件往往在结束后才能拷贝，如果能在开课前获取全套课件，可以为每节课记笔记做好准备，甚至可以在学期初了解本学期所有课程，规划好学习计划。

等到学完也考完了一门课，收集的很多资料，自己制作的复习资料都没有用了，一年后，认识的学弟学妹问有没有复习资料，都删了，问课设要求，考试特点，都忘了

课外学习也有类似的问题，比如雅、托福、计算机二级等各种考试，ACM、数学建模等各种比赛，还有工程项目/科研项目，从刚开始接触到取得一定的成果，每个人都会通过各种渠道收集资料并筛选，并且也会自己总结一些资料，等到结束，这些沉淀下来的、经过验证的资源都失去了价值，碰巧有认识的学弟学妹需要，就顺手赠送，而更多的萌新还在摸索，重复着相似的劳动

除了这些实体资源，还有无形的资源——经验，备考经验，比赛经验，保研考研经验，出国经验，找工作经验，时间长了就忘了

这些有价值、可复用的学习资源，在传播的过程中遇到两个问题
1. 人脉。资源的传播局限于个人的社交网络，有的新生想获取却找不到人，同时老生的资源进了回收站并永久清除
2. 时间。课程的资源一年被需要一次，实体资源保存一年没有问题，但无形资源容易遗忘

资源共享协会对于资源共享做了一些努力，但比较有限，仅提供往年试卷，仅包含部分学科

百川PT也做了资源共享网站，但注册麻烦，界面极丑，机制不友好，易被封号

百度等搜索引擎在我们收集资料过程中扮演着重要的角色，但是“万里黄河，泥沙俱下”，想要找到满意的资源还需要费许多时间

###Tshare致力于为资源共享提供一个更好的解决方案
- 依托互联网，所有资源以电子版分享，方便、快捷、低成本
- 每一个学生甚至教师都可以免费注册账号，通过平台，所有用户彼此连接，分享和获取资源不受人脉限制
- 平台把控资源质量，向用户提供有价值的资源
- 鼓励用户将非实体资源转化为文档或语音，然后上传，解决时间问题
- 网站界面美观，给用户良好的使用体验
- 友好的机制，允许用户只下载，不上传

这里有几个关键的问题
1. 在这个互联网发达的时代，资源的可获得性已经很高，平台有什么竞争优势？

	一是针对性，课内学习任务需要的是针对性的资源，Tshare平台就是面向特定学校的
    
    二是高质量，对于具有非针对性的课外资源，平台会把控内容质量，向用户提供高质量的资源，减少用户在自主筛选上花费的时间
    
2. 平台如何有稳定的资源流入

	我们设计了一个有效的激励机制，鼓励用户上传资源和更新旧资源
    
3. 用户利用平台传播非法资源

	平台会进行内容审核，以算法+管理员的方式管理平台内容，禁止上传任何形式的色情，盗版，反动资源，禁止发表低俗，反动言论

4. 上传者拥有的资源可能存在大量的重复

	上传时会有查重过程，如果上传的是重复资源，自动抛弃

###概要设计
网站包含宣传页，基本的用户管理模块：注册，登录，找回密码，还有资料板块和问答板块，用于资料的分享和经验的分享
####拓展功能
为充分发挥互联网平台的优势，我们在不改变立足校园，服务学生的宗旨的条件下，增加几个功能，同时这些功能可以提高网站的影响力和用户黏性
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

###详细设计
####用户管理和个人中心
注册，实名认证

登录，退出登录

找回密码

两类用户，普通用户和管理员

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

###阶段计划
####第2周：开发前准备
前后端程序员学习相关技术，熟悉Git

设计师设计网站标志和原型初稿，至少包括整体框架，宣传页，注册登录，上传搜索下载功能

####第3-4周：第一阶段
完成普通用户的用户管理，资料，问答，个人中心，网站首页

不必完成资料的查重

不必完成资料和发言的内容审核

租服务器，发布网站

####第5-6周：第二阶段
完成查重和审核

增加管理员身份

完成闲置交易，失物招领，表白墙的基础功能

####第7-8周 丰富内容和推广
邀请少量非计算机专业用户使用网站，根据反馈修改设计

邀请跳蚤市场群成员发布闲置信息，邀请表白墙官方账号发布表白墙信息

上传资料，问问题，答问题，丰富网站内容

力求让新用户有比较良好的体验，然后通过校园各大公众号，大范围推广

####第9-11周：第三阶段
完善细节，润色界面

自适应布局，让移动端有良好体验

####课程结束后：第四阶段
润色界面，使用框架重构后端

记录用户行为，访问深度，每一个功能的访问频度，用户画像等等，积累大数据

自己做广告位，找广告主或者（或同时）接入百度、腾讯广告

做一个类似知乎的折叠回答，用算法自动折叠低质量的回答

网站安全加固

增加移动端

组建工作室，纳新，找学弟学妹接手项目，继续运营

推广至其他学校

###产品推广
到跳蚤市场QQ群宣传，邀请入驻闲置交易板块

请观海听涛等校园公众号帮忙宣传

与各学生会、社团达成合作，我们为他们提供活动宣传的途径，他们全体成员注册账号

邀请新用户奖励

与独立广告位的广告主协商，不收广告费而是协办活动，比如和蛋糕房协办转发抽锦鲤送蛋糕的活动

有收入后，可以赞助学生会、社团的活动，取得深入合作，比如邀请10位新用户可获得服装节门票，校园歌手大赛门票