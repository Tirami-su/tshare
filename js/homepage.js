$(function(){
	/*$('.u189_img').click(function(){
		myClickshow();
		myClickhide();
	})
	$('u192_input').click(function(){
		srchcn();
	})*/
	var 
	
		$img="u193_img",
		$u192_input="u192_input";
	
	/*function srchcn(){	*/	
			$("u193_img").click(function(){
					$("u193_img").hide();
			});
			$("u192_input").mouseout(function(){
					$("u193_img").show();
			});
		
	//}
});
/*
		var $img=
		if(onmouseover='u192_input'.focus()){
				'u193_img'.hide();
		}else{
				'u193_img'.show();
		}
	}
	function myClickhide(){
				var event=document.getElementById('u204');
				event.hide();
			}
	function myClickshow(){
				var event=document.getElementById('u204');
				event.show();
			}
	
})*/