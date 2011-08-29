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

namespace XLite\Module\CDev\USPS\Model\Shipping\Processor;

/**
 * USPS shipping processor model
 * API documentation: https://www.usps.com/business/webtools-technical-guides.htm
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class USPS extends \XLite\Model\Shipping\Processor\AProcessor
{
    /**
     * Types of available API
     */
    const LC_USPS_API_DOMESTIC = 'Domestic';
    const LC_USPS_API_INTL     = 'Intl';


    /**
     * Unique processor Id
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $processorId = 'usps';

    /**
     * Type of API (Domestic | International)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $apiType = null;


    // {{{

    /**
     * Returns processor name (displayed name)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProcessorName()
    {
        return 'U.S.P.S.';
    }

    /**
     * Disable the possibility to edit the names of shipping methods in the interface of administrator
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMethodNamesAdjustable()
    {
        return false;
    }

    /**
     * Returns shipping rates by shipping order modifier (used on checkout)
     *
     * @param array|\XLite\Logic\Order\Modifier\Shipping $inputData   Shipping order modifier or array of data for request
     * @param boolean                                    $ignoreCache Flag: if true then do not get rates from cache OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRates($inputData, $ignoreCache = false)
    {
        $this->errorMsg = null;
        $rates = array();

        if ($this->isConfigured() && 'US' == \XLite\Core\Config::getInstance()->Company->location_country) {

            $data = $this->prepareRequestData($inputData);

            if (isset($data)) {
                $rates = $this->doQuery($data, $ignoreCache);
            
            } else {
                $this->errorMsg = 'Wrong input data';
            }
        
        } else {
            $this->errorMsg = 'U.S.P.S. module is not configured or origin country is not United States';
        }

        return $rates;
    }


    /**
     * Returns array of data for request
     * 
     * @param array|\XLite\Logic\Order\Modifier\Shipping $inputData Array of input data or a shipping order modifier
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareRequestData($inputData)
    {
        $result = array();

        $data = array();
        $data['packages'] = array();

        if ($inputData instanceOf \XLite\Logic\Order\Modifier\Shipping) {
            $data['srcAddress'] = array(
                'zipcode' => \XLite\Core\Config::getInstance()->Company->location_zipcode,
            );
            $data['dstAddress'] = \XLite\Model\Shipping::getInstance()->getDestinationAddress($inputData);
            $data['packages'][] = array(
                'weight'   => $inputData->getWeight(),
                'subtotal' => $inputData->getSubtotal(),
            );
        
        } else {
            $data = $inputData;
        }

        if (isset($data['srcAddress']) && isset($data['dstAddress'])) {

            $this->setApiType($data['dstAddress']);

            $result['USERID'] = \XLite\Core\Config::getInstance()->CDev->USPS->userid;
            $result['packages'] = array();

            foreach ($data['packages'] as $packKey => $package) {
                $result['packages'][] = $this->{'prepareRequestData' . $this->getApiType()}($data, $packKey);
            }

        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Performs request to USPS server and returns array of rates
     *
     * @param array   $data        Array of request parameters
     * @param boolean $ignoreCache Flag: if true then do not get rates from cache
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doQuery($data, $ignoreCache)
    {
        $rates = array();

        $availableMethods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')
            ->findMethodsByProcessor($this->getProcessorId());

        if ($availableMethods) {

            $xmlData = $this->getXMLData($data);

            $currencyRate = doubleval(\XLite\Core\Config::getInstance()->CDev->AustraliaPost->currency_rate);
            $currencyRate = 0 < $currencyRate ?: 1;

            $postURL = $this->getApiURL() . '?API=' . $this->getApiName() . '&XML=' . urlencode($xmlData);

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
                        $this->errorMsg = sprintf('Error while connecting to the USPS host (%s)', $this->getApiURL());
                    }
                }

                if (!isset($this->errorMsg)) {
                    $response = $this->parseResponse($result);
                
                } else {
                    $response = array();
                }

                $this->apiCommunicationLog[] = array(
                    'request'  => $postURL,
                    'xml' => htmlentities(preg_replace('/(USERID=")([^"]+)/', '\1***', $xmlData)),
                    'response' => htmlentities(\XLite\Core\XML::getInstance()->getFormattedXML($result))
                );

                if (!isset($this->errorMsg) && !isset($response['err_msg']) && !empty($response['postage'])) {

                    foreach ($response['postage'] as $postage) {
                        
                        $rate = new \XLite\Model\Shipping\Rate();

                        $method = $this->getShippingMethod($postage['CLASSID'], $availableMethods);

                        if (!isset($method)) {
                            // Unknown method received: add this to the database with disabled status
                            $this->addShippingMethod($postage);

                        } elseif ($method->getEnabled()) {
                            // Method is registered and enabled

                            $rate->setMethod($method);
                            $rate->setBaseRate($postage['Rate'] * $currencyRate);

                            $rates[] = $rate;
                        }
                    }

                } elseif (!isset($this->errorMsg)) {
                    $this->errorMsg = (isset($response['err_msg']) ? $response['err_msg'] : 'Unknown error');
                }

            } catch (\Exception $e) {
                $this->errorMsg = 'Exception: ' . $e->getMessage();
            }
        }

        return $rates;
    }

    /**
     * Parses response for current type of API and returns an associative array
     *
     * @param string $data Response received from USPS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponse($data)
    {
        return $this->{'parseResponse' . $this->getApiType()}($data);
    }

    /**
     * Returns XML-formatted request string for current type of API
     * 
     * @param array $data Array of request values
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getXMLData($data)
    {
        return $this->{'getXMLData' . $this->getApiType()}($data);
    }

    // }}}

    // {{{ Domestic API specific methods

    /**
     * Returns array of data for package (RateV4 request) 
     * 
     * @param array  $data    Array of input data
     * @param string $packKey Key of current package
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareRequestDataDomestic($data, $packKey)
    {
        list($pounds, $ounces) = $this->getPoundsOunces($data['packages'][$packKey]['weight']);

        $config = \XLite\Core\Config::getInstance()->CDev->USPS;

        $result = array(
            'Service' => 'ALL',
            'ZipOrigination' => $this->sanitizeZipcode($data['srcAddress']['zipcode']), // lenght=5, pattern=/\d{5}/ 
            'ZipDestination' => $this->sanitizeZipcode($data['dstAddress']['zipcode']), // lenght=5, pattern=/\d{5}/
            'Pounds' => intval($pounds), // integer, range=0-70
            'Ounces' => sprintf('%.1f', $ounces), // decimal, range=0.0-1120.0, totalDigits=10
            'Container' => $config->container,  // RECTANGULAR | NONRECTANGULAR | ...
            'Size' => $config->package_size,  // REGULAR | LARGE
            'Width' => sprintf('%.1f', $config->width), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Length' => sprintf('%.1f', $config->length), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Height' => sprintf('%.1f', $config->height), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Girth' => sprintf('%.1f', $config->girth), // Units=inches, decimal, min=0.0, totalDigits=10. Required for size=LARGE and container=NONRECTANGULAR | VARIABLE/NULL
            'Value' => sprintf('%.2f', $data['packages'][$packKey]['subtotal']), // decimal, min=0.00, totalDigits=10
            'SortBy' => 'PACKAGE', // CONTAINER | LETTER | LARGEENVELOPE | PACKAGE | FLATRATE
            'Machinable' => $config->machinable ? 'true' : 'false',
        );

        return $result;
    }

    /**
     * Returns XML-formatted string for RateV4 request  
     * 
     * @param array $data Array of request values
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getXMLDataDomestic($data)
    {
        $packId = 0;

        foreach ($data['packages'] as $pack) {

            $packId++;

            $packIdStr = sprintf('%02d', $packId);

            if (
                !empty($pack['Girth']) 
                && 0 < doubleval($pack['Girth']) 
                && in_array($pack['Container'], array('NONRECTANGULAR', 'VARIABLE'))
            ) {

                $girth = <<<OUT
        <Girth>{$pack['Girth']}</Girth>
OUT;
            } else {
                $girth = '';
            }

            $packages = <<<OUT
    <Package ID="{$packIdStr}">
        <Service>ALL</Service>
        <ZipOrigination>{$pack['ZipOrigination']}</ZipOrigination>
        <ZipDestination>{$pack['ZipDestination']}</ZipDestination>
        <Pounds>{$pack['Pounds']}</Pounds>
        <Ounces>{$pack['Ounces']}</Ounces>
        <Container>{$pack['Container']}</Container>
        <Size>{$pack['Size']}</Size>
        <Width>{$pack['Width']}</Width>
        <Length>{$pack['Length']}</Length>
        <Height>{$pack['Height']}</Height>
$girth
        <Value>{$pack['Value']}</Value>
        <Machinable>{$pack['Machinable']}</Machinable>
    </Package>
OUT;
        }

        return <<<OUT
<{$this->getApiName()}Request USERID="{$data['USERID']}">
    <Revision>2</Revision>
$packages
</{$this->getApiName()}Request>
OUT;
    }

    /**
     * Parses RateV4 response and returns an associative array
     *
     * @param string $stringData Response received from USPS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseDomestic($stringData)
    {
        $result = array();

        $xml = \XLite\Core\XML::getInstance();

        $xmlParsed = $xml->parse($stringData, $err);

        if (isset($xmlParsed['Error'])) {
            $result['err_msg'] = $xml->getArrayByPath($xmlParsed, 'Error/Description/0/#');
        
        } else {

            $error = $xml->getArrayByPath($xmlParsed, $this->getApiName() . 'Response/Package/Error');

            if ($error) {
                $result['err_msg'] = $xml->getArrayByPath($error, 'Description/0/#');
            }
        } 
        
        if (!isset($result['error_msg'])) {

            $postage = $xml->getArrayByPath($xmlParsed, $this->getApiName() . 'Response/Package/Postage');

            if ($postage) {
                foreach ($postage as $k => $v) {
                    $serviceName = $xml->getArrayByPath($v, '#/MailService/0/#');
                    $result['postage'][] = array(
                        'CLASSID' => 'D-' . $xml->getArrayByPath($v, '@/CLASSID') . '-' . md5($serviceName),
                        'MailService' => $this->getUSPSNamePrefix() . html_entity_decode($serviceName),
                        'Rate' => $xml->getArrayByPath($v, '#/Rate/0/#'),
                    );
                }
            }
        }

        return $result;
    }

    // }}} Domestic API specific methods

    // {{{ International API specific methods

    /**
     * Returns array of data for package (IntlRateV2 request) 
     * 
     * @param array  $data    Array of input data
     * @param string $packKey Key of current package
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareRequestDataIntl($data, $packKey)
    {
        list($pounds, $ounces) = $this->getPoundsOunces($data['packages'][$packKey]['weight']);

        $config = \XLite\Core\Config::getInstance()->CDev->USPS;

        $result = array(
            'Pounds' => intval($pounds), // integer, range=0-70
            'Ounces' => sprintf('%.1f', $ounces), // decimal, range=0.0-1120.0, totalDigits=10
            'Machinable' => $config->machinable ? 'true' : 'false',
            'MailType' => $config->mail_type,  // Package | Postcards or aerogrammes | Envelope | LargeEnvelope | FlatRate
            'ValueOfContents' => sprintf('%.2f', $data['packages'][$packKey]['subtotal']), // decimal
            'Country' => $this->getUSPSCountryByCode($data['dstAddress']['country']), // lenght=5, pattern=/\d{5}/
            'Container' => $config->container_intl,  // RECTANGULAR | NONRECTANGULAR
            'Size' => $config->package_size,  // REGULAR | LARGE
            'Width' => sprintf('%.1f', $config->width), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Length' => sprintf('%.1f', $config->length), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Height' => sprintf('%.1f', $config->height), // Units=inches, decimal, min=0.0, totalDigits=10. Required for LARGE
            'Girth' => sprintf('%.1f', $config->girth), // Units=inches, decimal, min=0.0, totalDigits=10. Required for size=LARGE and container=NONRECTANGULAR | VARIABLE/NULL
            'GXG' => $config->gxg,
            'GXGPOBoxFlag' => $config->gxg_pobox ? 'Y' : 'N',
            'GXGGiftFlag' => $config->gxg_gift ? 'Y' : 'N',
            'OriginZip' => $this->sanitizeZipcode($data['srcAddress']['zipcode']), // lenght=5, pattern=/\d{5}/ 
            'CommercialFlag' => $config->commercial ? 'Y' : 'N', // Y | N
            'ExtraServices' => array(),
        );

        return $result;
    }

    /**
     * Returns XML-formatted string for IntlRateV2 request  
     * 
     * @param array $data Array of request values
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getXMLDataIntl($data)
    {
        $packId = 0;

        foreach ($data['packages'] as $pack) {

            $packId++;

            $packIdStr = sprintf('%02d', $packId);

            if ($pack['GXG']) {
                $gxg = <<<OUT
        <GXG>
            <POBoxFlag>{$pack['GXGPOBoxFlag']}</POBoxFlag>
            <GiftFlag>{$pack['GXGGiftFlag']}</GiftFlag>
        </GXG>
OUT;
            } else {
                $gxg = '';
            }

            $packages = <<<OUT
    <Package ID="{$packIdStr}">
        <Pounds>{$pack['Pounds']}</Pounds>
        <Ounces>{$pack['Ounces']}</Ounces>
        <Machinable>{$pack['Machinable']}</Machinable>
        <MailType>{$pack['MailType']}</MailType>
$gxg
        <ValueOfContents>{$pack['ValueOfContents']}</ValueOfContents>
        <Country>{$pack['Country']}</Country>
        <Container>{$pack['Container']}</Container>
        <Size>REGULAR</Size>
        <Width>{$pack['Width']}</Width>
        <Length>{$pack['Length']}</Length>
        <Height>{$pack['Height']}</Height>
        <Girth>{$pack['Girth']}</Girth>
        <OriginZip>{$pack['OriginZip']}</OriginZip>
        <CommercialFlag>{$pack['CommercialFlag']}</CommercialFlag>
    </Package>
OUT;
        }

        return <<<OUT
<{$this->getApiName()}Request USERID="{$data['USERID']}">
    <Revision>2</Revision>
$packages
</{$this->getApiName()}Request>
OUT;
    }

    /**
     * Parses IntlRateV2 response and returns an associative array
     *
     * @param string $stringData Response received from USPS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseIntl($stringData)
    {
        $result = array();

        $xml = \XLite\Core\XML::getInstance();

        $xmlParsed = $xml->parse($stringData, $err);

        if (isset($xmlParsed['Error'])) {
            $result['err_msg'] = $xml->getArrayByPath($xmlParsed, 'Error/Description/0/#');
        
        } else {

            $error = $xml->getArrayByPath($xmlParsed, $this->getApiName() . 'Response/Package/Error');

            if ($error) {
                $result['err_msg'] = $xml->getArrayByPath($error, 'Description/0/#');
            }
        } 

        if (!isset($result['err_msg'])) {

            $postage = $xml->getArrayByPath($xmlParsed, $this->getApiName() . 'Response/Package/Service');

            if ($postage) {
                foreach ($postage as $k => $v) {
                    $serviceName = $xml->getArrayByPath($v, '#/SvcDescription/0/#');
                    $result['postage'][] = array(
                        'CLASSID' => 'I-' . $xml->getArrayByPath($v, '@/ID') . '-' . md5($serviceName),
                        'MailService' => $this->getUSPSNamePrefix() . html_entity_decode($serviceName),
                        'Rate' => $xml->getArrayByPath($v, '#/Postage/0/#'),
                    );
                }
            }
        }

        return $result;
    }

    // }}} International API specific methods

    // {{{ Service methods

    /**
     * Returns API URL
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getApiURL()
    {
        $protocol = 'http://';

        $host = \Includes\Utils\URLManager::isValidURLHost(\XLite\Core\Config::getInstance()->CDev->USPS->server_name) 
            ? \XLite\Core\Config::getInstance()->CDev->USPS->server_name 
            : 'testing.shippingapis.com';

        $path = \XLite\Core\Config::getInstance()->CDev->USPS->server_path;

        return $protocol . $host . '/' . $path;
    }

    /**
     * Returns array(pounds, ounces) from a weight value in specific weight units
     * 
     * @param float $weight Weight value
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPoundsOunces($weight)
    {
        $pounds = $ounces = 0;

        switch (\XLite\Core\Config::getInstance()->General->weight_unit) {

            case 'lbs':
                $pounds = $weight;
                break;

            case 'oz':
                $ounces = $weight;
                break;

            default:
                $ounces = \XLite\Core\Converter::convertWeightUnits(
                    $weight,
                    \XLite\Core\Config::getInstance()->General->weight_unit,
                    'oz'
                );
        }

        if (intval($pounds) < $pounds) {
            $ounces = ($pounds - intval($pounds)) * 16;
            $pounds = intval($pounds);
        }

        return array($pounds, round($ounces, 1));
    }

    /**
     * Returns shipping method name prefix
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getUSPSNamePrefix()
    {
        return $this->getProcessorName() . ' ';
    }


    /**
     * Returns a type of API 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getApiType()
    {
        return $this->apiType;
    }

    /**
     * Set a type of API (domestic | intrnational) depending on destination country
     * 
     * @param array $address Array of address data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setApiType($address)
    {
        $this->apiType = ('US' == $address['country'] ? self::LC_USPS_API_DOMESTIC : self::LC_USPS_API_INTL);
    }

    /**
     * Returns the name of API 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getApiName()
    {
        $apiName = array(
            self::LC_USPS_API_DOMESTIC => 'RateV4',
            self::LC_USPS_API_INTL     =>'IntlRateV2',
        );

        return $apiName[$this->getApiType()];
    }

    /**
     * Returns true if USPS module is configured 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isConfigured()
    {
        return !empty(\XLite\Core\Config::getInstance()->CDev->USPS->userid);
    }

    /**
     * Returns shipping method 
     * 
     * @param string $code             Unique code of payment method
     * @param array  $availableMethods Array of shipping methods objects gathered from database
     *  
     * @return \XLite\Model\Shipping\Method
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getShippingMethod($code, $availableMethods)
    {
        $result = null;

        if (!empty($availableMethods) && is_array($availableMethods)) {

            foreach ($availableMethods as $method) {

                if ($method->getCode() == $code) {
                    $result = $method;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Add shipping method to the database
     * 
     * @param array $postage Array of data for shipping method
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addShippingMethod($postage)
    {
        $method = new \XLite\Model\Shipping\Method();
        $method->setProcessor($this->getProcessorId());
        $method->setCarrier($this->getProcessorId());
        $method->setCode($postage['CLASSID']);
        $method->setEnabled(false);

        $code = \XLite\Core\Config::getInstance()->General->defaultLanguage->getCode();
        $method->getTranslation($code)->name = $postage['MailService'];

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Returns a name of country which is suitable for USPS API 
     * 
     * @param string $code Country code
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUSPSCountryByCode($code)
    {
        static $uspsCountries = array(
            'AE' => 'United Arab Emirates',
            'PG' => 'Papua New Guinea',
            'AF' => 'Afghanistan',
            'NZ' => 'New Zealand',
            'FI' => 'Finland',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia-Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'MM' => 'Burma',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Rep.',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo, Democratic Republic of the',
            'CR' => 'Costa Rica',
            'CI' => 'Cte d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia, Republic of',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GB' => 'Great Britain and Northern Ireland',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea, Democratic People\'s Republic of',
            'KR' => 'Korea, Republic of (South Korea)',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of',
            'MD' => 'Moldova',
            'MN' => 'Mongolia',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'MP' => 'Northern Mariana Islands, Commonwealth',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'AS' => 'American Samoa',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PA' => 'Panama',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Island',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'KN' => 'Saint Christopher (St. Kitts) and Nevis',
            'SH' => 'Saint Helena',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa, American',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovak Republic',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VI' => 'Virgin Islands U.S.',
            'WF' => 'Wallis and Futuna Islands',
            'YE' => 'Yemen',
            'YU' => 'Yugoslavia',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
            'CC' => 'Cocos Island',
            'CK' => 'Cook Islands',
            'TP' => 'East Timor',
            'YT' => 'Mayotte',
            'MC' => 'Monaco',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'TK' => 'Tokelau (Union) Group',
            'UK' => 'United Kingdom',
            'CX' => 'Christmas Island',
            'US' => 'United States',
        );

        return (isset($uspsCountries[$code]) ? $uspsCountries[$code] : null);
    }

    /**
     * Sanitize zipcode value according to USPS requirements, pattern: /\d{5}/
     * 
     * @param string $zipcode Zipcode value
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sanitizeZipcode($zipcode)
    {
        return preg_replace('/\D/', '', substr($zipcode, 0, 5));
    }

    // }}}
}
