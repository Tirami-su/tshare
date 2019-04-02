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
function upload() {
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
function search(key) {
	// 回车搜索
	if (event.keyCode == 13) {
		// 阻止搜索空字符串
		if (key == '')
			return
		$.get('api/search_file.php', {
			key: key,
			type: 'all',
			page: 1
		}, (res) => {
			fileList(key, JSON.parse(res))
		})
		$('#search-form-mid').remove()
		$('#result input').val(key)
	}
}

/**
 * 显示搜索结果
 */
function fileList(key, res) {
	var html
<<<<<<< HEAD
	for (var i = 0; i < data.length; i++)
		html += template('template-file', data[i])
	$('#file-list').append(html)
}
=======
	if (res.code == 0){
		html = template('template-nofound', key)
		$('body').append(html)
	}
	else {
		$('#result').removeClass('d-none')
		var data = res.data
		for (var i = 0; i < data.length; i++)
			html += template('template-file', data[i])
		$('#file-list').append(html)
	}
}
>>>>>>> 1ed2d00f29d874406b2440b7f4d8df669ca00ee7
