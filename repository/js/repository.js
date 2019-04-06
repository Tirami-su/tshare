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
		else {
			// 全局变量初始化
			if (typeof globalMode == 'undefined')
				globalMode = $('#search-mode').is(':checked') ? 0 : 1,
				if (typeof globalSort == 'undefined')
					globalSort = $('input[name=sort]:checked').val()
			curPage = 1
			// 搜索
			searchFile(key, true)
		}
	}
}

/**
 * 搜索资料
 */
function searchFile(key, change = false) {
	// 请求服务器
	$.get('api/search_file.php', {
		key: key,
		mode: globalMode,
		sort: globalSort,
		page: curPage
	}, res => {
		fileList(key, res)
	}, "json")
	// 局部页面改变
	if (change) {
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
		// 填cell模版
		var data = res.data
		for (var i = 0; i < data.length; i++) {
			if (data[i].contents == '')
				cellHtml += template('template-file', data[i])
			else
				cellHtml += template('template-folder', data[i])
		}
		// 清空列表，重新添加cell
		$('#file-list').empty().append(cellHtml)

		//填分页器模板
		pageHtml = '<li id="page-prev" class="page-item disabled"><a class="page-link" href="#" onclick="prevPage()">上一页</a></li>'
		if (res.amount > 10) {
			totalpages = Meth.ceil(res.amount / 10)
			// 如果页数太多，只显示当前页前后9页
			if (totalpages > 9) {
				var start = curPage - 4 > 1 ? curPage - 4 : 1,
					var end = curPage + 4 < totalpages ? curPage + 4 : totalpages,
						for (var i = start; i <= end; i++)
							pageHtml += template('template-page', {num: i})
			} else
				for (var i = 1; i <= totalpages; i++)
					pageHtml += template('template-page', {num: i})
		} else
			pageHtml += '<li id="page-1" class="page-item"><a class="page-link" href="#" onclick="toPage(this.text)">1</a></li>'
		pageHtml += '<li id="page-next" class="page-item disabled"><a class="page-link" href="#" onclick="nextPage()">下一页</a></li>'
		// 清空分页器，重新添加页项
		$('#pagination').empty().append(pageHtml)
		// 设置按钮状态
		if (curPage != 1)
			$('#page-prev').removeClass('disabled')
		if (curPage < totalpages)
			$('#page-next').removeClass('disabled')
		$('#page-' + curPage).addClass('active')
	}
}

/**
 * 切换搜索模式，可分或不可分
 */
function modeSwitch() {
	globalMode = $('#search-mode').is(':checked') ? 0 : 1,
		curPage = 1
	searchFile($('#navbar-search').val())
}

/**
 * 	切换排序方式
 */
function sortSwitch(sort) {
	globalSort = sort
	curPage = 1
	searchFile($('#navbar-search').val())
}

/**
 * 上一页
 */
function prevPage() {
	curPage -= 1
	searchFile($('#navbar-search').val())
}

/**
 * 下一页
 */
function nextPage() {
	curPage += 1
	searchFile($('#navbar-search').val())
}

/**
 * 跳转到某一页
 */
function toPage(page) {
	curPage = page
	searchFile($('#navbar-search').val())
}
