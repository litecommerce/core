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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Admin 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $fieldTypesTranslationTable = array(
        'string'   => 'textfield',
        'integer'  => 'textfield',
        'checkbox' => 'checkbox',
        'list'     => 'select',
    );


    // ------------------------------ Auxiliary methods -

    /**
     * Return list of available block types
     *
     * @return array
     * @access protected
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
     * @param string $field Field in data array to retrieve field
     *
     * @return boolean
     * @access protected
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
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isNewBlock(array $form)
    {
        return empty($form['delta']['#value']);
    }

    /**
     * Return block description
     * 
     * @param integer $delta Block ID
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return array|null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return null
     * @access protected
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
     * @return null
     * @access protected
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
     * @param array  $form  Form description
     * @param string $class LC widget class
     * @param string $label Widget class readable name
     * @param array  $block Block description
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addSettingsFieldset(array &$form, $class, $label, array $block = array())
    {
        // Get settings from LC
        if ($settings = $this->getHandler()->getWidget($class)->getWidgetSettings()) {

            // To prevent some unpredictable errors related to backslashes in element IDs
            $form[$key = 'lc_block_' . ltrim($class, '\\')] = array(
                '#type'       => 'fieldset',
                '#title'      => 'Parameters',
                '#attributes' => array('id' => str_replace('\\', '_', $key)),
            );

            // Translate native LC options into Drupal format
            foreach ($settings as $name => $param) {

                $form[$key][$name] = array(
                    '#type'          => $this->fieldTypesTranslationTable[$param->type],
                    '#title'         => t($param->label),
                    '#default_value' => isset($block['options'][$name]) ? $block['options'][$name] : $param->value,
                );

                if ('select' === $form[$key][$name]['#type']) {
                    $form[$key][$name]['#options'] = $param->options;
                }
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
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function extractWidgetSettings($class, array $data)
    {
        return !empty($data['lc_block_' . ($class = ltrim($class, '\\'))]) ? $data['lc_block_' . $class] : array();
    }

    /**
     * Method to modify LC widget settings in DB
     *
     * @param integer $blockId  Block delta
     * @param array   $settings Settings list
     *
     * @return null
     * @access protected
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


    // ------------------------------ Hook handlers -

    /**
     * Modify widget details form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return null
     * @access public
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

        $delta = isset($form['delta']['#value']) ? $form['delta']['#value'] : null;
        $form['settings']['lc_widget_details']['lc_widget'] = array('#tree' => true);

        foreach ($this->getHandler()->getWidgetsList() as $class => $label) {
            $this->addSettingsFieldset(
                $form['settings']['lc_widget_details']['lc_widget'],
                $class,
                $label,
                ($delta && ($class === $actualClass)) ? $this->getBlock($delta) : array()
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
     * @return null
     * @access public
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
     * @return null
     * @access public
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
                $errors = $this->getHandler()->getWidget($class)->validateAttributes(
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
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitWidgetModifyForm(array &$form, array &$formState)
    {
        // Short names
        $data  = $formState['values'];
        $class = $data['lc_class'];
        $delta = $data['delta'];

        if (!empty($data['delta'])) {

            // Set LC class field for block
            db_update('block_custom')
                ->fields(array('lc_class' => $class))
                ->condition('bid', $delta)
                ->execute();

            // Remove old and save new settings for widget
            $this->updateWidgetSettings($delta, $this->extractWidgetSettings($class, $data['lc_widget']));
        }
    }

    /**
     * Submit widget delete confirmation form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function submitWidgetDeleteForm(array &$form, array &$formState)
    {
        $this->updateWidgetSettings($formState['delta'], array());
    }


    // ------------------------------ Module settings form -

    /**
     * Return form description for the module settings
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleSettingsForm()
    {
        $form = array(

            'settings' => array(
                'lc_dir' => array(
                    '#type'          => 'textfield',
                    '#title'         => t('LiteCommerce installation dir'),
                    '#required'      => true,
                    '#default_value' => variable_get('lc_dir', self::LC_DIR_DEFAULT),
                ),

                '#type'  => 'fieldset',
                '#title' => t('LC Connector module settings'),
            ),
        );

        return system_settings_form($form);
    }
}
