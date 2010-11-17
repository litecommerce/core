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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\AustraliaPost\Model\Shipping\Processor;

/**
 * Shipping processor model
 * API documentation: http://drc.edeliver.com.au/
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class AustraliaPost extends \XLite\Model\Shipping\Processor\AProcessor implements \XLite\Base\IDecorator
{
    /**
     * Unique processor Id
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $processorId = 'aupost';

    /**
     * Australia Post API URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $apiUrl = 'http://drc.edeliver.com.au/ratecalc.asp';

    /**
     * prepareInputData 
     * 
     * @param mixed $data Can be either \XLite\Model\Order instance or an array
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareInputData($data)
    {
        $result = null;

        if ($data instanceof \XLite\Model\Order) {
            // Fill $result array by data from the order

            if ('AU' == \XLite\Base::getInstance()->config->Company->location_country) {

                $result['srcAddress']['zipcode'] = \XLite\Base::getInstance()->config->Company->location_zipcode;

                $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($data);

                if (isset($address)) {
                    $result['dstAddress'] = $address;
                    $result['weight'] = \XLite\Core\Converter::convertWeightUnits(
                        $data->getWeight(), 
                        \XLite\Base::getInstance()->config->General->weight_unit,
                        'g'
                    );

                } else {
                    $result = null;
                }
            }

        } else {
            // Suppose that data is passed for testing of rates calculation
            $result = $data; 
        }

        return $result;
    }

    /**
     * doQuery 
     * 
     * @param mixed $data        Can be either \XLite\Model\Order instance or an array
     * @param bool  $ignoreCache Flag: if true then do not get rates from cache
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doQuery($data, $ignoreCache)
    {
        $rates = array();

        $availableMethods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findMethodsByProcessor($this->getProcessorId());

        $currencyRate = doubleval(\XLite\Base::getInstance()->config->AustraliaPost->currency_rate);
        $currencyRate = $currencyRate > 0 ?: 1;

        $errorMsg = null;

        foreach ($availableMethods as $method) {

            $rate = null;

            $postFields = array(
                'Service_Type'         => $method->getCode(),
                'Pickup_Postcode'      => $data['srcAddress']['zipcode'],
                'Destination_Postcode' => $data['dstAddress']['zipcode'],
                'Country'              => $data['dstAddress']['country'],
                'Weight'               => $data['weight'],
                'Length'               => \XLite\Base::getInstance()->config->AustraliaPost->length,
                'Width'                => \XLite\Base::getInstance()->config->AustraliaPost->width,
                'Height'               => \XLite\Base::getInstance()->config->AustraliaPost->height,
                'Quantity'             => 1,
            );

            $postData = array();

            foreach ($postFields as $key => $value) {
                $postData[] = sprintf('%s=%s', $key, $value);
            }

            $postUrl = $this->apiUrl . '?' . implode('&', $postData);

            try {

                if (!$ignoreCache) {
                    $cachedRate = $this->getDataFromCache($postUrl);
                }

                if (isset($cachedRate)) {
                    $result = $cachedRate;

                } else {

                    require_once (LC_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php');

                    $http = new \HTTP_Request2($postUrl);
                    $http->setConfig('timeout', 5);

                    try {
                        $result = $http->send()->getBody();

                        // Save result in cache even if rate is failed
                        $this->saveDataInCache($postUrl, $result);

                    } catch (\HTTP_Request2_Exception $exception) {
                        $errorMsg = $exception->getMessage();
                        break;
                    }
                }
            
                $response = $this->parseResponse($result);

                $this->apiCommunicationLog[] = array(
                    'request'  => $postUrl,
                    'response' => isset($response['charge']) ? $response : $result
                );

                if ('OK' == $response['err_msg']) {
                    $rate = new \XLite\Model\Shipping\Rate();
                    $rate->setMethod($method);
                    $rate->setBaseRate($response['charge'] * $currencyRate);

                    $extraData = new \XLite\Core\CommonCell();
                    $extraData->deliveryDays = $response['days'];

                    $rate->setExtraData($extraData);
                }

            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                break;
            }

            if (isset($rate)) {
                $rates[] = $rate;
            }
        }

        return $rates;
    }

    /**
     * Parses response and returns an associative array
     * 
     * @param string $stringData Response of AUPOST API
     * example:
     *   'charge=2.50
     *   days=1
     *   err_msg=OK
     *   '
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($stringData)
    {
        $result = array();

        foreach (explode("\n", $stringData) as $data) {

            $data = trim($data);

            if (!empty($data)) {
                list($key, $value) = explode('=', $data, 2);
                $result[trim($key)] = trim($value);
            }
        }

        return $result;
    }

    /**
     * getProcessorName 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProcessorName()
    {
        return 'Australia Post';
    }

    /**
     * Returns shipping rates 
     * 
     * @param mixed $data        Can be either \XLite\Model\Order instance or an array
     * @param bool  $ignoreCache Flag: if true then do not get rates from cache
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates($data, $ignoreCache = false)
    {
        $rates = array();

        $inputData = $this->prepareInputData($data);

        if (isset($inputData)) {
            $rates = $this->doQuery($inputData, $ignoreCache);
        }

        // Return shipping rates list
        return $rates;
    }

}
