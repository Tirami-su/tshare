$(document).ready(function() {
	var index=sessionStorage.getItem('index')
	var data==JSON.parse(sessionStorage.getItem('res')).data[index]
	console.log(data)
})