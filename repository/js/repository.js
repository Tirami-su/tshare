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
	// 	}, res => {
	// 		res = JSON.parse(res)
	// 		if (res.code == 1) {
	// 			alert('上传成功')
	// 		} else {
	// 			alert(res.msg)
	// 		}
	// 	})
}

/**
 * 搜索框回车事件
 */
function inputSearch(key) {
	if (event.keyCode == 13) {
		// 阻止搜索空字符串
		if (key == '') 
			event.preventDefault()
		else
			searchFile(key,0,0,1,true)
	}
}

/**
 * 搜索资料
 * key,mode,sort,page,change
 */
function searchFile(key,mode,sort,page,change=false) {
	// 请求服务器
	$.get('api/search_file.php', {
		key: key,
		mode: mode,
		sort: sort,
		page: page
	}, res => {
		fileList(key, res)
	},"json")
	// 局部页面改变
	if (change){
		$('#search-form-mid').remove()
		$('#navbar-search').val(key)
	}
	// 等待动画
	$('.main').waitMe({
		effect: 'rotateplane',
		// text:'正在搜索...',
		bg: 'rgba(255,255,255,0.8)',
		color: ['#aaa', '#666'], //前者是字体颜色，后者是动画颜色
		fontSize: '16px'
	})
}

/**
 * 显示搜索结果
 */
function fileList(key, res) {
	// 隐藏等待动画，清除找不到的提示
	$('.main').waitMe('hide')
	$('#nofound').remove()
	// 填模版
	var cellHtml = ''
	if (res.code == 0) {
		cellHtml = template('template-nofound', {
			keyword: key
		})
		$('#result').addClass('d-none')
		$('.main').append(cellHtml)
	} else {
		$('#result').removeClass('d-none')
		// 填列表项模版
		var data = res.data
		for (var i = 0; i < data.length; i++) {
			if (data[i].contents == '')
				cellHtml += template('template-file', data[i])
			else
				cellHtml += template('template-folder', data[i])
		}
		$('#file-list').empty().append(cellHtml)
		// 分页器
		pageHtml=''
		if (res.amount>10){
			for (var i = 0; i < res.amount/10; i++) 
				cellHtml += template('template-page', {num:i})
			$('.page-item:last').before(pageHtml)
			$('.page-item:last').removeClass('disabled')
		}
	}
}

/**
 * 切换搜索模式，可分或不可分
 */
function modeSwitch() {
	// 选中，则可分模式
	if ($('#search-mode').is(':checked')) 
		searchFile($('#navbar-search').val(),1,$('input[name=sort]:checked').val(),1)
	else 
		searchFile($('#navbar-search').val(),0,$('input[name=sort]:checked').val(),1)
}

/**
 * 	切换排序方式
 */
function sortSwitch(sort) {
	var mode=0
	if (!$('#search-mode').is(':checked')) 
		mode=1
	searchFile($('#navbar-search').val(),mode,sort,1)
}