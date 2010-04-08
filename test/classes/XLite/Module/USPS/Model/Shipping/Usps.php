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

/**
 * USPS shipping
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_USPS_Model_Shipping_Usps extends XLite_Model_Shipping_Online
{
    /**
     * Error message
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $error = '';

    /**
     * XML error flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $xmlError = false;

    /**
     * Translations table (USPS shipping code to shipping name)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $translations = array(
        'EXPRESS'     => 'Express Mail',
        'PRIORITY'    => 'Priority Mail',
        'PARCEL'      => 'Parcel Post',
        'LIBRARY'     => 'Library',
        'FIRST CLASS' => 'First Class',
        'FIRSTCLASS'  => 'First Class',
        'MEDIA'       => 'Media',
        'BPM'         => 'Bound Printed Matter',
    );

    /**
     * Category in configuration storage
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $configCategory = 'USPS';

    /**
     * Option field names
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $optionsFields = array(
        'userid',
        'password',
        'server',
        'container_express',
        'container_priority',
        'mailtype',
        'machinable',
        'package_size',
        'value_of_content',
        'dim_lenght',
        'dim_width',
        'dim_height',
        'dim_girth',
        'fcmailtype',
    );

    /**
     * Express containers codes
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $expressContainers = array('VARIABLE', 'FLAT RATE ENVELOPE');

    /**
     * Priority containers codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $priorityContainers =  array(
        'VARIABLE',
        'FLAT RATE BOX',
        'FLAT RATE ENVELOPE',
        'RECTANGULAR',
        'NONRECTANGULAR',
    );

    /**
     * Get shipping module name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleName()
    {
        return 'U.S.P.S.';
    }

    /**
     * Get shipping rates 
     * 
     * @param XLite_Model_Order $order Order
     *  
     * @return array of XLite_Model_ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates(XLite_Model_Order $order)
    {
        $result = array();

        if (
            (!is_null($order->getProfile()) || $this->config->General->def_calc_shippings_taxes)
            && 0 < $order->get('weight')
            && $order->get('payment_method') != 'COD'
            && $this->getOptions()->userid
            && $this->getOptions()->server
        ) {

            $destinationCountry = is_null($order->getProfile())
                ? $this->config->General->default_country
                : $order->getProfile()->get('shipping_country');

            $result = $destinationCountry == $this->config->Company->location_country
                ? $this->getNationalRates($order)
                : $this->getInternationalRates($order);
        }

        return $result;
    }

    /**
     * Get national rates 
     * 
     * @param XLite_Model_Order $order Order
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNationalRates(XLite_Model_Order $order)
    {
        $options = $this->getOptions();
        $ounces = $this->getOunces($order);
        $zipOrigination = $this->config->Company->location_zipcode;

        $zipDestination = is_null($order->getProfile())
            ? $order->config->General->default_zipcode
            : $order->getProfile()->get('shipping_zipcode');

        // check national shipping rates cache
        $fields = array(
            'ounces'             => $ounces,
            'ziporig'            => $zipOrigination,
            'zipdest'            => $zipDestination,
            'package_size'       => $options->package_size,
            'machinable'         => $options->machinable,
            'container_priority' => $options->container_priority,
            'container_express'  => $options->container_express,
            'dim_lenght'         => $options->dim_lenght,
            'dim_width'          => $options->dim_width,
            'dim_height'         => $options->dim_height,
            'dim_girth'          => $options->dim_girth,
            'fcmailtype'         => $options->fcmailtype,
        );

        $rates = $this->_checkCache('usps_nat_cache', $fields);

        if (!$rates) {
            $rates = $this->filterEnabled(
                $this->getNationalRatesQuery(
                    $ounces,
                    $zipOrigination,
                    $zipDestination,
                    $options
                )
            );

            // store the result in cache
            $this->_cacheResult('usps_nat_cache', $fields, $rates);

            // add shipping markups
            $rates = $this->serializeCacheRates($rates);
            $rates = $this->unserializeCacheRates($rates);
        }

        return $rates;
    }

    /**
     * Check USPS errors in response
     * 
     * @param string &$response Response
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkUSPSError(&$response)
    {
        $result = false;

        $this->error = '';
        $this->xmlError = false;
        $xml = new XLite_Model_XML();
        $tree = $xml->parse($response);

        if (!$tree) {

            $this->error = $xml->error;
            $this->xmlError = true;
            $result = true;

        } elseif (isset($tree['ERROR'])) {

            $this->error = array(
                'ERROR:'
            );

            if (isset($tree['ERROR']['NUMBER'])) {
                $this->error[] = $tree['ERROR']['NUMBER'];
            }

            if (isset($tree['ERROR']['SOURCE'])) {
                $this->error[] = '(' . $tree['ERROR']['SOURCE'] . ')';
            }

            if (isset($tree['ERROR']['DESCRIPTION'])) {
                $this->error[] = $tree['ERROR']['DESCRIPTION'];
            }

            $this->error = implode(' ', $this->error);
            $result = true;
        }

        return $result;
    }

    /**
     * Get national rates (internal functionality)
     * 
     * @param integer $ounces         Weight (ounces)
     * @param string  $zipOrigination Origination zipcode
     * @param string  $zipDestination Destination zipcode
     * @param obhect  $options        Options
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNationalRatesQuery($ounces, $zipOrigination, $zipDestination, $options) 
    {
        // transform the #####-#### ZIP format into just #####
        $zipOrigination = $this->_normalizeZip($zipOrigination);
        $zipDestination = $this->_normalizeZip($zipDestination);

        // Express container type
        $containerExpress = strtoupper($options->container_express);
        if (!in_array($containerExpress, $this->expressContainers)) {
            $containerExpress = '';
        }
        $containerExpress = '<Container>' . $containerExpress . '</Container>';

        // Priority container type
        $containerPriority = strtoupper($options->container_priority);
        if (!in_array($containerPriority, $this->priorityContainers)) {
            $containerPriority = '';
        }
        $containerPriorityTag = '<Container>' . $containerPriority . '</Container>';

        // Make Dimensions
        $dimXml    = $dimGirthXml = '';
        $dimWidth  = $options->dim_width;
        $dimLength = $options->dim_lenght;
        $dimHeight = $options->dim_height;
        $dimGirth  = $options->dim_girth;

        if (
            'RECTANGULAR' == $containerPriority
            || 'NONRECTANGULAR' == $containerPriority
        ) {
            $dimXml = <<<EOT
    <Width>$dimWidth</Width>
    <Length>$dimLength</Length>
    <Height>$dimHeight</Height>
EOT;
            if ('NONRECTANGULAR' == $containerPriority) {
                $dimGirthXml = '<Girth>' . $dimGirth . '</Girth>';
            }
        }

        $firstClassMailType = strtoupper($options->fcmailtype);
        $machinableFirstClass = in_array($firstClassMailType, array('LETTER', 'FLAT'))
            ? '<Machinable>' . $options->machinable . '</Machinable>'
            : '';

        $request = <<<EOT
<RateV3Request USERID='$options->userid' PASSWORD='$options->password'>
  <Package ID='0'>
    <Service>EXPRESS</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    $containerExpress
    <Size>$options->package_size</Size>
  </Package>
  <Package ID='1'>
    <Service>FIRST CLASS</Service>
    <FirstClassMailType>$firstClassMailType</FirstClassMailType>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
    $machinableFirstClass
  </Package>
  <Package ID='2'>
    <Service>PRIORITY</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    $containerPriorityTag
    <Size>$options->package_size</Size>
    $dimXml
    $dimGirthXml
  </Package>
  <Package ID='3'>
    <Service>PARCEL</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
    <Machinable>$options->machinable</Machinable>
  </Package>
  <Package ID='4'>
    <Service>BPM</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID='5'>
    <Service>LIBRARY</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID='6'>
    <Service>MEDIA</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
</RateV3Request>
EOT;

        $response = $this->request('API=RateV3&XML=' . urlencode(trim($request)), $options);

        $result = array();

        if (!$this->error && !$this->checkUSPSError($response)) {
            $result = $this->parseResponse($response, 'L');
        }

        return $result;
    }

    /**
     * Get international rates 
     * 
     * @param XLite_Model_Order $order Order
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInternationalRates(XLite_Model_Order $order)
    {
        $ounces = $this->getOunces($order);

        $destinationCountry = is_null($order->getProfile())
            ? $this->config->General->default_country
            : $order->getProfile()->getComplex('shippingCountry.country');

        $options = $this->getOptions();

        // check international shipping rates cache
        $fields = array(
            'ounces'           => $ounces,
            'country'          => $destinationCountry,
            'mailtype'         => $options->mailtype,
            'value_of_content' => $options->value_of_content,
        );

        $rates = $this->_checkCache('usps_int_cache', $fields);
        if (!$rates) {
            $rates = $this->filterEnabled(
                $this->getInternationalRatesQuery(
                    $ounces,
                    $destinationCountry,
                    $options
                )
            );

            if (!$this->error) {

                // store the result in cache
                $this->_cacheResult('usps_int_cache', $fields, $rates);

                // add shipping markups
                $rates = $this->serializeCacheRates($rates);
                $rates = $this->unserializeCacheRates($rates);
            }
        }

        return $rates;
    }

    /**
     * Get international rates (internal functionality)
     * 
     * @param integer $ounces             Weight (ounces)
     * @param string  $destinationCountry Destination country code
     * @param object  $options            Options
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInternationalRatesQuery($ounces, $destinationCountry, $options)
    {
        $valueOfContent = intval($options->value_of_content);
        $valueOfContentXml = 0 < $valueOfContent
            ? '<ValueOfContents>' . $valueOfContent . '</ValueOfContents>'
            : '';

        $request = <<<EOT
<IntlRateRequest USERID='$options->userid'>
    <Package ID='0'>
        <Pounds>0</Pounds>
        <Ounces>$ounces</Ounces>
        <MailType>$options->mailtype</MailType>
        $valueOfContentXml
        <Country>$destinationCountry</Country>
    </Package>
</IntlRateRequest>
EOT;
        $response = $this->request('API=IntlRate&XML=' . urlencode(trim($request)), $options);

        $result = array();
        if (!$this->error && !$this->checkUSPSError($response)) {
            $result = $this->parseResponse($response, 'I');
        }

        return $result;
    }

    /**
     * Clean cache 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cleanCache()
    {
        $this->_cleanCache('usps_int_cache');
        $this->_cleanCache('usps_nat_cache');
    }
    
    /**
     * Parse response 
     * 
     * @param string $response    Response
     * @param string $destination Destination code (L or I)
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($response, $destination)
    {
        $rates = array();
        $this->error = '';
        $this->xmlError = false;
        $xml = new XLite_Model_XML();
        $tree = $xml->parse($response);

        if (!$tree) {

            $this->error = $xml->error;
            $this->xmlError = true;
            $this->response = $xml->xml;

        } elseif (
            'I' == $destination
            && isset($tree['INTLRATERESPONSE']['PACKAGE'][0]['ERROR'])
        ) {

            $this->error = $tree['INTLRATERESPONSE']['PACKAGE'][0]['ERROR']['DESCRIPTION'];

        } elseif ('I' == $destination) {

            if (is_array($tree['INTLRATERESPONSE']['PACKAGE'][0]['SERVICE'])) {
                $rates = $this->saveInternationalRates($tree['INTLRATERESPONSE']['PACKAGE'][0]['SERVICE']);
            }

        } elseif (is_array($tree['RATEV3RESPONSE']['PACKAGE'])) {

            $rates = $this->saveNationalRates($tree['RATEV3RESPONSE']['PACKAGE']);

        } elseif (is_array($tree['RATERESPONSE']['PACKAGE'])) {

            $rates = $this->saveNationalRatesOld($tree['RATERESPONSE']['PACKAGE']);

        }

        return $rates;
    }

    /**
     * Save international rates 
     * 
     * @param array $services Services list
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveInternationalRates(array $services)
    {
        $rates = array();

        foreach ($services as $service) {
            $shipping = $this->getService(
                'usps',
                'U.S.P.S. ' . $service['SVCDESCRIPTION'],
                'I'
            );
            $id = $shipping->get('shipping_id');

            $rates[$id] = new XLite_Model_ShippingRate();
            $rates[$id]->shipping = $shipping;
            $rates[$id]->rate = doubleval(trim($service['POSTAGE']));
        }

        return $rates;
    }

    /**
     * Save national rates 
     * 
     * @param array $services Services list
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveNationalRates(array $services)
    {
        $rates = array();

        foreach ($services as $service) {
            if (isset($service['ERROR'])) {
                $this->error = $service['ERROR']['DESCRIPTION'];
                continue;
            }

            $index = '';
            while (isset($service['POSTAGE' . $index])) {
                $postage = $service['POSTAGE' . $index];
                $index = empty($index) ? 1 : $index + 1;
                $shipping = $this->getService(
                    'usps',
                    'U.S.P.S. ' . $postage['MAILSERVICE'],
                    'L'
                );
                $id = $shipping->get('shipping_id');

                $rates[$id] = new XLite_Model_ShippingRate();
                $rates[$id]->shipping = $shipping;
                $rates[$id]->rate = doubleval(trim($postage['RATE']));
            }
        }

        return $rates;
    }

    /**
     * Save national rates (old variant)
     * 
     * @param array $services Services list
     *  
     * @return array of XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveNationalRatesOld(array $services)
    {
        $rates = array();

        foreach ($services as $service) {
            if (isset($service['ERROR'])) {

                $this->error = $service['ERROR']['DESCRIPTION'];

            } elseif (isset($this->translations[$service['SERVICE']])) {

                $shipping = $this->getService(
                    'usps',
                    'U.S.P.S. ' . $this->translations[$service['SERVICE']],
                    'L'
                );
                $id = $shipping->get('shipping_id');

                $rates[$id] = new XLite_Model_ShippingRate();
                $rates[$id]->shipping = $shipping;
                $rates[$id]->rate = doubleval(trim($service['POSTAGE']));

            }
        }

        return $rates;
    }

    /**
     * HTTP / HTTPS request 
     * 
     * @param string $queryString Query string
     * @param object $options     Options
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function request($queryString, $options)
    {
        $this->error = '';

        $response = '';

        $url = trim($options->server);
        if (!preg_match('/^https/i', $url)) {

            require_once LC_EXT_LIB_DIR . 'HTTP' . LC_DS . 'Request.php';

            $pearObj = new PEAR();

            $http = new HTTP_Request($url . '?' . $queryString);
            $http->_timeout = 5;
            $result = $http->sendRequest();

            if ($pearObj->isError($result)) {

                $this->error = $result->getMessage();

            } else {

                $response = $http->getResponseBody();

            }

        } else {

            $https = new XLite_Model_HTTPS();
            $https->data = $queryString;
            $https->method = 'POST';
            $https->conttype = 'application/xml';
            $https->urlencoded = true;
            $https->url = $url;

            if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
                $this->error = $https->error;

            } else {
                $response = $https->response;
            }
        }

        return $response;
    }
}
