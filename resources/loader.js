jQuery(document).ready(function(){
	TextEffect.init();
})

TextEffect = {
	installed:[],
	init: function(){	
		jQuery('texteffect').each(function(){
			var te = jQuery(this);
			TextEffect.loadPlugin(te);
		});
	},
	loadPlugin: function(obj){
		obj.attr('id', new Date().getTime());
		var cmd = obj.attr('plugin') + '.run('+obj.attr('id')+')';
		try{
			(new Function(cmd))();
		}catch(err){
			// console.log('Caught exception: '+err);
		}
	}
}
