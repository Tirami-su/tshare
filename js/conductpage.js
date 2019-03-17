$(function(){
  $("#u159").click(function(){
    $("#cd-login").show();
    mask_open();
  })
  $("#close_login").click(function(){
    $("#cd-login").hide();
    mask_close();

  })
  $("#u161").click(function(){
    $("#cd-signup").show();
    createCode();
    mask_open();
  })
  $("#close_signup").click(function(){
    $("#cd-signup").hide();
    mask_close();
  })
  $("#btnsignup").click(function(){
    $("#cd-signup").show();
    $("#cd-login").hide();
  })
  $("#btnlogin").click(function(){
    $("#cd-signup").hide();
    $("#cd-login").show();
  })
          //background darken
          function mask_open(){
            $('#mask').fadeIn(500);
          }
          //backgroune lighten
          function mask_close(){
            $('#mask').fadeOut(500);
          }
        })
//验证码生成
  var code;
          function createCode() {  //函数体
            code = "";
              var codeLength = 5; //验证码的长度
              var checkCode = document.getElementById("checkCode");
              var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
                'o','p','q','r','s','t','u','v','w','x','y','z',
                'A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
                'L','M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 
              'W', 'X', 'Y', 'Z'); //所有候选组成验证码的字符，当然也可以用中文的
              for (var i = 0; i < codeLength; i++)
              {
                  var charNum = Math.floor(Math.random() * 52);//设置随机产生
                  code += codeChars[charNum];
              }
                if (checkCode)
                {
                  checkCode.className = "code";
                  checkCode.innerHTML = code;
                }
              } 

              //验证
              function GetDom()
              {
                if(document.getElementById("change_identify_code").value==""){
                  alert("验证码不能为空！");
                  createCode();//输错一次或提交一次都将会刷新一次验证码
                  return false; //结束本次会话
                }
                else if(document.getElementById("change_identify_code").value.toUpperCase()!=code.toUpperCase()){ //toUpperCase不区分大小写
                  alert("您输入的验证码有误，请重新输入！！");
                  createCode();//读取文件
                }                  
                else{
                  alert("ok");
                }

              } 
    //向php页面提交数据
       function sendData(url, callback) {
        xmlHttp = new XMLHttpRequest();
        // 请求行
        xmlHttp.open("GET", url);
        // 请求头
        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-unlencoded");
        // 设置数据
        xmlHttp.send(null);
        // 监听服务器响应
        xmlHttp.onreadystatechange = function() {
            if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                callback(xmlHttp.responseText);
            }
        }
        return responseText;
    }

    /*发送邮箱验证码*/
    function sendVeri() {
        var id = document.getElementById('signup-username').value;    // 获取学号
        var url = "./api/send_verification.php" + "?id=" + id;
        sendData(url, function(responseText){
            if(responseText == 1) {
                alert("邮箱验证码发送成功");
            } else if(responseText == 0) {
                alert("邮箱验证码发送失败");
            } else {
                alert("未知错误");
            }
        });
    }

    /*注册验证*/
    function register() {
        var id = document.getElementById('signup-username').value;    // 获取学号
        var pwd = document.getElementById('signup-password').value;   // 获取密码
        var veri = document.getElementById('email-password').value;   // 获取邮箱验证码
        
        var url = "./api/register_check.php" + "?id=" + id + "&pwd=" + pwd + "&veri=" + veri;
        var responseText = sendData(url, function(responseText) {
            if(responseText == "0") {
                alert("邮箱验证码错误");
            } else if(responseText == "1") {
                alert("邮箱验证码失效");
            } else if(responseText == "2") {
                alert("用户已注册");
            } else if(responseText == "3") {
                alert("注册成功");
                window.location.href = "./";
            } else {
                alert("未知错误");
            }
        });
    }

    /*登录验证*/
    function login() {
        var id = document.getElementById("login-username").value;     // 获取学号
        var pwd = document.getElementById("login-password").value;    // 获取密码

        var url = "./api/login.php" + "?id=" + id + "&pwd=" + pwd;
        sendData(url, function(responseText) {
            if(responseText == 0) {
                alert("用户不存在");
            } else if(responseText == 1) {
                alert("密码错误");
            } else if(responseText == 2) {
                alert("登录成功");
                window.location.href = "./home";
            } else {
                alert("未知错误");
            }
        });
    }