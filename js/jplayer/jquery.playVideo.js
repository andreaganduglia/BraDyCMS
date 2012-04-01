/**
 * @author			Julian Bogdani <jbogdani@gmail.com>
 * @copyright		BraDypUS 2007-2011
 * @license			All rights reserved
 * @since			Mar 23, 2012
 * 
 * example:
 * <div	class="video" or class="video fancy"> 
 * 	{
 * 		"width" : "640px"						movie width
 * 		"height" : "272px" 						movie height
 * 		"path" : "./sites/default/images/video" movies path
 * 		"supplied" : "m4v,ogv,webmv"			movie available formats
 * 		"name" : "video-09"						movie name (all formats and poster must hanve the same name; poster must have JPG extension)
 * 		"text" : "some text"					this text will be used as the title of the fancybox
 * 		"html" : "some escaped html"			This html will be rendered on screen if fancybox is activated
 * }
 * 	</div>										end of div tag
 */

(function($){
	$.fn.playVideo = function(swfPath, abslPath)
	{
		var index = 1;
		$.each($(this), function(k, el){
			//set variables
			var opts = $.parseJSON($(el).text());
			$(el).html('');
			
			opts.tmpId = 'tmp' + new Date().getTime() + Math.floor(Math.random()*100); 
			
			// chack if fancybox must be used
			if ($(el).hasClass('fancy'))
				{
					var fancy_flag = true;
				}
			// make array of supplied
			if (opts.supplied.indexOf(',') < 0)
				{
				var supplied_arr = [];
				supplied_arr.push(supplied);
				}
			else
				{
				var supplied_arr = opts.supplied.split(',');
				}
			
			// set media object
			var media = {};
			$.each(supplied_arr, function(kk, vv){
				media[vv] = (abslPath ? abslPath : window.location.protocol + '//' + window.location.hostname ) + '/' + opts.path + '/' + opts.name + '.' + ( (vv=='webmv') ? 'webm' : vv);
			});
			media.poster = opts.path + '/' + opts.name + '.jpg';
			
			//write div placeholder
			var div = $('<div id="' + opts.tmpId + '" />');
			
			if (fancy_flag)
				{
				var a = $('<a></a>').attr('title', opts.text).attr('href', '#' + opts.tmpId);
				if (opts.html)
					{
					a.html(opts.html);
					}
				var hiddendiv = $('<div style="display:none" />').append(div);
				
				$(el).append(a).append(hiddendiv);	
				}
			else
				{
				$(el).append(div);
				}
			
			
			// write html for jplayer
			var html = '<div id="jp_container_' + index + '" class="jp-video jp-video-360p">'
					+ '<div class="jp-type-single">'
						+ '<div id="jquery_jplayer_' + index + '" class="jp-jplayer"></div>'
						+ '<div class="jp-gui">'
						+ '<div class="jp-video-play"><a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a></div>'
						+ '<div class="jp-interface">'
							+ '<div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div>'
							+ '<div class="jp-current-time"></div>'
							+ '<div class="jp-duration"></div>'
							+ '<div class="jp-controls-holder">'
								+ '<ul class="jp-controls">'
									+ '<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>'
									+ '<li><a href="javascript:;" class="jp-pause" tabindex="1">pausa</a></li>'
									+ '<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>'
									+ '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="muto">muto</a></li>'
									+ '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="voce">voce</a></li>'
								+ '</ul>'
								+ '<div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div>'
							+ '</div>'
						+ '</div>'
					+ '</div>'
					+ '<div class="jp-no-solution"><span>È necessario un aggiornamento</span>Per vedere questo video è necesario aggiornare il browser ad una versione più recente oppure aggiornare il vostro <a href="http://get.adobe.com/flashplayer/" target="_blank" data-test="ciao">Flash plugin</a>.</div>'
				+ '</div>'
			+ '</div>';
			
			// set html to div placeholder
			$('#' + opts.tmpId).html(html);
			
			// format options
			var jp_supplied = [];
			$.each(media, function(key, value){
				if (key != 'poster')
					jp_supplied.push(key);
			});
			
			// start jquery.jPlayer
			$('#jquery_jplayer_' + index).jPlayer({
				ready: function () {
					$(this).jPlayer("setMedia", media);
				},
				swfPath: swfPath,
				supplied: jp_supplied.join(', '),
				//solution:"flash, html",
				size: {
					width: opts.width,
					height: opts.height
				},
				cssSelectorAncestor: '#jp_container_' + index
			});
			
			// add 1 to index
			index++;
			
			// start fancybox
			if (fancy_flag)
				{
				a.fancybox();
				}
		});
	};
})(jQuery);