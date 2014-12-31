var wordtypeerase = {
		run: function(objId){			
			var obj = jQuery('#'+objId),
				strings = obj.attr('content').split('|'),
				highlightColor = obj.attr('highlightColor'),
				highlightColor = highlightColor === undefined || highlightColor == '' ? '#999999' : highlightColor;
			if(strings.length<1){
				return false;
			}
			jQuery('head').append('<style type="text/css">.highlight{background:'+highlightColor+';}</style>');
			obj.html(strings[0]);
			if(strings.length>1){
				strings.splice(0, 1);
				var typeOvers = strings.join();
				obj.attr('data-type-words', typeOvers);
			}
			obj.wordTypeErase(this.getSettings(obj));
		},
		getSettings: function(obj){
			settings = {
					delayOfStart: obj.attr('delayOfStart') === undefined ? 1000: parseInt(obj.attr('delayOfStart')),  // delay before typing begins
				    letterSpeed: obj.attr('letterSpeed') === undefined ? 150: parseInt(obj.attr('letterSpeed')), // delay between letters typed
				    highlightSpeed: obj.attr('highlightSpeed') === undefined ? 10: parseInt(obj.attr('highlightSpeed')), // how fast to highlight
				    delayOfWords: obj.attr('delayOfWords') === undefined ? 1600: parseInt(obj.attr('delayOfWords')), // delay between words being typed
				    naturalTypingSpeed: obj.attr('naturalTypingSpeed') === undefined || obj.attr('naturalTypingSpeed') != '1' ? false: true,// randomizes the delay between letters to simulate a more natural typing
				    loop: obj.attr('doLoop') === undefined || obj.attr('doLoop') == '1' ? true:false,// whether the animation should be restarted at the end
				    delayBetweenLoop: obj.attr('delayBetweenLoop') === undefined ? 1000: parseInt(obj.attr('delayBetweenLoop')), // delay between loop
			};				
			return settings;
		}
}