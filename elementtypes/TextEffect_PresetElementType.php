<?php
namespace Craft;

/**
 * TextEffect preset element type
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffect_PresetElementType extends BaseElementType
{

    /**
     * Returns the component’s name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Text Effect Presets');
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public function hasContent()
    {
        return true;
    }

    /**
     * Returns whether this element type can have statuses.
     *
     * @return bool
     */
    public function hasStatuses()
    {
        return true;
    }

    /**
     * Returns whether this element type stores data on a per-locale basis.
     *
     * @return bool
     */
    public function isLocalized()
    {
        return false;
    }

    /**
     * Returns this element type's sources.
     *
     * @param string|null $context            
     * @return array false
     */
    public function getSources($context = null)
    {
        $sources = array(
            '*' => array(
                'label' => Craft::t('All Presets'),
                'criteria' => array(
                    'source' => '*'
                )
            )
        );
        
        if ($installedPlugins = craft()->textEffect->getPlugins()) {
            foreach ($installedPlugins as $plugin) {
                $sources[$plugin['name']] = array(
                    'label' => Craft::t($plugin['label']),
                    'criteria' => array(
                        'source' => $plugin['name']
                    )
                );
            }
        }
        
        return $sources;
    }

    /**
     * Returns all of the possible statuses that elements of this type may have.
     *
     * @return array null
     */
    public function getStatuses()
    {
        return array(
            EntryModel::LIVE => Craft::t('Enabled'),
            EntryModel::EXPIRED => Craft::t('Disabled')
        );
    }

    /**
     * Defines which model attributes should be searchable.
     *
     * @return array
     */
    public function defineSearchableAttributes()
    {
        return array(
            'title',
            'plugin'
        );
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source            
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        return array(
            'title' => Craft::t('Title'),
            'plugin' => Craft::t('Plugin')
        );
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return array(
            'title' => AttributeType::String,
            'order' => array(
                AttributeType::String,
                'default' => 'texteffect_presets.title asc'
            ),
            'status' => array(
                AttributeType::String,
                'default' => EntryModel::LIVE
            )
        );
    }

    /**
     * Modifies an element query targeting elements of this type.
     *
     * @param DbCommand $query            
     * @param ElementCriteriaModel $criteria            
     * @return null false
     */
    public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
    {
        $fields = [
            'texteffect_presets.title',
            'texteffect_presets.plugin',
            'texteffect_presets.options',
            'texteffect_presets.notes'
        ];
        
        $query->addSelect(implode(', ', $fields))->join('texteffect_presets texteffect_presets', 'texteffect_presets.id = elements.id');
        
        if (isset($criteria->source) && $criteria->source)
            $query->andWhere(DbHelper::parseParam('texteffect_presets.plugin', $criteria->source, $query->params));
    }

    /**
     * Returns the element query condition for a custom status criteria.
     *
     * @param DbCommand $query
     *            The database query.
     * @param string $status
     *            The custom status.
     * @return string false
     */
    public function getElementQueryStatusCondition(DbCommand $query, $status)
    {
        switch ($status) {
            case EntryModel::LIVE:
                return array(
                    'and',
                    'elements.enabled = 1'
                );
            
            case EntryModel::EXPIRED:
                return array(
                    'and',
                    'elements.enabled = 0'
                );
        }
    }

    /**
     * Populates an element model based on a query result.
     *
     * @param array $row            
     * @return BaseModel
     */
    public function populateElementModel($row)
    {
        return TextEffect_PresetModel::populateModel($row);
    }

    /**
     * Returns the HTML that should be shown for a given element’s attribute in Table View.
     *
     * @param BaseElementModel $element
     *            The element.
     * @param string $attribute
     *            The attribute name.
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        switch ($attribute) {
            case 'plugin':
                if ($installedPlugins = craft()->textEffect->getPlugins())
                    if (array_key_exists($element->{$attribute}, $installedPlugins))
                        return $installedPlugins[$element->{$attribute}]['label'];
            default:
                return parent::getTableAttributeHtml($element, $attribute);
        }
    }
}
