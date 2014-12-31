<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

/**
 * TextEffect twig extension
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffectTwigExtension extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Text Effect';
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'optionField' => new Twig_Filter_Method($this, 'optionFieldFilter', array(
                'is_safe' => array(
                    'html'
                )
            ))
        );
    }

    /**
     * Returns option template, if available.
     *
     * @param array $args            
     * @return boolean
     */
    public function optionFieldFilter(array $args, $preset = null)
    {
        if (! isset($args['type']))
            return false;
        
        $options = @json_decode($preset->options, true);
        if (isset($options[$args['name']])) {
            switch ($args['type']) {
                case 'lightswitch':
                    $args['on'] = $options[$args['name']] ? true : false;
                    break;
                default:
                    $args['value'] = $options[$args['name']];
                    break;
            }
        }
        
        try {
            $args['input'] = craft()->templates->render('_includes/forms/' . strtolower($args['type']), $args);
            return craft()->templates->render('_includes/forms/field', $args);
        } catch (\Exception $e) {
            return false;
        }
    }
}
