$(document).ready(function() {
	/**
	 * 获取html的url中的参数，搜索文件
	 */
	var key = decodeURI(location.search.split('=')[1])
	$('#navbar-search').val(key)
	// 初始化全局变量
	globalMode = $('#search-mode').prop('checked') ? 0 : 1
	globalSort = $('input[name=sort]:checked').val()
	curPage = 1
	// 立即搜索
	if (sessionStorage.getItem('res') == null)
		searchFile(key)
	else
		fileList(key, JSON.parse(sessionStorage.getItem('res')))
	
	/**
	 * 预览功能按钮(显示整页 适应窗口宽度 放大 缩小)
	 * 关键是调整宽度，高度和左边距
	 */
	rszTimes = 70 //缩放比例 21%～140%
	$('#modal-previewFile .action button').click(function() {
		switch ($(this).data('resize')) {
			case 'w':
				$('#modal-previewFile .target').css({
					'width': '100%',
					'height': 'auto',
					'margin-left': 0
				})
				$(this).children().attr('src', 'img/height.png')
				$(this).data('resize', 'h')
				break
			case 'h':
				$('#modal-previewFile .target').css({
					'width': 'auto',
					'height': height,
				})
				// 计算边距
				var width = $('#modal-previewFile .target').prop('width')
				var viewport = document.body.clientWidth
				var rate = width / viewport * 100
				var margin = rate < 100 ? (100 - rate) / 2 : 0
				$('#modal-previewFile .target').css('margin-left', String(margin) + '%')

				$(this).children().attr('src', 'img/width.png')
				$(this).data('resize', 'w')
				break
			case 'l':
				// 检查是否可继续放大
				if (rszTimes < 140)
					$('#modal-previewFile .target').css(widthRate('+'))
				break
			case 's':
				// 检查是否可继续缩小
				if (rszTimes > 21)
					$('#modal-previewFile .target').css(widthRate('-'))
		}
	})
});

/**
 * 计算新比例和边距
 */
function widthRate(value, delta = 10) {
	var width = $('#modal-previewFile .target').prop('width')
	var viewport = document.body.clientWidth
	var rate = width / viewport * 100

	rate = value == '+' ? rate + delta : rate - delta
	if (rate > 140)
			rate = 140
	else if (rate < 21)
		rate = 21
	var margin = rate < 100 ? (100 - rate) / 2 : 0

	return {
		'width': String(rate) + '%',
		'height': 'auto',
		'margin-left': String(margin) + '%'
	}
}

/**
 * 导航栏搜索框回车事件
 */
function inputSearch(key) {
	if (event.keyCode == 13) {
		// 阻止搜索空字符串
		if (key == '')
			event.preventDefault()
		else
			searchFile(key)
	}
}

/**
 * 搜索资料
 */
function searchFile(key) {
	// 请求服务器
	$.ajax({
		url: 'api/search_file.php',
		type: 'GET',
		data: {
			key: key,
			mode: globalMode,
			sort: globalSort,
			page: curPage
		},
		success: res => {
			sessionStorage.setItem('res', res)
			res = JSON.parse(res)
			fileList(key, res)
		},
		error: (xhr, status, error) => {
			console.log('[Status]', status, '\n[Error]', error)
			// 隐藏等待动画，清除找不到的提示
			$('.main').waitMe('hide')
			$('#nofound').remove()
			// 提示连接服务器超时
		},
		timeout: 5000
	})

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
		data = res.data
		for (var i = 0; i < data.length; i++) {
			// 添加序号
			data[i].index = i
			if (data[i].contents == '') {
				// 判断文件类型，根据类型设置图标
				var nameArray = data[i].name.split('.')
				if (nameArray.length > 1)
					switch (nameArray[nameArray.length - 1]) {
						case 'doc':
						case 'docx':
						case 'odt':
						case 'pages':
							data[i].ext = 'word'
							break
						case 'ppt':
						case 'pptx':
						case 'odp':
						case 'key':
							data[i].ext = 'ppt'
							break
						case 'xls':
						case 'xlsx':
						case 'csv':
						case 'ods':
						case 'numbers':
							data[i].ext = 'excel'
							break
						case 'pdf':
							data[i].ext = 'pdf'
							break
						case 'jpg':
						case 'png':
						case 'bmp':
						case 'gif':
						case 'svg':
							data[i].ext = 'picture'
							break
						case 'c':
						case 'h':
						case 'cpp':
						case 'hpp':
						case 'py':
						case 'java':
						case 'html':
						case 'htm':
						case 'js':
						case 'json':
						case 'css':
						case 'scss':
						case 'php':
						case 'm':
						case 'matlab':
						case 'v':
						case 'md':
						case 'ipynb':
							data[i].ext = 'code'
							break
						case 'mp3':
							data[i].ext = 'audio'
							break
						case 'avi':
							data[i].ext = 'video'
							break
						case 'txt':
						case 'rtf':
						case 'rtfd':
							data[i].ext = 'text'
							break
						default:
							data[i].ext = 'other'
					}
				else
					data[i].ext = 'other'
				cellHtml += template('template-file', data[i])
			} else
				cellHtml += template('template-folder', data[i])
		}
		// 清空列表，重新添加cell
		$('#file-list').empty().append(cellHtml)

		//填分页器模板
		var pageHtml =
			'<li id="page-prev" class="page-item disabled"><a class="page-link" href="#" onclick="prevPage()">上一页</a></li>'
		if (res.amount > 10) {
			totalpages = Math.ceil(res.amount / 10)
			// 如果页数太多，只显示当前页前后9页
			if (totalpages > 9) {
				var start = curPage - 4 > 1 ? curPage - 4 : 1
				var end = curPage + 4 < totalpages ? curPage + 4 : totalpages
				for (var i = start; i <= end; i++)
					pageHtml += template('template-page', {
						num: i
					})
			} else
				for (var i = 1; i <= totalpages; i++)
					pageHtml += template('template-page', {
						num: i
					})
		} else {
			totalpages = 1
			pageHtml += '<li id="page-1" class="page-item"><a class="page-link" href="#" onclick="toPage(this.text)">1</a></li>'
		}
		pageHtml +=
			'<li id="page-next" class="page-item disabled"><a class="page-link" href="#" onclick="nextPage()">下一页</a></li>'
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
	globalMode = $('#search-mode').prop('checked') ? 0 : 1
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

/**
 * 预览文件
 */
function preview() {
	var path = event.target.dataset.url
	// 请求服务器生成预览
	$.ajax({
		url: 'api/preview.php',
		type: 'GET',
		data: {
			url: path
		},
		success: res => {
			if (res.code == 1) {
				// 显示预览
				$('#modal-previewFile .target').attr('src', 'temp/' + path + '.png')
				$('#modal-previewFile').modal('show')
				// 记录初始大小
				width = $('#modal-previewFile .target').prop('width')
				height = $('#modal-previewFile .target').prop('height')
			} else {
				alert(res.msg)
			}
		},
		error: (xhr, status, error) => {
			console.log('[Status]', status, '\n[Error]', error)
		},
		dataType: 'json',
		timeout: 5000
	})
}

/**
 * 查看文件详情
 */
function getDetails(){
	// 通过session把文件数据传到details页面
	var index=event.target.dataset.index
	sessionStorage.setItem('index',index)
	location = "repository/details.html"
}

/**
 * 重置预览modal的css
 */
function resetCss(){
	$('#modal-previewFile .target').css({
		'width': '70%',
		'height': 'auto',
		'margin-left': '15%'
	})
}