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

namespace XLite\Module\USPS\Model\Shipping;

/**
 * USPS shipping
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Usps extends \XLite\Model\Shipping\Online
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
        'server',
        'https',
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
     * XPath object
     * 
     * @var    DOMXPath
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $xpath = null;

    /**
     * Raw rates 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $rawRates = array();

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
     * @param \XLite\Model\Order $order Order
     *  
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates(\XLite\Model\Order $order)
    {
        $result = array();
        $this->rawRates = array();

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
     * @param \XLite\Model\Order $order Order
     *  
     * @return array of \XLite\Model\ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNationalRates(\XLite\Model\Order $order)
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
        $xml = new \XLite\Model\XML();
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
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNationalRatesQuery($ounces, $zipOrigination, $zipDestination, $options) 
    {
        $this->rawRates = array();

        // transform the #####-#### ZIP format into just #####
        $zipOrigination = $this->_normalizeZip($zipOrigination);
        $zipDestination = $this->_normalizeZip($zipDestination);

        // Express container type
        $containerExpress = strtoupper($options->container_express);
        $containerExpress = in_array($containerExpress, $this->expressContainers)
            ? '<Container>' . $containerExpress . '</Container>'
            : '';

        // Priority container type
        $containerPriority = strtoupper($options->container_priority);
        $containerPriorityTag = in_array($containerPriority, $this->priorityContainers)
            ? '<Container>' . $containerPriority . '</Container>'
            : '';

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
<RateV3Request USERID="$options->userid">
  <Package ID="0">
    <Service>EXPRESS</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    $containerExpress
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="1">
    <Service>FIRST CLASS</Service>
    <FirstClassMailType>$firstClassMailType</FirstClassMailType>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Container>None</Container>
    <Size>$options->package_size</Size>
    $dimXml
    $dimGirthXml
    $machinableFirstClass
  </Package>
  <Package ID="2">
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
  <Package ID="3">
    <Service>PARCEL</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Container>None</Container>
    <Size>$options->package_size</Size>
    <Machinable>$options->machinable</Machinable>
  </Package>
  <Package ID="4">
    <Service>BPM</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Container>None</Container>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="5">
    <Service>LIBRARY</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Container>None</Container>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="6">
    <Service>MEDIA</Service>
    <ZipOrigination>$zipOrigination</ZipOrigination>
    <ZipDestination>$zipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Container>None</Container>
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
     * @param \XLite\Model\Order $order Order
     *  
     * @return array of \XLite\Model\ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInternationalRates(\XLite\Model\Order $order)
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
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInternationalRatesQuery($ounces, $destinationCountry, $options)
    {
        $this->rawRates = array();

        $valueOfContent = intval($options->value_of_content);
        $valueOfContentXml = 0 < $valueOfContent
            ? '<ValueOfContents>' . $valueOfContent . '</ValueOfContents>'
            : '';

        $request = <<<EOT
<IntlRateRequest USERID="$options->userid">
    <Package ID="0">
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
     * @return array of \XLite\Model\ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($response, $destination)
    {
        $this->rawRates = array();
        $this->error = '';
        $this->xmlError = false;

        $dom = new DOMDocument();
        $result = @$dom->loadXML($response, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_ERR_NONE);
        $this->xpath = new DOMXPath($dom);

        if (!$result) {

            $this->error = $xml->error;
            $this->xmlError = true;
            $this->response = $response;

        } elseif (
            'I' == $destination
            && $this->xpath->query('//IntlRateResponse/Package/Error')->length
        ) {

            $this->error = $this->xpath->query('//IntlRateResponse/Package/Error/Description')->item(0)->nodeValue;

        } elseif ('I' == $destination) {

            $services = $this->xpath->query('//IntlRateResponse/Package/Service');
            if ($services->length) {
                $this->saveInternationalRates($services);
            }

        } elseif ($this->xpath->query('//RateV3Response/Package')->length) {

            $this->saveNationalRates($this->xpath->query('//RateV3Response/Package'));

        } elseif ($this->xpath->query('//RateResponse/Package')->length) {

            $this->saveNationalRatesOld($this->xpath->query('//RateResponse/Package'));

        }

        return $this->rawRates;
    }

    /**
     * Save international rates 
     * 
     * @param DOMNodeList $services Services list
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveInternationalRates(\DOMNodeList $services)
    {
        foreach ($services as $service) {
            $this->buildRate(
                'I',
                $this->xpath->query('SvcDescription', $service)->item(0)->nodeValue,
                $this->xpath->query('Postage', $service)->item(0)->nodeValue
            );
        }
    }

    /**
     * Save national rates 
     * 
     * @param DOMNodeList $services Services list
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveNationalRates(\DOMNodeList $services)
    {
        foreach ($services as $service) {
            if ($this->xpath->query('Error', $service)->length) {
                $this->error = $this->xpath->query('Error/Description', $service)->item(0)->nodeValue;
                continue;
            }

            foreach ($this->xpath->query('Postage', $service) as $postage) {
                $this->buildRate(
                    'L',
                    $this->xpath->query('MailService', $postage)->item(0)->nodeValue,
                    $this->xpath->query('Rate', $postage)->item(0)->nodeValue
                );
            }
        }
    }

    /**
     * Save national rates (old variant)
     * 
     * @param DOMNodeList $services Services list
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveNationalRatesOld(\DOMNodeList $services)
    {
        foreach ($services as $service) {
            if ($this->xpath->query('Error', $service)->length) {

                $this->error = $this->xpath->query('Error/Description', $service)->item(0)->nodeValue;

            } elseif (isset($this->translations[$this->xpath->query('Service', $service)->item(0)->nodeValue])) {

                $this->buildRate(
                    'L',
                    $this->translations[$this->xpath->query('Service', $service)->item(0)->nodeValue],
                    $this->xpath->query('Postage', $service)->item(0)->nodeValue
                );
            }
        }
    }

    /**
     * Build shipping rate and add to internal list
     * 
     * @param string $destination Destination code
     * @param string $suffix      Shipping method suffix
     * @param string $rate        Unformatted rate
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function buildRate($destination, $suffix, $rate)
    {
        $shipping = $this->getService(
            'usps',
            'U.S.P.S. ' . $suffix,
            $destination
        );
        $id = $shipping->get('shipping_id');

        $this->rawRates[$id] = new \XLite\Model\ShippingRate();
        $this->rawRates[$id]->shipping = $shipping;
        $this->rawRates[$id]->rate = doubleval(trim($rate));
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

        $file = 'testing.shippingapis.com' == $url
            ? '/ShippingAPITest.dll'
            : '/ShippingAPI.dll';

        if ('Y' != $options->https) {

            require_once LC_LIB_DIR . 'PEAR.php';
            require_once LC_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

            $response = false;

            try {

                $http = new HTTP_Request2(
                    'http://' . $url . $file . '?' . $queryString,
                    HTTP_Request2::METHOD_GET
                );
                $http->setConfig('timeout', 5);
                $response = $http->send()->getBody();

            } catch (Exception $e) {
                $this->error = $e->getMessage();
            }

        } else {

            $https = new \XLite\Model\HTTPS();
            $https->data = $queryString;
            $https->method = 'GET';
            $https->conttype = 'application/xml';
            $https->urlencoded = true;
            $https->url = 'https://' . $url . ':443' . $file;

            if ($https->request() == \XLite\Model\HTTPS::HTTPS_ERROR) {
                $this->error = $https->error;

            } else {
                $response = $https->response;
            }
        }

        return $response;
    }

}
