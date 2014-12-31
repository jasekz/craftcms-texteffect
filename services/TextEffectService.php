<?php
namespace Craft;

/**
 * TextEffect service
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffectService extends BaseApplicationComponent
{

    /**
     * Installed plugin configs cache.
     *
     * @var array
     */
    public static $pluginConfigFiles = array();

    /**
     * JavaScript plugins directory.
     *
     * @var string
     */
    private $pluginsDir = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->pluginsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
    }

    /**
     * Returns plugin config.
     *
     * @param string $pluginName            
     * @return boolean array
     */
    public function getPluginConfig($pluginName)
    {
        if (isset(self::$pluginConfigFiles[$pluginName]) && self::$pluginConfigFiles[$pluginName])
            return self::$pluginConfigFiles[$pluginName];
        
        $configFile = $this->pluginsDir . $pluginName . DIRECTORY_SEPARATOR . 'config.json';
        
        self::$pluginConfigFiles[$pluginName] = false;
        
        if (! file_exists($configFile))
            return false;
        
        if (! $config = json_decode(file_get_contents($configFile), true))
            return false;
        
        if (! isset($config['scripts']) || ! $config['scripts'])
            return false;
        
        if (! isset($config['installed']) || $config['installed'] != true)
            return false;
        
        self::$pluginConfigFiles[$pluginName] = $config;
        
        return self::$pluginConfigFiles[$pluginName];
    }

    /**
     * Returns all installed plugins.
     *
     * @return array
     */
    public function getPlugins()
    {
        $ignore = array(
            '.',
            '..'
        );
        $plugins = array();
        foreach (scandir($this->pluginsDir) as $file) {
            if (! is_dir($this->pluginsDir . $file) || in_array($file, $ignore))
                continue;
            
            $config = $this->getPluginConfig($file);
            
            if (! isset($config['name']) || ! $config['name'] || ! isset($config['label']) || ! $config['label'])
                continue;
            
            $plugins[$file] = $config;
        }
        
        return $plugins;
    }

    /**
     * Returns preset.
     *
     * @param int $presetId            
     */
    public function getPreset($presetId)
    {
        return craft()->elements->getElementById($presetId);
    }

    /**
     * Returns preset.
     *
     * @param int $presetTitle            
     */
    public function getPresetByTitle($presetTitle)
    {
        $criteria = craft()->elements->getCriteria('TextEffect_Preset');
        $criteria->first(array(
            'slug' => $presetTitle
        ));
        
        if (isset($criteria[0]) && $criteria[0])
            return $criteria[0];
        
        return false;
    }

    /**
     * Deletes preset.
     *
     * @param int $presetId            
     * @return boolean
     */
    public function deletePreset($presetId)
    {
        if (! $presetId)
            return false;
        
        return craft()->elements->deleteElementById($presetId);
    }

    /**
     * Saves a preset.
     *
     * @param TextEffect_PresetModel $model            
     * @throws Exception
     * @return boolean
     */
    public function savePreset(TextEffect_PresetModel $model)
    {
        $isNew = ! $model->id;
        
        if ($isNew)
            $record = new TextEffect_PresetRecord();
        
        else {
            $record = TextEffect_PresetRecord::model()->findById($model->id);
            
            if (! $record)
                throw new Exception(Craft::t('No preset exists with the ID "{id}"', [
                    'id' => $model->id
                ]));
        }
        
        if (! $this->getPluginConfig($model->plugin))
            throw new Exception(Craft::t('Plugin "{plugin}" is not on the list of installed plugins.', [
                'plugin' => $model->plugin
            ]));
        
        $record->setAttribute('title', ElementHelper::createSlug($model->title));
        $record->setAttribute('notes', $model->notes);
        $record->setAttribute('options', $model->options);
        
        if ($isNew)
            $record->setAttribute('plugin', $model->plugin);
        
        $record->validate();
        $model->addErrors($record->getErrors());
        
        if ($model->hasErrors())
            throw new Exception(Craft::t('Please correct the errors below.'));
        
        try {
            if (! craft()->elements->saveElement($model))
                throw new Exception(Craft::t('Error saving preset element.'));
            
            if ($isNew)
                $record->id = $model->id;
            
            $record->save(false);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
