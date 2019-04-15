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
	searchFile(key)

	/**
	 * 预览功能按钮(显示整页 适应窗口宽度 放大 缩小)
	 */
	$('#modal-previewFile .action button').click(function() {
		switch ($(this).data('resize')) {
			case 'w':
				$('#modal-previewFile .target').css({
					'width': '100%',
					'height': 'auto'
				})
				$(this).children().attr('src', 'img/height.png')
				$(this).data('resize', 'h')
				break
			case 'h':
				$('#modal-previewFile .target').css({
					'width': 'auto',
					'height': '100%'
				})
				$(this).children().attr('src', 'img/width.png')
				$(this).data('resize', 'w')
				break
			case 'l':
				var width = $('#modal-previewFile .target').prop('width')
				var height = $('#modal-previewFile .target').prop('height')
				$('#modal-previewFile .target').css({
					'width': width * 1.2,
					'height': height * 1.2
				})
				break
			case 's':
				var width = $('#modal-previewFile .target').prop('width')
				var height = $('#modal-previewFile .target').prop('height')
				$('#modal-previewFile .target').css({
					'width': width * 0.8,
					'height': height * 0.8
				})
		}
	})
});

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
		success: res => fileList(key, res),
		error: (xhr, status, error) => {
			console.log('[Status]', status, '\n[Error]', error)
			// 隐藏等待动画，清除找不到的提示
			$('.main').waitMe('hide')
			$('#nofound').remove()
			// 提示连接服务器超时
		},
		dataType: 'json',
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
		// 填cell模版
		data = res.data
		for (var i = 0; i < data.length; i++) {
			// 添加序号
			data[i].index=i
			if (data[i].contents == '') {
				// 判断文件类型，根据类型设置图标
				var nameArray = data[i].name.split('.')
				if (nameArray.length > 1) {
					switch (nameArray[nameArray.length - 1]) {
						case 'doc':
						case 'docx':
						case 'odt':
						case 'pages':
							data[i].type = 'word'
							break
						case 'ppt':
						case 'pptx':
						case 'odp':
						case 'key':
							data[i].type = 'ppt'
							break
						case 'xls':
						case 'xlsx':
						case 'csv':
						case 'ods':
						case 'numbers':
							data[i].type = 'excel'
							break
						case 'pdf':
							data[i].type = 'pdf'
							break
						case 'jpg':
						case 'png':
						case 'bmp':
						case 'gif':
						case 'svg':
							data[i].type = 'picture'
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
							data[i].type = 'code'
							break
						case 'mp3':
							data[i].type = 'audio'
							break
						case 'avi':
							data[i].type = 'video'
							break
						case 'txt':
						case 'rtf':
						case 'rtfd':
							data[i].type = 'text'
							break
					}
					cellHtml += template('template-file', data[i])
				}
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
	// 请求服务器生成预览
	$.ajax({
		url: 'api/preview.php',
		type: 'GET',
		data: {
			url: event.target.dataset.url
		},
		success: res => {
			if (res.code == 1) {
				$('preview-target').attr('src', 'temp/' + path + '.png')
				$('#modal-previewFile').modal('show')
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
	sessionStorage.setItem('file',data[index])
	location.pathname = "details.html"
}