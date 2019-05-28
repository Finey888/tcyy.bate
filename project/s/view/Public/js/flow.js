$(function(){
	flow();

	loadingImg();

});


$(window).on("resize",function(){

	flow();

	loadingImg();
})

var arr = [10,10];

function flow(){

	$(".flow-box").find(".flow-li").each(function( index ){


		var minObj = getMinLen( arr ) , thisHeight =  $(this).height() , left = $(window).width()/2;

		left = left*minObj.index-5;

		$(this).css({top:minObj.value,left:left});

		arr[minObj.index] = arr[minObj.index]+thisHeight+5;

	});

	console.log( getMax( arr ).value );

	$(".flow-box").height( getMax( arr ).value +10);

	arr = [ 10,10]


}

function loadingImg(){

	$(".flow-box").find("img").each(function(){
		this.onload = function(){
			flow();
		}
	});
}





function getMinLen( arr ){

	var minobj = {
		index : 0 ,
		value : arr[0]
	}

	for( var i = 0 ; i < arr.length ; i++ ){
		if( minobj.value > arr[i] ){
			minobj .index = i;
			minobj .value = arr[i] ;
		}
	}
	return minobj;
}

function getMax( arr ){

	var maxObj = {
		index : 0 ,
		value : arr[0]
	}

	for( var i = 0 ; i < arr.length ; i++ ){
		if( maxObj.value < arr[i] ){
			maxObj .index = i;
			maxObj .value = arr[0] ;
		}
	}
	return maxObj;
}