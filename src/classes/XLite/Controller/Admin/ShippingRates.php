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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingRates extends \XLite\Controller\Admin\AAdmin
{
    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        parent::handleRequest();

        if ('Y' != $this->config->Shipping->shipping_enabled) {
            $this->redirect('admin.php?target=shipping_settings');
        }
    }

    /**
     * Validates and prepares posted data for markup objects
     * 
     * @param array $data  Array of posted data
     * @param bool  $isNew If true then prepares data for creating a new markup 
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareData($data, $isNew = false)
    {
        // Allowed markup fields
        $fields = ($isNew ? array('method_id', 'zone_id') : array());

        $fields = array_merge(
            $fields,
            array(
                'min_weight',
                'max_weight',
                'min_total',
                'max_total',
                'min_items',
                'max_items',
                'markup_flat',
                'markup_percent',
                'markup_per_item',
                'markup_per_weight'
            )
        );

        $errorMsg = null;

        foreach ($data as $key => $value) {

            // Reject key if it is out of the allowed fields
            if (!in_array($key, $fields)) {
                unset($data[$key]);
                continue;
            }

            // If it is for creating of markup - validate method_id and zone_id
            if ($isNew) {

                if ('method_id' == $key) {
                    $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')
                        ->getMethodById(intval($value));

                    if (isset($method)) {
                        // Add shipping method object to the data
                        $data['shipping_method'] = $method;

                    } else {
                        $errorMsg = $this->t('Wrong method_id specifed');
                        break;
                    }
                }

                if ('zone_id' == $key) {
                    $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(intval($value));

                    if (isset($zone)) {
                        // Add zone object to the data
                        $data['zone'] = $zone;

                    } else {
                        $errorMsg = $this->t('Wrong zone_id specifed');
                        break;
                    }
                }
            }

            // Sanitize value
            $data[$key] = in_array($key, array('method_id', 'zone_id')) ? intval($value) : doubleval($value);
        }

        // If error occured then returns false, else returns data
        if (isset($errorMsg)) {
            $result = false;

            \XLite\Core\TopMessage::getInstance()->add(
                $errorMsg,
                \XLite\Core\TopMessage::ERROR
            );

        } else {
            $result = $data;
        }

        return $result;
    }

    /**
     * Do action 'Add'
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionAdd()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $data = $this->prepareData($postedData['new'], true);

        if (is_array($data)) {

            $newMarkup = new \XLite\Model\Shipping\Markup();

            $newMarkup->map($data);

            \XLite\Core\Database::getEM()->persist($newMarkup);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::getInstance()->add(
                $this->t('Shipping markup is successfully created'),
                \XLite\Core\TopMessage::INFO
            );
        }
    }

    /**
     * doActionUpdate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionUpdate()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        if (isset($postedData['posted_data']) && is_array($postedData['posted_data'])) {

            foreach ($postedData['posted_data'] as $markupId => $values) {

                $values = $this->prepareData($values);

                if (is_array($values)) {
                    $data[$markupId] = $values;
                }
            }

            $markupIds = array_keys($data);

            $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->getMarkupsByIds($markupIds);

            if (!empty($markups)) {

                foreach ($markups as $markup) {
                    $markup->map($data[$markup->getMarkupId()]);
                    \XLite\Core\Database::getEM()->persist($markup);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    $this->t('Shipping markups have been updated'),
                    \XLite\Core\TopMessage::INFO
                );
            }
        }

        $this->redirect($this->getRedirectUrl());
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionDelete()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        if (isset($postedData['to_delete']) && is_array($postedData['to_delete'])) {

            $markupIds = array_keys($postedData['to_delete']);

            $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->getMarkupsByIds($markupIds);

            if (!empty($markups)) {

                foreach ($markups as $markup) {
                    \XLite\Core\Database::getEM()->remove($markup);
                }

                \XLite\Core\Database::getEM()->flush();
                \XLite\Core\Database::getEM()->clear();

                \XLite\Core\TopMessage::getInstance()->add(
                    $this->t('The selected shipping markups have been deleted successfully'),
                    \XLite\Core\TopMessage::INFO
                );
            }
        }

        $this->redirect($this->getRedirectUrl());
    }

    /**
     * Do action 'change'
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionChange()
    {
        $this->redirect($this->getRedirectUrl());
    }

    /**
     * Generates redirect Url 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRedirectUrl()
    {
        $params = array();

        $postedData = \XLite\Core\Request::getInstance()->getData();

        if (isset($postedData['methodid']) && 0 < intval($postedData['methodid'])) {
            $params[] = sprintf('methodid=%d', intval($postedData['methodid']));
        }

        if (isset($postedData['zoneid']) && 0 < intval($postedData['zoneid'])) {
            $params[] = sprintf('zoneid=%d', intval($postedData['zoneid']));
        }

        $redirect = 'admin.php?target=shipping_rates';

        if (!empty($params)) {
            $redirect .= '&' . implode('&', $params);
        }

        return $redirect;
    }

}
