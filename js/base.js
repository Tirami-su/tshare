/**
 * 输入框获取焦点时去除错误提示
 */
$(document).ready(function(){
	$('input').on('focus', function() {
		$(this).removeClass('input-error');
	});
});

/**
 * 登录模态对话框显示事件
 */
function showLogin(){
	// 清除错误提示
	$('#modal-login form').find('input').each(function(){
		$(this).removeClass('input-error');
	});
}

/**
 * 注册模态对话框显示事件
 */ 
function showRegister(){
	// 清除错误提示
	$('#modal-register form').find('input').each(function(){
		$(this).removeClass('input-error');
	});
	// 生成图形验证码，只生成一次
	if (typeof verifyCode == 'undefined')
		verifyCode = new GVerify("img-graph-code")//全局变量
}

/**
 * 发送邮箱验证码
 */ 
function sendEmailCode(){
	// 首先验证图形验证码
	var graph_code = $("#input-graph-code").val()
	if (graph_code==""){
		$('#input-graph-code').addClass('input-error');
		return
	}
	if(!verifyCode.validate(graph_code)){
		alert("验证码错误");
		return
	}
	// 发送邮箱验证码
	var id=$("#input-id-register").val()
	$.get('api/email_code.php', {id:id}, (res)=>{
		res=JSON.parse(res)
		if (res.code==0)
			alert(res.msg)
	})
	
	// 发送验证码按钮的倒计时
	$('#btn-send').attr('disabled', true)
	$('#btn-send').text('60秒后可重新获取')
	$('#btn-send').css({
	background: '#d8d8d8',
	color: '#707070',
	});
    count = 60
    const sendCD = setInterval(()=>{
		var btn=$('#btn-send')
		if (count === 0) {
			btn.text('重新发送')
			btn.removeAttr('disabled')
			btn.css({
			background: '#ff9400',
			color: '#fff',
			})
			clearInterval(sendCD)
		} else 
			btn.text(count + '秒后可重新获取')
		count--
	}, 1000)
}

/**
 * 验证邮箱验证码
 */
function confirmEmailCode(){
	// 检查输入框是否为空，空则提示
	var empty=false
	$('#modal-register form').find('input').each(function(){
		if( $(this).val() == "" ) {
			empty=true
			$(this).addClass('input-error');
		}
	});
	// 验证邮箱验证码
	if (!empty) {
		$.post('api/email_code_confirm.php', {id:id, email_code:email_code}, (res)=>{
			res=JSON.parse(res)
			if (res.code==1){
				modalSwitch('setinfo')
			}
			else{
				alert(res.msg)
			}
		})
	}
}

/**
 * 注册
 */
function register(){
	// 检查输入框是否为空，空则提示
	var empty=false
	$('#modal-setting form').find('input').each(function(){
		if( $(this).val() == "" ) {
			empty=true
			$(this).addClass('input-error');
		}
	});
	// 注册
	if (!empty){
		var name=$('#input-name').val()
		var pwd=$('#input-pwd-setting').val()
		$.post('api/register.php', {name:name,pwd:pwd}, ()=>{
			res=JSON.parse(res)
			if (res.code==1){
				modalSwitch('login')
			}
			else{
				alert(res.msg)
			}		
		})
	}
}

/**
 * 登录
 */
function login(){
	// 检查输入框是否为空，空则提示
	var empty=false
	$('#modal-login form').find('input').each(function(){
		if( $(this).val() == "" ) {
			empty=true
			$(this).addClass('input-error');
		}
	});
	// 登录验证
	if (!empty){
		var id=$('#input-id-login').val()
		var pwd=$('#input-pwd-login').val()
		$.post('api/login.php', {id:id,pwd:pwd}, ()=>{
			res=JSON.parse(res)
			if (res.code==1){
				location.pathname="/home.html"
			}
			else{
				alert(res.msg)
			}		
		})
	}
}
