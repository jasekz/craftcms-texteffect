<?php
namespace Craft;

/**
 * TextEffect variable
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffectVariable
{

    /**
     * Status on whether the main TextEffect JavaScript has been loaded.
     *
     * @var bool
     */
    static $initialized = false;

    /**
     * List of plugins whose JavaScript files have been loaded along with their config files.
     *
     * @example pluginName => configFile (associative array from the plugin's config.json)
     * @var array
     */
    static $loadedPlugins = array();

    /**
     * Returns the hml markup necessary to run a plugin.
     * Usage: {{
     * craft.texteffect.run({
     * 'plugin': 'typed',
     * 'content': ["Hello there.", "It's working!!!!"]
     * })
     * }}
     *
     * @param array $options            
     * @return \Twig_Markup
     */
    public function run($options = array())
    {
        if (isset($options['preset'])) {
            if (! $options['preset'] = craft()->textEffect->getPresetByTitle($options['preset']))
                return false;
            
            $options['plugin'] = $options['preset']->plugin;
        } elseif (! isset($options['plugin'])){
            return false;
        }
        
        return new \Twig_Markup($this->mainLoader() . $this->pluginLoader($options) . $this->pluginMarkup($options), craft()->templates->getTwig()->getCharset());
    }

    /**
     * Returns all installed plugins.
     *
     * @return array
     */
    public function getPlugins()
    {
        return craft()->textEffect->getPlugins();
    }

    /**
     * Returns configuration array for specified plugin.
     *
     * @param string $pluginName            
     * @return null array
     */
    public function getPluginConfig($pluginName)
    {
        if (array_key_exists($pluginName, self::$loadedPlugins))
            return self::$loadedPlugins[$pluginName];
        
        self::$loadedPlugins[$pluginName] = craft()->textEffect->getPluginConfig($pluginName);
        
        return self::$loadedPlugins[$pluginName];
    }

    /**
     * Returns html markup for specified plugin.
     *
     * @param array $options            
     * @return string
     */
    private function pluginMarkup($options)
    {
        if (! array_key_exists($options['plugin'], self::$loadedPlugins) || ! self::$loadedPlugins[$options['plugin']])
            return false;
        
        $attributes = array();
        
        if (isset($options['preset']) && $opts = @json_decode($options['preset']->options)) {
            foreach ($opts as $key => $option) {
                if (! $option)
                    continue;
                
                if (is_array($option))
                    $attributes[$key] = $key . '="' . implode('|', $option) . '"';
                else
                    $attributes[$key] = $key . '="' . $option . '"';
            }
        }
        
        foreach ($options as $key => $option) {
            if (! $option)
                continue;
            
            if (is_array($option))
                $attributes[$key] = $key . '="' . implode('|', $option) . '"';
            else
                $attributes[$key] = $key . '="' . $option . '"';
        }
        
        return '<texteffect ' . implode(' ', $attributes) . '></texteffect>';
    }

    /**
     * Loads plugin JavaScript file(s).
     *
     * @param array $options            
     * @return boolean string
     */
    private function pluginLoader($options)
    {
        if (array_key_exists($options['plugin'], self::$loadedPlugins))
            return false;
        
        self::$loadedPlugins[$options['plugin']] = craft()->textEffect->getPluginConfig($options['plugin']);
        
        if (! self::$loadedPlugins[$options['plugin']])
            return false;
        
        $pluginScripts = array();
        foreach (self::$loadedPlugins[$options['plugin']]['scripts'] as $script) {
            $pluginScripts[] = craft()->templates->includeJsResource('texteffect/' . $options['plugin'] . '/' . $script);
        }
        
        return implode('', $pluginScripts) . craft()->templates->includeJsResource('texteffect/' . $options['plugin'] . '/run.min.js');
    }

    /**
     * Loads main TextEffect JavaScript file.
     *
     * @return boolean string
     */
    private function mainLoader()
    {
        if (self::$initialized)
            return false;
        
        self::$initialized = true;
        
        return craft()->templates->includeJsResource('texteffect/loader.min.js');
    }
}