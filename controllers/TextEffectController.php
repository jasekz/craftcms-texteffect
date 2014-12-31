<?php
namespace Craft;

/**
 * TextEffect controller
 *
 * @package TextEffect
 * @author Zbigniew Jasek <zig@absoluteweb.solutions>
 * @since Craft 2.2
 *       
 */
class TextEffectController extends BaseController
{

    /**
     * Displays the preset list.
     *
     * @return void
     */
    public function actionListPresets()
    {
        $this->renderTemplate('texteffect/presets/_index');
    }

    /**
     * Displays the 'create new preset' screen.
     *
     * @param array $variables            
     * @return void
     */
    public function actionCreatePreset(array $variables)
    {
        $variables['config'] = craft()->textEffect->getPluginConfig($variables['texteffectPlugin']);
        $this->renderTemplate('texteffect/presets/_edit', $variables);
    }

    /**
     * Displays the 'edit preset' screen.
     *
     * @param array $variables            
     * @return void
     */
    public function actionEditPreset(array $variables = [])
    {
        if (! isset($variables['preset']) || ! $variables['preset'])
            $variables['preset'] = craft()->textEffect->getPreset($variables['texteffectPresetId']);
        
        $variables['config'] = craft()->textEffect->getPluginConfig($variables['preset']->plugin);
        $variables['texteffectPlugin'] = $variables['preset']->plugin;
        $this->renderTemplate('texteffect/presets/_edit', $variables);
    }

    /**
     * Saves a preset.
     *
     * @return void
     */
    public function actionSavePreset()
    {
        $this->requireLogin();
        $this->requirePostRequest();
        
        $preset = TextEffect_PresetModel::populateModel(craft()->request->getPost());
        $preset->enabled = craft()->request->getPost('enabled') ? 1 : 0;
        $preset->options = $this->parseOptions($preset);
        
        try {
            craft()->textEffect->savePreset($preset);
            craft()->userSession->setNotice(Craft::t('Preset saved.'));
            $this->redirectToPostedUrl($preset);
        } catch (\Exception $e) {
            craft()->userSession->setError(Craft::t($e->getMessage()));
            craft()->urlManager->setRouteVariables(array(
                'preset' => $preset
            ));
        }
    }

    /**
     * Deletes a preset.
     *
     * @return void
     */
    public function actionDeletePreset()
    {
        $this->requirePostRequest();
        $preset = craft()->textEffect->getPreset(craft()->request->getRequiredPost('id'));
        
        if (craft()->textEffect->deletePreset($preset->id)) {
            if (craft()->request->isAjaxRequest())
                $this->returnJson(array(
                    'success' => true
                ));
            
            else {
                craft()->userSession->setNotice(Craft::t('Preset deleted.'));
                $this->redirectToPostedUrl($preset);
            }
        } else {
            if (craft()->request->isAjaxRequest())
                $this->returnJson(array(
                    'success' => false
                ));
            
            else {
                craft()->userSession->setError(Craft::t('Couldn’t delete preset.'));
                
                // Send the entry back to the template
                craft()->urlManager->setRouteVariables(array(
                    'preset' => $preset
                ));
            }
        }
    }

    /**
     * Parses plugin options.
     *
     * @param TextEffect_PresetModel $model            
     * @return string
     */
    private function parseOptions(TextEffect_PresetModel $model)
    {
        $ignore = $model->getAttributes() + array(
            'redirect' => null,
            'action' => null
        );
        $options = array();
        foreach (craft()->request->getPost() as $key => $value) {
            if (array_key_exists($key, $ignore))
                continue;
            
            $options[$key] = $value;
        }
        
        return json_encode($options, true);
    }
}