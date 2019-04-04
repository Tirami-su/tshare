/**
 * 自动登录
 */
$(document).ready(function() {
		$.post('api/login.php', (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				location.pathname = "home/home.html"
			}
		})
})

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
 * 选择教育邮箱地址
 */
$(document).ready(function() {
	$('#input-addr-login').next().children().on('click', function() {
		$('#input-addr-login').text($(this).text())
	});
	$('#input-addr-register').next().children().on('click', function() {
		$('#input-addr-register').text($(this).text())
	});
});

/**
 * 图像验证码
 */
class GraphVerify {
	constructor(arg) {
		this.id = arg //容器Id
		this.canvasId = "verifyCanvas" //canvas的ID
		this.width = "120" //默认canvas宽度
		this.height = "45" //默认canvas高度
		this.length = 4
		this.type = "blend" //图形验证码默认类型blend:数字字母混合类型、number:纯数字、letter:纯字母
		this.code = ""
		this.numArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
		this.letterArr =
			"a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z".split(",")

		var con = document.getElementById(this.id)
		var canvas = document.createElement("canvas")
		canvas.id = this.canvasId
		canvas.width = this.width
		canvas.height = this.height
		canvas.style.cursor = "pointer"
		canvas.innerHTML = "您的浏览器版本不支持canvas"
		con.appendChild(canvas)
		var parent = this
		// 点击重新生成图片
		canvas.onclick = function() {
			parent.refresh()
		}
		// 立即生成图片
		this.refresh()
	}

	// 生成验证码
	refresh() {
		this.code = ""
		var canvas = document.getElementById(this.canvasId)
		if (canvas.getContext)
			var ctx = canvas.getContext('2d')
		else
			return

		ctx.textBaseline = "middle"

		ctx.fillStyle = this.randomColor(180, 240)
		ctx.fillRect(0, 0, this.width, this.height)


		if (this.type == "blend") //判断验证码类型
			var txtArr = this.numArr.concat(this.letterArr)
		else if (this.type == "number")
			var txtArr = this.numArr
		else
			var txtArr = this.letterArr

		for (var i = 1; i <= this.length; i++) {
			var txt = txtArr[this.randomNum(0, txtArr.length)]
			this.code += txt
			ctx.font = this.randomNum(this.height / 2, this.height) + 'px SimHei' //随机生成字体大小
			ctx.fillStyle = this.randomColor(50, 160) //随机生成字体颜色        
			ctx.shadowOffsetX = this.randomNum(-3, 3)
			ctx.shadowOffsetY = this.randomNum(-3, 3)
			ctx.shadowBlur = this.randomNum(-3, 3)
			ctx.shadowColor = "rgba(0, 0, 0, 0.3)"
			var x = this.width / (this.length + 1) * i
			var y = this.height / 2
			var deg = this.randomNum(-30, 30)
			//设置旋转角度和坐标原点
			ctx.translate(x, y)
			ctx.rotate(deg * Math.PI / 180)
			ctx.fillText(txt, 0, 0)
			//恢复旋转角度和坐标原点
			ctx.rotate(-deg * Math.PI / 180)
			ctx.translate(-x, -y)
		}
		//绘制干扰线
		for (var i = 0; i < 4; i++) {
			ctx.strokeStyle = this.randomColor(40, 180)
			ctx.beginPath()
			ctx.moveTo(this.randomNum(0, this.width), this.randomNum(0, this.height))
			ctx.lineTo(this.randomNum(0, this.width), this.randomNum(0, this.height))
			ctx.stroke()
		}
		//绘制干扰点
		for (var i = 0; i < this.width / 4; i++) {
			ctx.fillStyle = this.randomColor(0, 255)
			ctx.beginPath()
			ctx.arc(this.randomNum(0, this.width), this.randomNum(0, this.height), 1, 0, 2 * Math.PI)
			ctx.fill()
		}
	}

	// 生成随机数
	randomNum(min, max) {
		return Math.floor(Math.random() * (max - min) + min)
	}

	//生成随机色
	randomColor(min, max) {
		var r = this.randomNum(min, max)
		var g = this.randomNum(min, max)
		var b = this.randomNum(min, max)
		return "rgb(" + r + "," + g + "," + b + ")"
	}

	// 验证验证码
	validate(code) {
		var code = code.toLowerCase()
		var v_code = this.code.toLowerCase()
		if (code == v_code)
			return true
		else {
			this.refresh()
			return false
		}
	}
}

/**
 * 发送邮箱验证码
 */
function sendEmailCode() {
	// 检查学号是否为空
	if ($('#input-id-register').val() == "") {
		$('#input-id-register').addClass('input-error')
		return
	}
	// 首先验证图形验证码
	var graph_code = $("#input-graph-code").val()
	if (graph_code == "") {
		$('#input-graph-code').addClass('input-error')
		return
	}
	if (!verifyCode.validate(graph_code)) {
		alert("验证码错误")
		return
	}
	// 发送邮箱验证码
	$.get('api/email_code.php', {
		id: $("#input-id-register").val()+'@'+$('#input-addr-register').text()
	}, (res) => {
		res = JSON.parse(res)
		if (res.code == 0)
			alert(res.msg)
	})

	// 发送验证码按钮的倒计时
	$('#btn-send').attr('disabled', true)
	$('#btn-send').text('60秒后可重发')
	$('#btn-send').css({
		background: '#d8d8d8',
		color: '#707070',
	})
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
			btn.text(count + '秒后可重发')
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
			$(this).addClass('input-error')
		}
	})
	// 验证邮箱验证码
	if (!empty) {
		$.post('api/email_code_confirm.php', {
			id: $("#input-id-register").val()+'@'+$('#input-addr-register').text(),
			email_code: $("#input-email-code").val()
		}, (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				modalSwitch('setting')
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
			$(this).addClass('input-error')
		}
	})
	// 注册
	if (!empty) {
		$.post('api/register.php', {
			id: $('#input-id-register').val(),
			name: $('#input-name').val(),
			pwd: $('#input-pwd-setting').val()
		}, (res) => {
			res = JSON.parse(res)
			if (res.code == 1) {
				modalSwitch('login')
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
			$(this).addClass('input-error')
		}
	})
	// 登录验证
	if (!empty) {
		$.post('api/login.php', {
			id: $('#input-id-login').val()+'@'+$('#input-addr-login').text(),
			pwd: $('#input-pwd-login').val(),
			auto_login: $('#remember').prop('checked') ? 1 : 0
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
				$(this).removeClass('input-error')
			})
			$('#content-register').addClass('d-none')
			$('#content-setting').addClass('d-none')
			$('#content-login').removeClass('d-none')
			break
		case 'register':
			// 清除错误提示
			$('#content-register form').find('input').each(function() {
				$(this).removeClass('input-error')
			})
			// 生成图形验证码，只生成一次
			if (typeof verifyCode == 'undefined')
				verifyCode = new GraphVerify("img-graph-code") //全局变量

			$('#content-login').addClass('d-none')
			$('#content-setting').addClass('d-none')
			$('#content-register').removeClass('d-none')
			break
		case 'setting':
			$('#content-register').addClass('d-none')
			$('#content-setting').removeClass('d-none')
			break
	}
}
