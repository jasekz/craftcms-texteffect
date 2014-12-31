var arctext = {
		run: function(objId){	
			var obj = jQuery('#'+objId);
			obj.html(obj.attr('content'));
			obj.arctext(this.getSettings(obj));
		},
		getSettings: function(obj){
			settings = {
				radius: obj.attr('radius'),
				rotate: obj.attr('rotate') === undefined || obj.attr('rotate') == 'false' ? false: true,
				animation: obj.attr('animation'),
				dir: obj.attr('dir')
			};
				
			return settings;
		}
}