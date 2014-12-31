<?php
namespace Craft;

/**
 * TextEffect
 * Simplifies adding fun effects to any text.
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @version 1.0
 * @since Craft 2.2
 * @license http://opensource.org/licenses/MIT
 *         
 */
class TextEffectPlugin extends BasePlugin
{

    /**
     * Initializes the plugin.
     *
     * @return void
     */
    public function init()
    {}

    /**
     * Returns the plugins’s name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Text Effect Presets';
    }

    /**
     * Returns the plugin’s version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * Returns whether this plugin has its own section in the CP.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Returns the plugin developer's name.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Absolute Web Solutions';
    }

    /**
     * Returns the plugin developer's URL.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://absoluteweb.solutions';
    }

    /**
     * Sets up plugin routing.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'texteffect' => array(
                'action' => 'textEffect/listPresets'
            ),
            'texteffect/(?P<texteffectPlugin>{handle})/new' => array(
                'action' => 'textEffect/createPreset'
            ),
            'texteffect/(?P<texteffectPresetId>\d+)' => array(
                'action' => 'textEffect/editPreset'
            )
        );
    }

    /**
     * Registers plugin's custom Twig extension
     *
     * @return \Craft\TextEffectTwigExtension
     */
    public function addTwigExtension()
    {
        Craft::import('plugins.texteffect.twigextensions.TextEffectTwigExtension');
        
        return new TextEffectTwigExtension();
    }
}
