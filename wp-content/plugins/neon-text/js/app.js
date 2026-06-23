jQuery( document ).ready(function($) {	
    if ($(".nbneontext").length) {
		$('.nbneontext').each(function(i){
			var id=$(this).attr('id');
			
			var atts={};
			
			var reblinkProbability = $(this).attr('data-reblinkProbability');
			if (reblinkProbability.length>0) { atts["reblinkProbability"]=reblinkProbability; }
			
			var blinkMin = $(this).attr('data-blinkMin');
			if (blinkMin.length>0) { atts["blinkMin"]=blinkMin; }
			
			var blinkMax = $(this).attr('data-blinkMax');
			if (blinkMax.length>0) { atts["blinkMax"]=blinkMax; }
			
			var loopMin = $(this).attr('data-loopMin');
			if (loopMin.length>0) { atts["loopMin"]=loopMin; }
			
			var loopMax = $(this).attr('data-loopMax');
			if (loopMax.length>0) { atts["loopMax"]=loopMax; }
			
			var dcolor = $(this).attr('data-color');
			if (dcolor.length>0) { atts["color"]=dcolor; }
			
			var glow = $(this).attr('data-glow');
			if (glow.length>0) {
				glowsplit=glow.split(',');
				atts["glow"]=glowsplit;
			}
			
			var doff = $(this).attr('data-off');
			if (doff.length>0) { atts["off"]=doff; }
			
			$('#'+id).novacancy(atts);
		});
	}
    if ($(".board_wrap").length) {
		$('.board_wrap').each(function(i){
			$(this).css('height',$(this).find('.board').height()+50);
		});
	}
});