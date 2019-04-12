/**
 * 输入框获取焦点时去除错误提示 
 */
$(document).ready(function() {
	$('input').on('focus', function() {
		$(this).removeClass('input-error');
	});
	$('.custom-file-input').on('focus', function() {
		$(this).next().removeClass('input-error');
	})
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
	$.ajax({
		url: '/api/logout.php',
		type: 'GET',
		success: res => {
			if (res.code == 1)
				location.pathname = "index.html"
			else
				alert(res.msg)
		},
		error: (xhr, status, error) => console.log('[Status]', status, '\n[Error]', error),
		dataType: 'json',
		timeout: 5000
	})
}