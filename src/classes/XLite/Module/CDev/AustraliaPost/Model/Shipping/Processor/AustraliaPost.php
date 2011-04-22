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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor;

/**
 * Shipping processor model
 * API documentation: http://drc.edeliver.com.au/
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      1.0.0
 */
class AustraliaPost extends \XLite\Model\Shipping\Processor\AProcessor implements \XLite\Base\IDecorator
{
    /**
     * Unique processor Id
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $processorId = 'aupost';

    /**
     * Australia Post API URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $apiURL = 'http://drc.edeliver.com.au/ratecalc.asp';

    /**
     * prepareInputData 
     * 
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier Shipping order modifier 
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareInputData(\XLite\Logic\Order\Modifier\Shipping $modifier)
    {
        $result = null;

        // Fill $result array by data from the order

        if ('AU' == \XLite\Core\Config::getInstance()->Company->location_country) {

            $result['srcAddress']['zipcode'] = \XLite\Core\Config::getInstance()->Company->location_zipcode;

            $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($modifier);

            if (isset($address)) {
                $result['dstAddress'] = $address;
                $result['weight'] = \XLite\Core\Converter::convertWeightUnits(
                    $modifier->getWeight(), 
                    \XLite\Core\Config::getInstance()->General->weight_unit,
                    'g'
                );

            } else {

                $result = null;
            }
        }

        return $result;
    }

    /**
     * doQuery 
     * 
     * @param mixed $data        Can be either \XLite\Model\Order instance or an array
     * @param boolean  $ignoreCache Flag: if true then do not get rates from cache
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doQuery($data, $ignoreCache)
    {
        $rates = array();

        $availableMethods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findMethodsByProcessor($this->getProcessorId());

        $currencyRate = doubleval(\XLite\Core\Config::getInstance()->CDev->AustraliaPost->currency_rate);
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
                'Length'               => \XLite\Core\Config::getInstance()->CDev->AustraliaPost->length,
                'Width'                => \XLite\Core\Config::getInstance()->CDev->AustraliaPost->width,
                'Height'               => \XLite\Core\Config::getInstance()->CDev->AustraliaPost->height,
                'Quantity'             => 1,
            );

            $postData = array();

            foreach ($postFields as $key => $value) {
                $postData[] = sprintf('%s=%s', $key, $value);
            }

            $postURL = $this->apiURL . '?' . implode('&', $postData);

            try {

                if (!$ignoreCache) {
                    $cachedRate = $this->getDataFromCache($postURL);
                }

                if (isset($cachedRate)) {
                    $result = $cachedRate;

                } else {

                    $bouncer  = new \XLite\Core\HTTP\Request($postURL);
                    $bouncer->requestTimeout = 5;
                    $response = $bouncer->sendRequest();

                    if (200 == $response->code) {
                        $result = $response->body;
                        $this->saveDataInCache($postURL, $result);
                    } else {
                        $errorMsg = 'Bouncer error';
                        break;
                    }
                }
            
                $response = $this->parseResponse($result);

                $this->apiCommunicationLog[] = array(
                    'request'  => $postURL,
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getProcessorName()
    {
        return 'Australia Post';
    }

    /**
     * Returns shipping rates 
     * 
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier    Shipping order modifier
     * @param boolean                              $ignoreCache Flag: if true then do not get rates from cache OPTIONAL
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRates(\XLite\Logic\Order\Modifier\Shipping $modifier, $ignoreCache = false)
    {
        $rates = array();

        $inputData = $this->prepareInputData($modifier);

        if (isset($inputData)) {
            $rates = $this->doQuery($inputData, $ignoreCache);
        }

        // Return shipping rates list
        return $rates;
    }

    /**
     * Returns shipping rates
     *
     * @param array   $modifier    Shipping order modifier
     * @param boolean $ignoreCache Flag: if true then do not get rates from cache OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRatesByArray(array $inputData, $ignoreCache = false)
    {
        return $this->doQuery($inputData, $ignoreCache);
    }

}
