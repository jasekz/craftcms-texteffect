var typedjs = {
		run: function(objId){			
			var obj = jQuery('#'+objId);
			obj.typed(this.getSettings(obj));
			jQuery('head').append('<style type="text/css">'+
			'.typed-cursor {-webkit-animation: 1s blink step-end infinite;-moz-animation: 1s blink step-end infinite;-ms-animation: 1s blink step-end infinite;-o-animation: 1s blink step-end infinite;animation: 1s blink step-end infinite;}'+
			'@keyframes "blink"{from,to {color: transparent;}50% {color: black;}}@-moz-keyframes blink {from,to {color: transparent;}'+
			'50% {color: black;}}@-webkit-keyframes "blink" {from,to {color: transparent;}50% {color: black;}}@-ms-keyframes "blink" {from,to {color: transparent;}'+
			'50% {color: black;}}@-o-keyframes "blink" {from,to {color: transparent;}50% {color: black;}}'+
			'</style>');
		},
		getSettings: function(obj){
			settings = {
		        strings: obj.attr('content').split('|'),
		        typeSpeed: obj.attr('typeSpeed') === undefined ? 0: parseInt(obj.attr('typeSpeed')), // typing speed
		        startDelay: obj.attr('startDelay') === undefined ? 0: parseInt(obj.attr('startDelay')), // time before typing starts
		        backSpeed: obj.attr('backSpeed') === undefined ? 0: parseInt(obj.attr('backSpeed')), // backspacing speed
		        backDelay: obj.attr('backDelay') === undefined ? 500: parseInt(obj.attr('backDelay')), // time before backspacing
		        loop: obj.attr('doLoop') === undefined || obj.attr('doLoop') == 'false' ? false: true, // loop
		        loopCount: obj.attr('loopCount') === undefined ? false: parseInt(obj.attr('loopCount')), // false = infinite
		        showCursor: obj.attr('showCursor') === undefined || obj.attr('showCursor') == 'false' ? false: true, // show cursor
		        cursorChar: obj.attr('cursorChar') === undefined ? '|': obj.attr('cursorChar'), // character for cursor
		        attr: obj.attr('attr') === undefined ? null: obj.attr('attr'), // attribute to type (null == text)
		        contentType: obj.attr('contentType') === undefined ? 'text': obj.attr('contentType'), // either html or text
			};

			if(obj.attr('callback'))
				settings.callback = function(){(new Function(obj.attr('callback')))()};
			if(obj.attr('preStringTyped'))
				settings.preStringTyped = function(){(new Function(obj.attr('preStringTyped')))()};
			if(obj.attr('onStringTyped'))
				settings.onStringTyped = function(){(new Function(obj.attr('onStringTyped')))()};
			if(obj.attr('resetCallback'))
				settings.resetCallback = function(){(new Function(obj.attr('resetCallback')))()};
				
			return settings;
		}
}