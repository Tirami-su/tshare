/**
 * 输入框获取焦点时去除错误提示
 */
$(document).ready(function() {
	$('input').on('focus', function() {
		$(this).removeClass('input-error');
	});
});

/**
 * 全站搜索
 */
function globalSearch(e) {
	var key = $('#global-search').val()
	alert(key)
	if (key == "")
		e.preventDefault()
}

/**
 * 退出
 */
function logout() {
	$.get('api/logout.php', (res) => {
		res = JSON.parse(res)
		if (res.code == 1) {
			location.pathname = "index.html"
		} else {
			alert(res.msg)
		}
	})
}
