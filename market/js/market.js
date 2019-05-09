/**
 * 导航栏搜索框和中心搜索框回车事件
 */
function inputSearch(key) {
	if (event.keyCode == 13) {
		// 阻止搜索空字符串
		if (key == '')
			event.preventDefault()
		else {
			// 跳转到list.html并传值
			location = "list.html?key="+encodeURI(key)
		}
	}
}