# TextEffect plugin for Craft CMS

This plugin allows you to have some fun with text effects on your site.

## Installation

To install TextEffect, follow these steps:

1.  Upload the `texteffect/` folder to your `craft/plugins/` folder.
2.  Go to `Settings > Plugins` from your Craft control panel and enable the TextEffect plugin.
3.  Click on “TextEffect Presets” to go to the plugin’s presets page, and start adding presets.

# Creating a Preset
Creating a new preset is simple.  To do so, simply click on the "TextEffects Presets" and add a new preset.  Each preset type has its own configuration, but you can leave all the preset fields blank if you'd like to get started with the pre-programmed defaults.

**NOTE:** When creating a preset, the title will by 'slugified'.  This will be the field you will use to specify the ` preset ` in the template.

## Usage
First, for the included plugins to work, you must include jQuery.  If you don't use jQuery in your site, you can embed the following:
 ```
 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 ```

After creating a preset, the most basic way to output in a template is like this:

```
{{ craft.texteffect.run({
    'content': [
        "This is the word type erase effect.", 
        "And now we type over it.", 
        "And type over again."],
    'preset': 'my-new-preset'}) }}
```
` content ` is the only required field and should be an array of strings.  ` preset ` is optional, however, if not present, you will need to provide the ` plugin ` field and any options you may want to use for that plugin.  For example, you can do the following to use the "ArcText" plugin:
```
{{ craft.texteffect.run({
    'plugin': 'arctext',
    'content': ["Arc this text."],
    'radius': 100}) }}
```

Or if you prefer to start off with a generic preset and then tweak it in the template:
```
{{ craft.texteffect.run({
    'content': [
        "This is the word type erase effect.", 
        "And now we type over it.", 
        "And type over again."],
    'preset': 'my-new-preset',
    'typeSpeed': '10',
    'doLoop': 'false' }) }}
```
## Included Plugins
The following plugins are included with TextEffect.  You can follow the provided urls for an in depth description of the plugin and available options.  If you have one of your own you'd like to add, see the section below, **Adding Your Own Plugins**.
##### Typed.js 
Plugin page: http://www.mattboldt.com/demos/typed-js/

##### Arctext.js
Plugin page: http://tympanus.net/codrops/2012/01/24/arctext-js-curving-text-with-css3-and-jquery/

##### WordTypeErase
Plugin page: https://github.com/benrlodge/wordTypeErase

## Adding Your Own Plugins
To add your own plugins, it's a rather straight forward process and does not involve any modification to the TextEffect code.  You simply add your plugin to the ` plugins/texteffect/resources ` directory, add a ` config.json ` file, create a ` run.js ` file, and you're on your way.  The following structure must be adhered to:
```
  texteffect
   |_ resources
     |_ myawesomeplugin // name of your plugin
       |_js // this can be anything and is defined in your config.json file
         |_myawesomeplugin.js
       |_run.js // required
       |_config.json // required
```
And the corresponding ` config.json ` file:
```
{
    "name": "myawesomeplugin", // should be the same as your plugin directory name
    "label": "My Awesome Plugin", // for display in the Craft views
    "url": "http://docs.to.myawesomeplugin.com", // this will be the link in the preset options section
    "installed": true, // if false, plugin will be disabled
    "scripts": ["js/myawesomeplugin.js"], // plugin scripts
    "optionFields": [
        { // this takes the same params as a Craft field
            "type": "text",
            "name": "speed",
            "label": "Speed",
            "instructions": "How fast is it?"
        },
        { // so does this
            "type": "lightswitch",
            "name": "lightup",
            "label": "Light Up",
            "instructions": "You wanna light it up?"
        },
        { // and this too
            "type": "select",
            "name": "repeat",
            "label": "Repeat",
            "options": [{"label":"No", "value":"0"},{"label":"Yes", "value":"1"},{"label":"Maybe", "value":"2"}],
            "instructions": "Should we repeat this?"
        }
    ]
}
```
And one more, the ` run.js ` file:
```
var myawesomeplugin = { // should be the same as your plugin directory name and config.json 'name' field
        /*
        * This is the only method the MUST be implemented, however, implementation is up to you.
        */
        run: function(objId){   // objId is a unique id for the html element tied to this plugin instance
            var obj = jQuery('#'+objId);
            obj.html(obj.attr('content'));
            obj.myawesomeplugin(this.getSettings(obj));
        },
        /*
        * This is specific to this plugin and is totally optional for your plugin.
        */
        getSettings: function(obj){
            return {
                speed: obj.attr('speed'),
                lightup: obj.attr('lightup'),
                repeat: obj.attr('repeat'),
            };
        }
}
```

If you'd like to see more details on how to do this, please see one of the included plugins in ` plugins/texteffect/resources `.

## Changelog

### 1.0

* Initial release
