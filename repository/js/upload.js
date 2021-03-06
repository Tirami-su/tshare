$(document).ready(function() {
	// 初始化文件上传框动画
	bsCustomFileInput.init()
});

/**
 * 切换表单
 */
function categorySwitch(value) {
	// 清除输入框错误提示
	$('#upload-form').find('input').add('#upload-form textarea').each(function() {
		$(this).removeClass('input-error')
	})
	// 清除文件选择器错误提示
	$('#file').next().removeClass('input-error')
}

/**
 * 选择文件
 */
function selectFile() {
	if (event.target.files.length > 0) {
		// html没加multiple属性，所以只可能有一个文件
		var file = event.target.files[0]
		// 限制文件大小在1G以内
		if (file.size > 1024 * 1024 * 1024) {
			alert('文件太大')
			event.target.value = ''
		} else {
			// 自动填充资料名称
			var filename=file.name.split('.').slice(0, -1).join('.')
			$('#name').val(filename)
		}
	}
}

/**
 * 上传文件
 */
function upload() {
	// 检查输入框是否为空，空则提示，并阻止上传
	var empty = false
	if ($('#file')[0].value == '') {
		empty = true
		$('#file').next().addClass('input-error')
	}
	$('#upload-form').find('input').add('#upload-form textarea').not(':hidden').each(function() {
		if ($(this).val() == "") {
			empty = true
			$(this).addClass('input-error');
		}
	});
	if (empty)
		return
		
	// 上传
	var category = $('#tab-category').find('a.active').text() == '课外资料' ? 1 : 0
	var formdata = new FormData($('#upload-form')[0])
	formdata.append('category', category)
	if (category==1)
		formdata.set('subject', $('#ex-subject').val())
	$.ajax({
		url: 'api/upload.php',
		type: 'POST',
		data: formdata,
		success: res => {
			if (res.code == 1) {
				alert('上传成功')
			} else
				alert(res.msg)
		},
		error: (xhr, status, error) => console.log('[Status]', status, '\n[Error]', error),
		processData: false, // 不处理数据
		contentType: false, // 不设置内容类型
		dataType: 'json',
		timeout: 5000
	})
}
