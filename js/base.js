/**
 * 输入框获取焦点时去除错误提示
 */
$(document).ready(function() {
	$('input').on('focus', function() {
		$(this).removeClass('input-error');
	});
});

/**
 * 登录模态对话框显示事件
 */
function showLogin() {
	// 显示模态对话框
	modalSwitch('login')
	$('#modal-register').modal('show')
}

/**
 * 注册模态对话框显示事件
 */
function showRegister() {
	// 显示模态对话框
	modalSwitch('register')
	$('#modal-register').modal('show')
}

/**
 * 发送邮箱验证码
 */
function sendEmailCode() {
	// 检查学号是否为空
	if ($('#input-id-register').val() == "") {
		$('#input-id-register').addClass('input-error');
		return
	}
	// 首先验证图形验证码
	var graph_code = $("#input-graph-code").val()
	if (graph_code == "") {
		$('#input-graph-code').addClass('input-error');
		return
	}
	if (!verifyCode.validate(graph_code)) {
		alert("验证码错误");
		return
	}
	// 发送邮箱验证码
	var id = $("#input-id-register").val()
	$.get('api/email_code.php', {
		id: id
	}, (res) => {
		res = JSON.parse(res)
		if (res.code == 0)
			alert(res.msg)
	})

	// 发送验证码按钮的倒计时
	$('#btn-send').attr('disabled', true)
	$('#btn-send').text('60秒后可重新发送')
	$('#btn-send').css({
		background: '#d8d8d8',
		color: '#707070',
	});
	count = 60
	const sendCD = setInterval(() => {
		var btn = $('#btn-send')
		if (count === 0) {
			btn.text('重新发送')
			btn.removeAttr('disabled')
			btn.css({
				background: '#fd625e',
				color: '#fff',
			})
			clearInterval(sendCD)
		} else
			btn.text(count + '秒后可重新发送')
		count--
	}, 1000)
}

/**
 * 验证邮箱验证码
 */
function confirmEmailCode() {
	// 检查输入框是否为空，空则提示
	var empty = false
	$('#content-register form').find('input').each(function() {
		if ($(this).val() == "") {
			empty = true
			$(this).addClass('input-error');
		}
	});
	// 验证邮箱验证码
	var id = $("#input-id-register").val()
	var email_code = $("#input-email-code").val()
	if (!empty) {
		$.post('api/email_code_confirm.php', {
			id: id,
			email_code: email_code
		}, (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				modalSwitch('#modal-register', '#modal-setting')
			} else {
				alert(res.msg)
			}
		})
	}
}

/**
 * 注册
 */
function register() {
	// 检查输入框是否为空，空则提示
	var empty = false
	$('#content-setting form').find('input').each(function() {
		if ($(this).val() == "") {
			empty = true
			$(this).addClass('input-error');
		}
	});
	// 注册
	if (!empty) {
		var id = $('#input-id-register').val()
		var name = $('#input-name').val()
		var pwd = $('#input-pwd-setting').val()
		$.post('api/register.php', {
			id: id,
			name: name,
			pwd: pwd
		}, (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				modalSwitch('#modal-register', '#modal-login')
			} else {
				alert(res.msg)
			}
		})
	}
}

/**
 * 登录
 */
function login() {
	// 检查输入框是否为空，空则提示
	var empty = false
	$('#content-login form').find('input').each(function() {
		if ($(this).val() == "") {
			empty = true
			$(this).addClass('input-error');
		}
	});
	// 登录验证
	if (!empty) {
		var id = $('#input-id-login').val()
		var pwd = $('#input-pwd-login').val()
		$.post('api/login.php', {
			id: id,
			pwd: pwd
		}, (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				location.pathname = "home/home.html"
			} else {
				alert(res.msg)
			}
		})
	}
}

/**
 * 切换模态对话框
 */
function modalSwitch(value) {
	// 策略一 登录注册的切换：切换对话框，注册切换到设置用户名密码：替换内容
	// 	if (showm == '#modal-setting') {
	// 		$('#content-register').addClass('d-none')
	// 		$('#content-setting').removeClass('d-none')
	// 	} else {
	// 		$(hidem).modal('hide')
	// 		$(showm).modal('show')
	// 	}
	// 策略二 无论什么切换都通过替换内容实现
	switch (value) {
		case 'login':
			// 清除错误提示
			$('#content-login form').find('input').each(function() {
				$(this).removeClass('input-error');
			});
			$('#content-register').addClass('d-none')
			$('#content-setting').addClass('d-none')
			$('#content-login').removeClass('d-none')
			break;
		case 'register':
			// 清除错误提示
			$('#content-register form').find('input').each(function() {
				$(this).removeClass('input-error');
			});
			// 生成图形验证码，只生成一次
			if (typeof verifyCode == 'undefined')
				verifyCode = new GVerify("img-graph-code") //全局变量
				
			$('#content-login').addClass('d-none')
			$('#content-setting').addClass('d-none')
			$('#content-register').removeClass('d-none')
			break;
		case 'setting':
			$('#content-register').addClass('d-none')
			$('#content-setting').removeClass('d-none')
			break;
	}
}

/**
 * 全站搜索
 */
function globalSearch(e) {
	var key = $('#global-search').val()
	alert(key)
	if (key == "")
		e.preventDefault()
}
