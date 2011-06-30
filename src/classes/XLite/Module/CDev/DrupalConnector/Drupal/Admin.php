<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Admin
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Admin extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Available block types
     */
    const BLOCK_TYPE_REGULAR   = 'regular';
    const BLOCK_TYPE_LC_WIDGET = 'lc_widget';


    /**
     * Translation tables for (<litecommerce> => <drupal>) field types
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $fieldTypesTranslationTable = array(
        'string'   => 'textfield',
        'integer'  => 'textfield',
        'checkbox' => 'checkbox',
        'list'     => 'select',
    );


    // {{{ Auxiliary methods

    /**
     * Return list of available block types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBlockTypes()
    {
        return array(
            self::BLOCK_TYPE_REGULAR   => t('Regular'),
            self::BLOCK_TYPE_LC_WIDGET => t('LiteCommerce widget'),
        );
    }

    /**
     * Check if current block is an LC one
     *
     * @param array  $data  Form state
     * @param string $field Field in data array to retrieve field OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isLCBlock(array $data, $field = 'values')
    {
        return !empty($data[$field]['lc_block_type']) && self::BLOCK_TYPE_LC_WIDGET === $data[$field]['lc_block_type'];
    }

    /**
     * Check if it's the "Add new" form
     *
     * @param array $form Form description
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isNewBlock(array $form)
    {
        return empty($form['delta']['#value']);
    }

    /**
     * Check if block exists in "block_custom" table
     *
     * @param integer $delta Block unique identifier
     * @param string  $info  Block description
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCustomBlock($delta, $info)
    {
        return (bool) db_query_range(
            'SELECT 1 FROM {block_custom} WHERE bid = :bid AND info = :info',
            0,
            1,
            array(
                ':bid'  => $delta,
                ':info' => $info,
            )
        )->fetchField();
    }

    /**
     * Return block description
     *
     * @param integer $delta Block ID
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBlock($delta)
    {
        return \XLite\Module\CDev\DrupalConnector\Drupal\Model::getInstance()->getBlock($delta);
    }

    /**
     * Get block by info retrieved from the form
     *
     * @param array $form Form description
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormBlock(array $form)
    {
        return !$this->isNewBlock($form) && ($block = $this->getBlock($form['delta']['#value'])) ? $block : null;
    }

    /**
     * Return default value for the "Block type" select box
     *
     * @param array $form Form description
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultBlockType(array $form)
    {
        return $this->getFormBlock($form) ? self::BLOCK_TYPE_LC_WIDGET : self::BLOCK_TYPE_REGULAR;
    }

    /**
     * Return default value for the "Widget type" select box
     *
     * @param array $form Form description
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultWidgetClass(array $form)
    {
        return ($block = $this->getFormBlock($form)) ? $block['lc_class'] : '';
    }

    /**
     * Add "LC widget settings" fieldset for the form
     *
     * :FIXME: to revize
     *
     * @param array  &$form     Form description
     * @param string $class     LC widget class
     * @param string $label     Widget class readable name
     * @param array  $block     Block description OPTIONAL
     * @param array  $formInput Form input OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addSettingsFieldset(array &$form, $class, $label, array $block = array(), array $formInput = array())
    {
        // Get settings from LC
        $widget = $this->getHandler()->getWidget($class);
        if (isset($block['options']) && is_array($block['options'])) {
            $widget->setWidgetParams($block['options']);
        }

        // To prevent some unpredictable errors related to backslashes in element IDs
        $key = $this->getBlockName($class);
        if ($formInput && $block && isset($formInput['lc_widget']) && isset($formInput['lc_widget'][$key])) {
            $widget->setWidgetParams($formInput['lc_widget'][$key]);
        }

        $settings = $widget->getWidgetSettings();
        if ($settings) {

            $form[$key] = array(
                '#type'       => 'fieldset',
                '#title'      => 'Parameters',
                '#attributes' => array('id' => $key),
            );

            $extendedItemsList = is_subclass_of($widget->getProtectedWidget(), 'XLite\View\ItemsList\Product\Customer\ACustomer');

            // Translate native LC options into Drupal format
            foreach ($settings as $name => $param) {

                $form[$key][$name] = array(
                    '#type'          => $this->fieldTypesTranslationTable[$param->type],
                    '#title'         => t($param->label),
                    '#default_value' => isset($block['options'][$name]) ? $block['options'][$name] : $param->value,
                );

                if ('select' === $form[$key][$name]['#type']) {
                    $form[$key][$name]['#options'] = $param->options;

                } else {
                    if ($extendedItemsList && in_array($name, array(\XLite\View\ItemsList\Product\Customer\ACustomer::PARAM_ICON_MAX_WIDTH, \XLite\View\ItemsList\Product\Customer\ACustomer::PARAM_ICON_MAX_HEIGHT))) {
                        $form[$key][$name]['#description'] = t('recommended: !size', array('!size' => 110));
                    }
                    
                }
            }

            if ($extendedItemsList) {
                if (!isset($form['#attached'])) {
                    $form['#attached'] = array('js' => array());

                } elseif (!isset($form['#attached']['js'])) {
                    $form['#attached']['js'] = array();
                }

                $path = \XLite\Core\Layout::getInstance()->getResourceFullPath(
                    'modules/CDev/DrupalConnector/blocks.js',
                    \XLite::CUSTOMER_INTERFACE
                );
                $form['#attached']['js'][] = \XLite\View\AView::modifyResourcePath($path);

                // Display modes data
                $jsData = array(
                    \XLite\View\ItemsList\Product\Customer\ACustomer::WIDGET_TYPE_SIDEBAR => \XLite\View\ItemsList\Product\Customer\ACustomer::getSidebarDisplayModes(),
                    \XLite\View\ItemsList\Product\Customer\ACustomer::WIDGET_TYPE_CENTER  => \XLite\View\ItemsList\Product\Customer\ACustomer::getCenterDisplayModes(),
                );
                drupal_add_js('lcConnectorBlocks.' . $key . ' = ' . json_encode($jsData) . ';', 'inline');

                // Recommended icons sizes
                $jsData = \XLite\View\ItemsList\Product\Customer\ACustomer::getIconSizes();
                $lbl = t('recommended: !size');
                drupal_add_js(
                    'lcConnectorRecommendedIconSizes.' . $key . ' = ' . json_encode($jsData) . ';' . PHP_EOL
                    . 'var lcConnectorRecommendedLabel = \'' . $lbl . '\';',
                    'inline'
                );
            }
        }
    }

    /**
     * Ancillary method to get settings from form description
     *
     * @param string $class Current class name
     * @param array  $data  Form data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function extractWidgetSettings($class, array $data)
    {
        $block = $this->getBlockName($class);

        return !empty($data[$block]) ? $data[$block] : array();
    }

    /**
     * Method to modify LC widget settings in DB
     *
     * @param integer $blockId  Block delta
     * @param array   $settings Settings list OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateWidgetSettings($blockId, array $settings = array())
    {
        // Clear old settings
        db_delete('block_lc_widget_settings')
            ->condition('bid', $blockId)
            ->execute();

        // Save settings
        foreach ($settings as $name => $value) {
            db_insert('block_lc_widget_settings')
                ->fields(array('bid' => $blockId, 'name' => $name, 'value' => $value))
                ->execute();
        }
    }

    /**
     * Return name of block in Drupal for class in LC
     *
     * @param string $class Class name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBlockName($class)
    {
        return 'lc_block' . str_replace('\\', '_', $class);
    }

    // }}}

    // {{{ Hook handlers

    /**
     * Modify widget details form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function alterWidgetModifyForm(array &$form, array &$formState)
    {
        // Add fake content for LC blocks (on submit)
        if ($this->isLCBlock($formState, 'input')) {

            $form['settings']['body_field']['body']['#value'] = '____FROM_LC____';
        }

        $form = array(
            'lc_block_type' => array(
                '#type'          => 'select',
                '#title'         => t('Block type'),
                '#required'      => true,
                '#options'       => $this->getBlockTypes(),
                '#default_value' => variable_get('lc_block_type', $this->getDefaultBlockType($form)),
                '#attributes'    => $this->isNewBlock($form) ? array() : array('disabled' => 'disabled'),
            ),
        ) + $form;

        $form['settings']['lc_widget_details'] = array(
            'lc_class' => array(
                '#type'          => 'select',
                '#title'         => t('Widget type'),
                '#required'      => true,
                '#options'       => $this->getHandler()->getWidgetsList(),
                '#default_value' => ($actualClass = variable_get('lc_class', $this->getDefaultWidgetClass($form))),
            ),
        );

        $delta = isset($form['delta']['#value'])
            ? $form['delta']['#value']
            : null;

        $form['settings']['lc_widget_details']['lc_widget'] = array('#tree' => true);

        $lbl = t('recommended: !size');
        drupal_add_js(
            'var lcConnectorBlocks = {};' . PHP_EOL
            . 'var lcConnectorRecommendedIconSizes = {};' . PHP_EOL
            . 'var lcConnectorRecommendedLabel = \'' . $lbl . '\';',
            'inline'
        );

        foreach ($this->getHandler()->getWidgetsList() as $class => $label) {

            $this->addSettingsFieldset(
                $form['settings']['lc_widget_details']['lc_widget'],
                $class,
                $label,
                ($delta && ($class === $actualClass)) ? $this->getBlock($delta) : array(),
                isset($formState['input']) ? $formState['input'] : array()
            );
        }

        $form['settings']['lc_widget_details'] += array(
            '#type'  => 'fieldset',
            '#title' => t('LC widget details'),
        );

        $form['#validate'][] = 'lcConnectorValidateWidgetModifyForm';
        $form['#submit'][]   = 'lcConnectorSubmitWidgetModifyForm';
    }

    /**
     * Modify widget details form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function alterWidgetDeleteForm(array &$form, array &$formState)
    {
        $form['#submit'][] = 'lcConnectorSubmitWidgetDeleteForm';
    }

    /**
     * Validate widget details form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function validateWidgetModifyForm(array &$form, array &$formState)
    {
        $errors = array();

        // Check LC blocks only
        if ($this->isLCBlock($formState)) {

            // Short name
            $data = $formState['values'];

            // Get widget class (it's an LC class)
            $class = isset($data['lc_class']) ? $data['lc_class'] : null;

            // Check class
            if (empty($class) || !class_exists($class)) {

                $errors['lc_widget_details'] = t('Unknown LC widget class - "' . $class . '"');

            } else {

                // Check LC widget params
                $errors = $this->getHandler()->getWidget($class)
                    ->validateAttributes(
                        $this->extractWidgetSettings($class, $data['lc_widget'])
                    );
            }
        }

        // Set Drupal form errors
        foreach ($errors as $field => $error) {

            form_set_error($field, t($error));
        }
    }

    /**
     * Submit widget details form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitWidgetModifyForm(array &$form, array &$formState)
    {
        // Short names
        $data  = $formState['values'];
        $class = $data['lc_class'];
        $delta = $data['delta'];

        if (
            $this->isLCBlock($formState)
            && $this->isCustomBlock($delta, $data['info'])
        ) {

            // Set LC class field for block
            db_update('block_custom')
                ->fields(array('lc_class' => $class))
                ->condition('bid', $delta)
                ->execute();

            // Remove old and save new settings for widget
            $this->updateWidgetSettings(
                $delta,
                $this->extractWidgetSettings($class, $data['lc_widget'])
            );
        }
    }

    /**
     * Submit widget delete confirmation form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitWidgetDeleteForm(array &$form, array &$formState)
    {
        $this->updateWidgetSettings($formState['delta'], array());
    }

    /**
     * Alter user profile form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function alterUserProfileForm(array &$form, array &$formState)
    {
        array_unshift($form['#submit'], 'lcConnectorUserProfileFormSubmit');
    }

    /**
     * Alter user register form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function alterUserRegisterForm(array &$form, array &$formState)
    {
        array_unshift($form['#submit'], 'lcConnectorUserProfileFormSubmit');
    }

    /**
     * Handler to submit user profile/register form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitUserProfileForm(array &$form, array &$formState)
    {
        if (isset($formState['input']) && isset($formState['input']['pass']['pass1'])) {
            LCConnector_Handler::saveVariable('passwd', $formState['input']['pass']['pass1'], true);
        }
    }

    /**
     * Alter admin permissions form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function alterUserPermissionsForm(array &$form, array &$formState)
    {
        $form['#submit'][] = 'lcConnectorUserPermissionsSubmit';
    }

    /**
     * Submit admin permissions form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitUserPermissionsForm(array &$form, array &$formState)
    {
        return \XLite\Module\CDev\DrupalConnector\Drupal\Profile::getInstance()->performActionUpdateRoles(user_roles());
    }

    // }}}
}
