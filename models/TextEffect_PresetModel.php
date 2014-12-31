<?php
namespace Craft;

/**
 * TextEffect preset model
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffect_PresetModel extends BaseElementModel
{

    /**
     * Element type
     *
     * @var string
     */
    protected $elementType = 'TextEffect_Preset';

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string false
     */
    public function getCpEditUrl()
    {
        $url = UrlHelper::getCpUrl('texteffect/' . $this->id);
        return $url;
    }

    /**
     * Returns model attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'title' => AttributeType::String,
            'enabled' => AttributeType::Number,
            'notes' => AttributeType::String,
            'plugin' => AttributeType::String,
            'options' => AttributeType::String
        ));
    }

    /**
     * Returns the element's status.
     *
     * @return string null
     */
    public function getStatus()
    {
        if (parent::getStatus() == 'disabled')
            return 'expired'; // red dot
        
        return 'live'; // green dot
    }
}
