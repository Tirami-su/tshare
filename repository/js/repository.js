/**
 * 每当页面加载时都设置一下表单，以免浏览器返回时显示错误
 */
$(document).ready(function() {
	if ($('input[name=category]:checked').val() == '1') {
		$('#content0').addClass('d-none')
		$('#content1').removeClass('d-none')
	}
});

/**
 * 切换表单
 */
function categorySwitch(value) {
	// 清除错误提示
	$('#upload-form').find('input').each(function() {
		$(this).removeClass('input-error')
	})
	if (value == '0') {
		$('#content1').addClass('d-none')
		$('#content0').removeClass('d-none')
	} else {
		$('#content0').addClass('d-none')
		$('#content1').removeClass('d-none')
	}
}

/**
 * 上传文件
 */
function upload(event) {
	// 检查输入框是否为空，空则提示，并阻止上传
	var empty = false
	$('#upload-form').children().not('.d-none').find('input').each(function() {
		if ($(this).val() == "") {
			empty = true
			$(this).addClass('input-error');
		}
	});
	if (empty)
		event.preventDefault()
	// 	$.post('api/upload.php', {
	// 		category:$('input[name=category]:checked').val(),
	// 		file:$('#file').val(),
	// 		name:$('#name').val(),
	// 		subject:$('#subject').val(),
	// 		teacher:$('#teacher').val(),
	// 		type:$('#type').val(),
	// 		time:$('#time').val(),
	// 		description:$('#description').val(),
	// 	}, (res) => {
	// 		res = JSON.parse(res)
	// 		if (res.code == 1) {
	// 			alert('上传成功')
	// 		} else {
	// 			alert(res.msg)
	// 		}
	// 	})
}

/**
 * 搜索资料
 */
function search(event) {
	// 回车搜索
	if (event.keyCode == 13) {
		$.get('api/search_file.php', {
			key: $('#input-search').val(),
			page: 1
		}, (res) => {
			$('body').html(res)
		})
	}
}
