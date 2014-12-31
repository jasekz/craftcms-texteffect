CONVENTIONS

Paths: 
    resources/<plugin>/
    resources/<plugin>/js/
    resources/<plugin>/config.json
    resources/<plugin>/run.js
    
run.js:
<plugin> = {
        run: function(objId){
            var obj = jQuery('#'+objId);
            // do what you need to here to run the plugin
        }
}

config.json:
{
    "scripts": ["js/typed.js", "js/some-other-important-script.js"]
}