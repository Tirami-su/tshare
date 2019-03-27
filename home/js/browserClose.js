window.onbeforeunload = onbeforeunload_handler;
window.onunload = onunload_handler;

var time = 0;

function onbeforeunload_handler() {
          	// 页面刷新
	time = new Date();
	// var year = time.getFullYear();
	// var month = time.getMonth();
	// var day = time.getDate();
	// var hour = time.getHours();
	// var minute = time.getMinutes();
	// var second = time.getSeconds();
	// var millis = time.getMilliseconds();
	// $.post('test.php', {
	// 	year: year,
	// 	month: month,
	// 	day: day,
	// 	hour: hour,
	// 	minute: minute,
	// 	second: second,
	// 	millis: millis,
	// 	id: 1
	// }, (res) => {
	// 	res = JSON.parse(res)
	// 	if (res.code == 1) {
	// 		// alert("日志修改成功");
	// 	} else {
	// 		alert(res.msg)
	// 	}	
	// });
}   

function onunload_handler() {	
	var newTime = new Date();
	var newYear   = newTime.getFullYear();	// 年份
	var newMonth  = newTime.getMonth();		// 月份
	var newDay	  = newTime.getDate();		// 日期
	var newHour   = newTime.getHours();		// 小时
	var newMinute = newTime.getMinutes();	// 分钟
	var newSecond = newTime.getSeconds();	// 秒
	var newMillis = newTime.getMilliseconds();	// 毫秒

	var year = time.getFullYear();
	var month = time.getMonth();
	var day = time.getDate();
	var hour = time.getHours();
	var minute = time.getMinutes();
	var second = time.getSeconds();
	var millis = time.getMilliseconds();

	var url = "../api/logout.php";

	if(newYear == year && newMonth == month && newDay == day
		&& newHour == hour && newMinute == minute && newSecond == second
		&& newMillis - millis <= 5) {
		// 向服务器脚本发送浏览器关闭的消息
		$.get(url, {
				
			}, (res) => {
				
			});
	}
}