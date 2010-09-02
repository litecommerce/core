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

namespace XLite\Module\AustraliaPost\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Aupost extends \XLite\Controller\Admin\AAdmin
{
/*
    public $params = array('target', "updated");
    public $page		="aupost";
    public $updated 	= false;
    public $testResult = false;
    public $settings;
    public $rates 		= array();

    public function __construct(array $params)  
    {
        parent::__construct($params);

        $aupost = new \XLite\Module\AustraliaPost\Model\Shipping\Aupost();
        $this->settings = $aupost->get('options');
    }

 */

    protected function doActionUpdate()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $allowedFields = array(
            'length',
            'width',
            'height',
            'currency_rate'
        );

        $data = array();
        $errorMsg = null;

        foreach ($allowedFields as $field) {

            if (isset($postedData[$field])) {
                $data[$field] = $postedData[$field];

            } else {
                $errorMsg = $this->t('Wrong data submited');
                break;
            }
        }

        if (isset($errorMsg)) {
            \XLite\Core\TopMessage::getInstance()->add(
                $errorMsg,
                \XLite\Core\TopMessage::ERROR
            );

        } else {
            foreach ($data as $key => $value) {
                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => 'AustraliaPost',
                        'name'     => $key,
                        'value'    => $value
                    )
                );
            }

//            \XLite\Core\Config::getInstance()->update();
            $this->config->update();

            \XLite\Core\TopMessage::getInstance()->add(
                $this->t('Shipping settings has been successfully updated'),
                \XLite\Core\TopMessage::INFO
            );
        }
    }
    
    protected function doActionTest()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        var_dump($postedData);
        die();

        if (empty($this->weight)) 
            $this->weight = 1;
        if (empty($this->sourceZipcode)) 
            $this->sourceZipcode = $this->config->Company->location_zipcode;
        if (empty($this->destinationZipcode)) 
            $this->destinationZipcode = $this->config->Company->location_zipcode;
        if (empty($this->destinationCountry)) 
            $this->destinationCountry = $this->config->General->default_country;
 
        $this->aupost = new \XLite\Module\AustraliaPost\Model\Shipping\Aupost();
        $options = $this->aupost->get('options');

        $this->rates = $this->aupost->queryRates
        (
            $options, 
            $this->sourceZipcode,
            $this->destinationZipcode,
            $this->destinationCountry,
            $this->weight,
            $this->weight_unit
        );
        $this->testResult = true;
        $this->valid	  = false;
    }

}
