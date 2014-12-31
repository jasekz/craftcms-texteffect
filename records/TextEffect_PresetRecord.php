<?php
namespace Craft;

/**
 * Class TextEffect_PresetRecord
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 */
class TextEffect_PresetRecord extends BaseRecord
{

    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public function getTableName()
    {
        return 'texteffect_presets';
    }

    /**
     * Defines this model's relations to other models.
     *
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'element' => array(
                static::BELONGS_TO,
                'ElementRecord',
                'id',
                'required' => true,
                'onDelete' => static::CASCADE
            )
        );
    }

    /**
     * Defines this model's database table indexes.
     *
     * @return array
     */
    public function defineIndexes()
    {
        return array(
            array(
                'columns' => array(
                    'title'
                ),
                'unique' => true
            )
        );
    }

    /**
     * Defines this model's attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'title' => array(
                AttributeType::String,
                'required' => true
            ),
            'notes' => array(
                AttributeType::String
            ),
            'plugin' => array(
                AttributeType::String,
                'required' => true
            ),
            'options' => array(
                AttributeType::String
            )
        );
    }
}
