/**
 * 切换表单内容
 */
function categorySwitch(value) {
	if (value == '0') {
		$('#content1').addClass('d-none')
		$('#content0').removeClass('d-none')
	} else {
		$('#content0').addClass('d-none')
		$('#content1').removeClass('d-none')
	}
}
