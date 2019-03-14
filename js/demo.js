$(function () {
$('.mask').click(function(){
	Rhideslider();
	Lhideslider();
})
$('.small').click(function(){
	Rshowslider();
})

function Rshowslider(){
	$('.mask').fadeIn()
	$('.Rsilder').css('right',0)
}
function Rhideslider(){
	$('.mask').fadeOut()
	$('.Rsilder').css('right',-300)
}

$('.logo').click(function(){
	$('.mask').fadeIn()
	$('.Lsilder').css('left',0)
})
$('.signin').click(function(){
	
})
//------以下为各功能分页跳转-------------
function Lshowslider(){
	$('.mask').fadeIn()
	$('.Lsilder').css('left',0)
}
function Lhideslider(){
	$('.mask').fadeOut()
	$('.Lsilder').css('left',-300)
}

}
)