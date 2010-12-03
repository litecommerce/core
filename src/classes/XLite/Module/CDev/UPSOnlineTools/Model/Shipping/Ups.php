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

namespace XLite\Module\CDev\UPSOnlineTools\Model\Shipping;

/**
 * Shipping
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Ups extends \XLite\Model\Shipping\Online
{
    /**
     *  Minimum package weight (lbs)
     */
    const MIN_PACKAGE_WEIGHT = 0.1;

    /**
     * Error message
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $error = 0;

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
     * UPS shipping methods 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $services = array();

    /**
     * Constructor
     * 
     * @param mixed $param Parameters OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($param = null)
    {
        parent::__construct($param);

        $this->services = $this->getServices();
    }

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
        return 'United Parcel Service';
    }

    /**
     * Set account 
     * 
     * @param array $userinfo User info
     * @param mixed &$error   Error storage
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setAccount(array $userinfo, &$error)
    {
        $devlicense = $this->config->UPSOnlineTools->devlicense;
        $upsLicenseText = '';
        if ($this->getLicense($upsLicenseText)) {
            return 2;
        }
        $version = $this->config->Version->version;

        if (is_numeric($userinfo['state'])) {
            $userinfo['state'] = \XLite\Core\Database::getRepo('\XLite\Model\State')->getCodeById($userinfo['state']);
        }

        $request = <<<EOT
<?xml version="1.0" encoding="ISO-8859-1"?>
<AccessLicenseRequest xml:lang="en-US">
    <Request>
        <TransactionReference>
            <CustomerContext>License Test</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>AccessLicense</RequestAction>
        <RequestOption>AllTools</RequestOption>
    </Request>
    <CompanyName>$userinfo[company]</CompanyName>
    <Address>
    <AddressLine1>$userinfo[address]</AddressLine1>
        <City>$userinfo[city]</City>
        <StateProvinceCode>$userinfo[state]</StateProvinceCode>
        <PostalCode>$userinfo[postal_code]</PostalCode>
        <CountryCode>$userinfo[country]</CountryCode>
    </Address>
    <PrimaryContact>
        <Name>$userinfo[contact_name]</Name>
        <Title>$userinfo[title_name]</Title>
        <EMailAddress>$userinfo[email]</EMailAddress>
        <PhoneNumber>$userinfo[phone]</PhoneNumber>
    </PrimaryContact>
    <CompanyURL>$userinfo[web_url]</CompanyURL>
    <ShipperNumber>$userinfo[shipper_number]</ShipperNumber>
    <DeveloperLicenseNumber>$devlicense</DeveloperLicenseNumber>
    <AccessLicenseProfile>
        <CountryCode>US</CountryCode>
        <LanguageCode>EN</LanguageCode>
        <AccessLicenseText>$upsLicenseText</AccessLicenseText>
    </AccessLicenseProfile>
    <OnLineTool>
        <ToolID>RateXML</ToolID>
        <ToolVersion>1.0</ToolVersion>
    </OnLineTool>
    <OnLineTool>
        <ToolID>TrackXML</ToolID>
        <ToolVersion>1.0</ToolVersion>
    </OnLineTool>
    <ClientSoftwareProfile>
        <SoftwareInstaller>$userinfo[software_installer]</SoftwareInstaller>
        <SoftwareProductName>LiteCommerce</SoftwareProductName>
        <SoftwareProvider>Creative Development LLC.</SoftwareProvider>
        <SoftwareVersionNumber>$version</SoftwareVersionNumber>
    </ClientSoftwareProfile>
</AccessLicenseRequest>
EOT;

    $result = $this->request($request, null, 'License', false);
    if ($this->error) {
        return 1;
    }
    $result = $result['ACCESSLICENSERESPONSE'];

    $status = $result['RESPONSE']['RESPONSESTATUSCODE'];
    if ($status != 1) {
        $error = $result['RESPONSE']['ERROR']['ERRORDESCRIPTION'];
        return 3;
    }

    $this->setConfig('UPS_accesskey', $result['ACCESSLICENSENUMBER']);

    $ups_userinfo = $userinfo;

    $post_counter = 0;
    $suggest = 'suggest';
    $ups_username = $this->getKeyHash(0, 12);
    $ups_password = $this->getKeyHash(16, 10);

    $request = <<<EOT
<?xml version="1.0"?>
<RegistrationRequest>
    <Request>
        <TransactionReference>
            <CustomerContext>x893</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>Register</RequestAction>
        <RequestOption>$suggest</RequestOption>
    </Request>
    <UserId>$ups_username</UserId>
    <Password>$ups_password</Password>
    <RegistrationInformation>
        <UserName>$userinfo[contact_name]</UserName>
        <Title>$userinfo[title_name]</Title>
        <CompanyName>$userinfo[company]</CompanyName>
        <Address>
            <AddressLine1>$userinfo[address]</AddressLine1>
            <City>$userinfo[city]</City>
            <StateProvinceCode>$userinfo[state]</StateProvinceCode>
            <PostalCode>$userinfo[postal_code]</PostalCode>
            <CountryCode>$userinfo[country]</CountryCode>
        </Address>
        <PhoneNumber>$userinfo[phone]</PhoneNumber>
        <EMailAddress>$userinfo[email]</EMailAddress>
        <ShipperAccount>
            <ShipperNumber>$userinfo[shipper_number]</ShipperNumber>
            <PickupPostalCode>$userinfo[postal_code]</PickupPostalCode>
            <PickupCountryCode>$userinfo[country]</PickupCountryCode>
            <AccountName>Test Account</AccountName>
        </ShipperAccount>
    </RegistrationInformation>
</RegistrationRequest>
EOT;

        $result = $this->request($request, null, 'Register', false);
        if ($this->error) {
            return 1;
        }

        if ($result['REGISTRATIONRESPONSE']['RESPONSE']['RESPONSESTATUSCODE'] != 1) {
            $error = $result['REGISTRATIONRESPONSE']['RESPONSE']['ERROR']['ERRORDESCRIPTION'];
            return 4;
        }

        $this->setConfig('UPS_username', $ups_username);
        $this->setConfig('UPS_password', $ups_password);

        return 0;
    }

    /**
     * Set configuration variable
     * 
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setConfig($name, $value)
    {
        if (in_array($name, array('UPS_username', 'UPS_password', 'UPS_accesskey'))) {
            $value = $this->encode($value);
        }

        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'category' => 'UPSOnlineTools',
                'name'     => $name,
                'value'    => $value
            )
        );
    }

    /**
     * Get rates 
     * 
     * @param \XLite\Model\Order $order Order
     *  
     * @return array(\XLite\Model\ShippingRate)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates(\XLite\Model\Order $order)
    {
        // drop error semapfores
        $this->session->set('ups_failed_items', false);
        $this->session->set('ups_rates_error', false);

        if (
            (is_null($order->getProfile()) && !$this->config->General->def_calc_shippings_taxes)
            || $order->get('weight') == 0
        ) {
            return array();
        }

        $options = $this->getOptions();
        if (
            !$options->UPS_username
            || !$options->UPS_password
            || !$options->UPS_accesskey
        ) {
            return array();
        }

        $originAddress = $this->config->Company->location_address;
        $originCity = $this->config->Company->location_city;
        $originZipCode = $this->config->Company->location_zipcode;
        $originCountry = $this->config->Company->location_country;

        // Define company state
        $state_id = $this->config->Company->location_state;
        if ($state_id != -1) {
            $originState = \XLite\Core\Database::getRepo('\XLite\Model\State')->getCodeById($state_id);

        } else {
            $originState = $this->config->Company->location_custom_state;
        }

        if (is_null($order->getProfile())) {
            $destinationAddress = '';
            $destinationCity = '';
            $destinationState = '';
            $destinationCountry = $this->config->General->default_country;
            $destinationZipCode = $this->config->General->default_zipcode;

        } else {
            $destinationAddress = $order->getProfile()->get('shipping_address');
            $destinationCity = $order->getProfile()->get('shipping_city');
            $destinationZipCode = $order->getProfile()->get('shipping_zipcode');
            $destinationCountry = $order->getProfile()->get('shipping_country');

            // Define destination state
            $state_id = $order->getProfile()->get('shipping_state');
            if ($state_id != -1) {
                $destinationState = \XLite\Core\Database::getRepo('\XLite\Model\State')->getCodeById($state_id);

            } else {
                $destinationState = $order->getProfile()->get('shipping_custom_state');
            }
        }

        // pack order items into containers
        $failed_items = array();
        $containers = $order->packOrderItems($failed_items);

        if (is_array($failed_items) && count($failed_items) > 0) {
            $this->session->set('ups_failed_items', true);

            return array();

        } else {
            $this->session->set('ups_failed_items', null);
        }

        // if containers not set, return no UPS shipping rates
        if (!is_array($containers) || count($containers) <= 0) {
            return array();
        }

        $pounds = 0;
        foreach ($containers as $container) {
            $pounds += $container->getWeight();
        }
        $pounds = round($pounds, 2);

        // Containers fingerprint
        $fingerprint = $order->ups_online_tools_getItemsFingerprint();

        // check national shipping rates cache
        $fields = array(
            'pounds'              => $pounds,
            'origin_address'      => $originAddress,
            'origin_city'         => $originCity,
            'origin_state'        => $originState,
            'origin_zipcode'      => $originZipCode,
            'origin_country'      => $originCountry,
            'destination_address' => $destinationAddress,
            'destination_city'    => $destinationCity,
            'destination_state'   => $destinationState,
            'destination_zipcode' => $destinationZipCode,
            'destination_country' => $destinationCountry,
            'pickup'              => $options->pickup,
            'fingerprint'         => $fingerprint,
        );

        $cached = $this->_checkCache('ups_online_tools_cache', $fields);
        if ($cached) {
            return $cached;
        }

        $rates = $this->filterEnabled(
            $this->getRatesByQuery(
                $pounds,
                $originAddress,
                $originState,
                $originCity,
                $originZipCode, 
                $originCountry,
                $destinationAddress,
                $destinationState,
                $destinationCity,
                $destinationZipCode,
                $destinationCountry,
                $options,
                $containers
            )
        );

        if (!$this->error) {

            // store the result in cache
            $this->_cacheResult('ups_online_tools_cache', $fields, $rates);

            // add shipping markups
            $rates = $this->serializeCacheRates($rates);
            $rates = $this->unserializeCacheRates($rates);

        } else {
            $this->session->set('ups_rates_error', $this->error);
        }

        return $rates;
    }

    /**
     * Get HTTPS requester 
     * 
     * @return \XLite\Model\HTTPS
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHTTPSRequester()
    {
        $request = new \XLite\Model\HTTPS();
        $request->use_ssl3 = true;
        $request->conttype = 'text/xml';

        return $request;
    }

    public function getRatesByQuery(
        $pounds,
        $originAddress,
        $originState,
        $originCity,
        $originZipCode,
        $originCountry,
        $destinationAddress,
        $destinationState,
        $destinationCity,
        $destinationZipCode,
        $destinationCountry,
        $options,
        array $containers = array()
    ) {

        $https = $this->getHTTPSRequester();
        $https->url = $options->server . 'Rate';
        $https->method = 'POST';
        $https->urlencoded = true;

        $request = $this->createRequest(
            $pounds,
            $originAddress,
            $originState,
            $originCity,
            $originZipCode,
            $originCountry,
            $destinationAddress,
            $destinationState,
            $destinationCity,
            $destinationZipCode,
            $destinationCountry,
            $options,
            $containers
        );

        $https->data = implode('', array_filter(array_map('trim', explode("\n", $request)), 'strlen'));

        // log UPSOnlineTools request
        $this->logger->log('UPSOnlineTools request:' . "\n" . $request);

        if ($https->request() == \XLite\Model\HTTPS::HTTPS_ERROR) {
            $this->logger->log('HTTPS_ERROR: ' . $https->error);
            $this->error = $https->error;

            return array();
        }

        $destination = $originCountry == $destinationCountry ? 'L' : 'I';

        // log UPSOnlineTools response
        $response_log = str_replace('><', '>' . "\n" . '<', $https->response);
        $this->logger->log('UPSOnlineTools response:' . "\n" . $response_log);

        preg_match('/<.*[^>].*>/msx', $https->response, $res);

        return $this->parseResponse($res[0], $destination, $originCountry);
    }

    protected function createRequest(
        $pounds,
        $originAddress,
        $originState,
        $originCity,
        $originZipCode,
        $originCountry,
        $destinationAddress,
        $destinationState,
        $destinationCity,
        $destinationZipCode,
        $destinationCountry,
        $options,
        array $containers = array()
    ) {

        $customer_classification_code = $options->customer_classification_code;

        if ($originCountry == 'US' && !empty($customer_classification_code)) {
            $customer_classification_query = <<<EOT
    <CustomerClassification>
        <Code>$customer_classification_code</Code>
    </CustomerClassification>
EOT;
        }

        // Residential / commercial address indicator
        $residental_flag = '';
        if ($options->residential == 'Y') {
            $residental_flag = "\t\t\t\t" . '<ResidentialAddressIndicator/>';
        }

        $upsAccessKey = $options->UPS_accesskey;
        $upsUserName = $options->UPS_username;
        $upsPassword = $options->UPS_password;
        $pickup_type = $options->pickup_type;

        $packages = '';
        foreach ($containers as $container) {

            // get container dimnsions & weight
            list($width, $length, $height) = $container->getDimensions();
            $weight = $container->getWeight();

            // get packaging from the container details
            $packaging_type = $container->getContainerType();

            if ($packaging_type == 1) {
                $weight = 0.0; // UPS Letter
            }

            $inches_lbs = true;
            if (!in_array($originCountry, array('DO', 'PR', 'US'))) {
                $width = $width * 2.54;
                $length = $length * 2.54;
                $height = $height * 2.54;
                $weight = $weight / 2.20462;

                $inches_lbs = false;
            }

            $width = $this->formatCurrency(doubleval($width));
            $length = $this->formatCurrency(doubleval($length));
            $height = $this->formatCurrency(doubleval($height));

            $weight = max(self::MIN_PACKAGE_WEIGHT, $this->formatCurrency(doubleval($weight)));

            // Set Additional Handling option
            $options->AH = $container->isAdditionalHandling();

            // set declared value
            $options->iv_amount = $container->getDeclaredValue();
            $options->iv_currency = $options->currency_code ? $options->currency_code : 'USD';

            $packages .= $this->createPackage(
                $width, 
                $length,
                $height,
                $originCountry,
                $destinationCountry,    
                $packaging_type,
                $inches_lbs,
                $weight,
                $options
            );
        }

        $request = <<<EOT
<?xml version="1.0"?>
<AccessRequest xml:lang="en-US">
    <AccessLicenseNumber>$upsAccessKey</AccessLicenseNumber>
    <UserId>$upsUserName</UserId>
    <Password>$upsPassword</Password>
</AccessRequest>
<?xml version="1.0"?>
<RatingServiceSelectionRequest xml:lang="en-US">
    <Request>
        <TransactionReference>
            <CustomerContext>Rating and Service</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>Rate</RequestAction>
        <RequestOption>shop</RequestOption>
    </Request>
    <PickupType>
        <Code>$pickup_type</Code>
    </PickupType>
$customer_classification_query
    <Shipment>
        <Shipper>
            <Address>
                <AddressLine1>$originAddress</AddressLine1>
                <City>$originCity</City>
                <StateProvinceCode>$originState</StateProvinceCode>
                <PostalCode>$originZipCode</PostalCode>
                <CountryCode>$originCountry</CountryCode>
            </Address>
        </Shipper>
        <ShipFrom>
            <Address>
                <AddressLine1>$originAddress</AddressLine1>
                <City>$originCity</City>
                <StateProvinceCode>$originState</StateProvinceCode>
                <PostalCode>$originZipCode</PostalCode>
                <CountryCode>$originCountry</CountryCode>
            </Address>
        </ShipFrom>
        <ShipTo>
            <Address>
                <City>$destinationCity</City>
                <StateProvinceCode>$destinationState</StateProvinceCode>
                <PostalCode>$destinationZipCode</PostalCode>
                <CountryCode>$destinationCountry</CountryCode>
$residental_flag
            </Address>
        </ShipTo>
$packages
    </Shipment>
</RatingServiceSelectionRequest>
EOT;

        return $request;
    }

    protected function createPackage(
        $width,
        $length,
        $height,
        $originCountry,
        $destinationCountry,
        $packaging_type,
        $inches_lbs,
        $weight,
        $options
    ) {
        $pkgparams = '';

        if ($inches_lbs) {
            $wunit = 'LBS';
            $dunit = 'IN';

        } else {
            $wunit = 'KGS';
            $dunit = 'CM';
        }

        $insvalue = $this->formatCurrency(doubleval($options->iv_amount));

        $pkgopt = array();
        $srvopts = array();
        if ($insvalue > 0.1) {
            $iv_currency = $options->iv_currency;

            $pkgopt[] = <<<EOT
                <InsuredValue>
                    <CurrencyCode>$iv_currency</CurrencyCode>
                    <MonetaryValue>$insvalue</MonetaryValue>
                </InsuredValue>

EOT;
        }

        // delivery confirmation
        $delivery_conf = intval($options->delivery_conf);
        if ($delivery_conf > 0 && $delivery_conf < 4) {
            $tmp = <<<EOT
                <DeliveryConfirmation>
                    <DCISType>$delivery_conf</DCISType>
                </DeliveryConfirmation>
EOT;

            if ($originCountry != 'US') {
                $srvopts[] = $tmp;

            } else {
                $pkgopt[] = $tmp;
            }

        }

        // combine package service options
        if (count($pkgopt) > 0) {
            $pkgparams .= "\t\t\t" . '<PackageServiceOptions>' . "\n"
            . implode('', $pkgopt) . "\n"
            . "\t\t\t" . '</PackageServiceOptions>' . "\n";
        }

        $upsoptions = $options->upsoptions;
        if (is_array($upsoptions) && count($upsoptions) > 0) {
            foreach ($upsoptions as $opt=>$val) {
                if ($val != 'Y') {
                     continue;
                }

                switch ($opt) {
                    case 'SP':
                        $srvopts[] = "\t\t\t\t" . '<SaturdayPickupIndicator/>' . "\n";
                        break;

                    case 'SD':
                        $srvopts[] = "\t\t\t\t" . '<SaturdayDeliveryIndicator/>' . "\n";
                        break;
                }
            }
        }

        if ($options->AH) {
            $pkgparams .= "\t\t\t" . '<AdditionalHandling/>' . "\n";
        }

        // combine shipment service options
        if (count($srvopts) > 0) {
            $pkgparams .= "\t\t\t" . '<ShipmentServiceOptions>' . "\n"
                . implode('', $srvopts) . "\n"
                . "\t\t\t" . '</ShipmentServiceOptions>';
        }

        $packaging_type = sprintf('%02d', $packaging_type);

        $girth = $length + (2 * $width) + (2 * $height);

        $largePackageXML = '';
        if ($girth > 130) {
            $largePackageXML = '<LargePackageIndicator />';
        }

        // create package
        $package = <<<EOT
        <Package>
            <PackagingType>
                <Code>$packaging_type</Code>
            </PackagingType>
            <PackageWeight>
                <UnitOfMeasurement>
                    <Code>$wunit</Code>
                </UnitOfMeasurement>
                <Weight>$weight</Weight>
            </PackageWeight>
            <Dimensions>
                <UnitOfMeasurement>
                    <Code>$dunit</Code>
                </UnitOfMeasurement>
                <Length>$length</Length>
                <Width>$width</Width>
                <Height>$height</Height>
            </Dimensions>
            $largePackageXML
$pkgparams
        </Package>

EOT;

        return $package;
    }

    /**
     * Parse response 
     * 
     * @param string $response      Response
     * @param string $destination   Destination code
     * @param string $originCountry Origination country code
     *  
     * @return array(\XLite\Model\ShippingRate)
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($response, $destination, $originCountry)
    {
        // original code
        $this->error = '';
        $this->xmlError = false;

        $dom = new DOMDocument();
        $result = @$dom->loadXML($response, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_ERR_NONE);
        $this->xpath = new DOMXPath($dom);

        if (!$result) {
            $this->error = $xml->error;
            $this->xmlError = true;
            $this->response = $response;

            return array();
        }

        // check for errors
        $response = $this->xpath->query('//RatingServiceSelectionResponse/Response')->item(0);
        if (!$this->getXMLNodeValue('ResponseStatusCode', $response)) {
            $this->error = 'UPS error #' . $this->getXMLNodeValue('Error/ErrorCode', $response) . ': '
                . $this->getXMLNodeValue('Error/ErrorDescription', $response);

            return array();
        }

        // enumerate services
        $rates = array();
        $currency = $this->getOptions()->currency_code;

        foreach ($this->xpath->query('//RatingServiceSelectionResponse/RatedShipment') as $service) {
            $serviceCode = $this->getXMLNodeValue('Service/Code', $service);
            $serviceName = $this->getServiceName($serviceCode, $originCountry);

            if (is_null($serviceName)) {
                // there's no known service for that code
                continue;
            }

            $shipping = $this->getService('ups', 'UPS ' . $serviceName, $destination);
            $shipping_id = $shipping->get('shipping_id');

            // to prevent create not-complete shipping object
            $shipping = new \XLite\Model\Shipping($shipping_id);
            $shipping->set('nameUPS', $this->getNameUPS($shipping->get('name')));

            $id = $shipping->get('shipping_id');
            $rates[$id] = new \XLite\Model\ShippingRate($shipping_id);
            $rates[$id]->shipping = $shipping;
            $rates[$id]->rate = doubleval(
                trim(
                    $this->getXMLNodeValue('TotalCharges/MonetaryValue', $service)
                )
            );

            $currentCurrency = $this->getXMLNodeValue('TotalCharges/CurrencyCode', $service);
            if ($currency != $currentCurrency) {
                $this->setConfig('currency_code', $currentCurrency);
                $currency = $currentCurrency;
            }

        }

        return $rates;
    }

    /**
     * Prepare shipping method name
     * 
     * @param string $name Method name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNameUPS($name)
    {
        if (in_array($name, array('UPS Express Plus', 'UPS Today Express Saver'))) {
            return $name;
        }

        $replace = array(
            '<sup>SM</sup>' => array(
                'Plus',
                'Select',
                'Worldwide Express',    
                'Expedited',
                'Express Saver',
                'View Notify',
                'Quantum View', 
                'Today Standard',
                'Dedicated Courrier',
            ),
            '&reg' => array(
                'Air Early A.M.',
                'Day Air A.M.',
                'Air',
                'Air Saver',    
                'Pickup',
                'Box',
                'WorldShip',
                'OnLine',
            ),
        );

        foreach ($replace as $sign => $marks) {
            foreach ($marks as $key) {
                if (preg_match('/' . $key . '$/S', $name)) {
                    $name = preg_replace('/' . $key . '$/S', $key . $sign, $name);
                    break;
                }
            }
        }

        $name = str_replace('/2(nd)/', '2<sup>nd</sup>', $name);

        return str_replace('/Air Early/', 'Air&reg; Early', $name);
    }

    /**
     * Get options 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        static $options = null;

        if (!isset($options)) {
            $options = $this->config->UPSOnlineTools;
            foreach (array('UPS_username', 'UPS_password', 'UPS_accesskey') as $name) {
                $val = $options->$name;
                $val = $this->decode($val);
                $options->$name = $val;
            }
        }

        switch ($options->account_type) {
            case '01':
                $options->customer_classification_code = '01';
                $options->pickup_type = '01';
                break;

            case '02':
                $options->customer_classification_code = '03';
                $options->pickup_type = '03';
                break;

            default:
                $options->customer_classification_code = '04';
                $options->pickup_type = '11';
        }

        return $options;
    }

    /**
     * Get services list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getServices()
    {
        return array(
            '01' => array(
                'Next Day Air' => array('US', 'PR'),
                'Express'      => array('CA'),
            ),
            '02' => array(
                '2nd Day Air'  => array('US', 'PR'),
            ),
            '03' => array(
                'Ground'       => array('US', 'PR'),
            ),
            '07' => array(
                'Worldwide Express' => array('US', 'PR', 'CA'),
                'Express'           => array('EU', 'PL', 'MX'),
            ),
            '08' => array(
                'Worldwide Expedited' => array('US', 'PR', 'CA', 'OTHER_ORIGINS'),
                'Expedited'           => array('MX'),
            ),
            '11' => array(
                'Standard' => array('US', 'CA', 'EU', 'PL'),
            ),
            '12' => array(
                '3 Day Select' => array('US', 'CA'),
            ),
            '13' => array(
                'Next Day Air Saver' => array('US'),
                'Saver'              => array('CA'),
            ),
            '14' => array(
                'Next Day Air Early A.M.' => array('US', 'PR'),
                'Express Early A.M.'      => array('CA'),
            ),
            '54' => array(
                'Worldwide Express Plus' => array('US', 'PR', 'PL', 'EU', 'OTHER_ORIGINS'),
                'Express Plus'           => array('MX'),
            ),
            '59' => array(
                '2nd Day Air A.M.' => array('US'),
            ),
            '65' => array(
                'Saver' => array('US', 'PR', 'CA', 'MX', 'PL', 'EU', 'OTHER_ORIGINS'),
            ),
            '82' => array(
                'Today Standard' => array('PL'),
            ),
            '83' => array(
                'Today Dedicated Courrier' => array('PL'),
            ),
            '84' => array(
                'Today Intercity' => array('PL'),
            ),
            '85' => array(
                'Today Express' => array('PL'),
            ),
            '86' => array(
                'Today Express Saver' => array('PL'),
            ),
        );
    }

    /**
     * Get service name 
     * 
     * @param string $serviceCode   Service code
     * @param string $originCountry Origination country code
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getServiceName($serviceCode, $originCountry)
    {
        switch ($originCountry) {
            case 'US':
                $origin = 'US';
                break;

            case 'PR':
                $origin = 'PR';
                break;

            case 'MX':
                $origin = 'MX';
                break;

            case 'CA':
                $origin = 'CA';
                break;

            case 'PL':
                $origin = 'PL';
                break;

            default:
                $country = \XLite\Core\Database::getEM()->find('\XLite\Model\Country', $originCountry);
                $origin = $country->isEUMember() ? 'EU' : 'OTHER_ORIGINS';
                break;
        }

        $result = null;

        if (isset($this->services[$serviceCode])) {
            foreach ($this->services[$serviceCode] as $service => $origins) {
                if (in_array($origin, $origins)) {
                    $result = $service;
                    break;
                }
            }
        }

        return $result;
    }

    public function checkAddress(
        $shipping_country,
        $shipping_state,
        $shipping_custom_state,
        $shipping_city,
        $shipping_zipcode,
        &$av_result,    
        &$request_result
    ) {

        $options = $this->getOptions();

        if ($options->av_status == 'Y' && $shipping_country == 'US') {

            if ($shipping_state > 0) {
                $state_code = \XLite\Core\Database::getRepo('\XLite\Model\State')->getCodeById($shipping_state);

            } else {
                $state_code = $shipping_custom_state;
            }

            if (!array_key_exists($state_code, $this->getUPSStates())) {
                $state_code = '';
            }

            $required_quality = $options->av_quality;
            $av_error = 1;

        } else {
            return true;
        }

        $request = <<<EOT
<?xml version="1.0"?>
<AddressValidationRequest xml:lang="en-US">
    <Request>
        <TransactionReference>
            <CustomerContext>Address validation request</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>AV</RequestAction>
    </Request>
    <Address>
        <City>$shipping_city</City>
        <StateProvinceCode>$state_code</StateProvinceCode>
        <PostalCode>$shipping_zipcode</PostalCode>
    </Address>
</AddressValidationRequest>
EOT;

        $result = $this->request($request, 'postprocessCheckAddress', 'AV', true);

        $request_result = $result;

        if ($this->error || $result['statuscode'] != '1') {
            return false;
        }

        $quality_factors = array (
            'exact'      => array('min' => 1.00, 'max' => 1.00, 'rank' => 5),
            'very_close' => array('min' => 0.95, 'max' => 0.99, 'rank' => 4),
            'close'      => array('min' => 0.90, 'max' => 0.94, 'rank' => 3),
            'possible'   => array('min' => 0.70, 'max' => 0.89, 'rank' => 2),
            'poor'       => array('min' => 0.00, 'max' => 0.69, 'rank' => 1),
        );

        foreach ($quality_factors as $k=>$v) {
            if ($result['address'][0]['QUALITY'] >= $v['min'] && $result['address'][0]['QUALITY'] <= $v['max']) {
                $quality = $k;
                break;
            }
        }

        $address_is_valid = $quality_factors[$quality]['rank'] >= $quality_factors[$required_quality]['rank'];

        if ($address_is_valid) {
            return true;
        }

        $index = 0;
        foreach ($result['address'] as $k => $v) {
            if ($v['POSTALCODELOWEND'] != $v['POSTALCODEHIGHEND']) {

                $max = intval($v['POSTALCODEHIGHEND']) - $v['POSTALCODELOWEND'];

                for ($i = 0; $i <= $max; $i++) {
                    $av_result[$index]['city'] = $v['ADDRESS']['CITY'];
                    $av_result[$index]['state'] = $v['ADDRESS']['STATEPROVINCECODE'];
                    $av_result[$index]['zipcode'] = $v['POSTALCODELOWEND'] + $i;

                    $index++;
                }

            } else {
    
                $av_result[$index]['city'] = $v['ADDRESS']['CITY'];
                $av_result[$index]['state'] = $v['ADDRESS']['STATEPROVINCECODE'];
                $av_result[$index]['zipcode'] = $v['POSTALCODELOWEND'];

                $index++;
            }
        }

        return false;
    }

    /**
     * HTTPS request 
     * 
     * @param string  $request  XML string
     * @param string  $func     Postprocessing method name
     * @param string  $tool     UPS tool name
     * @param boolean $use_auth Authentication flag OPTIONAL
     *  
     * @return array 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function request($request, $func, $tool, $use_auth = true)
    {
        $options = $this->getOptions();

        if ($use_auth) {

            $upsAccessKey = $options->UPS_accesskey;
            $upsUserName = $options->UPS_username;
            $upsPassword = $options->UPS_password;

            $request = <<<EOT
<?xml version="1.0"?>
<AccessRequest>
    <AccessLicenseNumber>$upsAccessKey</AccessLicenseNumber>
    <UserId>$upsUserName</UserId>
    <Password>$upsPassword</Password>
</AccessRequest>
$request
EOT;
        }

        $https = $this->getHTTPSRequester();
        $https->url = $options->server . $tool;
        $https->method = 'POST';
        $https->urlencoded = true;
        $https->data = $request;

        if ($https->request() == \XLite\Model\HTTPS::HTTPS_ERROR) {
            $this->error = 'Connection failed';
            return array();
        }

        preg_match('/<.*[^>].*>/msx', $https->response, $res);
        $_response = $res[0];

        $xml = $this->getObjectXML();
        $tree = $xml->parse($_response);
        if (!$tree) {
            $this->error = $xml->error;
            $this->xmlError = true;
            $this->response = $xml->xml;

            return array();
        }

        return !is_null($func) ? $this->$func($tree) : $tree;
    }

    /**
     * Postprocess check address response
     * 
     * @param array $result Response
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessCheckAddress(array $result)
    {
        $return = $this->postprocessResponseCell($result['ADDRESSVALIDATIONRESPONSE']['RESPONSE']);

        if (is_array($result['ADDRESSVALIDATIONRESPONSE'])) {
            foreach ($result['ADDRESSVALIDATIONRESPONSE'] as $key => $val) {
                if (strpos($key, 'ADDRESSVALIDATIONRESULT') !== false) {
                    if (!isset($return['address'])) {
                        $return['address'] = array();
                    }

                    $return['address'][] = $val;
                }
            }
        }

        return $return;
    }

    /**
     * Postprocess address verification cell 
     * 
     * @param array $result Cell
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessResponseCell(array $result)
    {
        return array(
            'statuscode'  => $result['RESPONSESTATUSCODE'],
            'statusdescr' => $result['RESPONSESTATUSDESCRIPTION'],
            'errorcode'   => $result['ERROR']['ERRORCODE'],
            'errordescr'  => $result['ERROR']['ERRORDESCRIPTION'],
        );
    }

    /**
     * Get countries codes
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUPSCountries()
    {
        return array(
            'AR' => 'Argentina',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BR' => 'Brazil',
            'CA' => 'Canada',
            'CL' => 'Chile',
            'CR' => 'Costa Rica',
            'DK' => 'Denmark',
            'DO' => 'Dominican Republic',
            'FI' => 'Finland',
            'FR' => 'France',
            'DE' => 'Germany',
            'GR' => 'Greece',
            'GT' => 'Guatemala',
            'HK' => 'Hong Kong',
            'IN' => 'India',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JP' => 'Japan',
            'LU' => 'Luxembourg',
            'MY' => 'Malaysia',
            'MX' => 'Mexico',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NO' => 'Norway',
            'PA' => 'Panama',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'RO' => 'Romania',
            'SG' => 'Singapore',
            'ZA' => 'South Africa',
            'KR' => 'South Korea',
            'ES' => 'Spain',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'GB' => 'United Kingdom (Great Britain)',
            'US' => 'United States',
            'VI' => 'United States Virgin Islands',
            'VE' => 'Venezuela',
        );
    }

    /**
     * Get states codes
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUPSStates()
    {
        return array(
            'AB' => 'Alberta (Canada)',
            'BC' => 'British Columbia (Canada)',
            'MB' => 'Manitoba (Canada)',
            'NB' => 'New Brunswick (Canada)',
            'NF' => 'Newfoundland/Labrador (Canada)',
            'NS' => 'Nova Scotia (Canada)',
            'NT' => 'NWT/Nunavut (Canada)',
            'ON' => 'Ontario (Canada)',
            'PE' => 'Prince Edward Island (Canada)',
            'QC' => 'Quebec (Canada)',
            'SK' => 'Saskatchewan (Canada)',
            'YT' => 'Yukon (Canada)',
            'AA' => 'Armed Forces Americas (US)',
            'AE' => 'Armed Forces Europe (US)',
            'AL' => 'Alabama (US)',
            'AK' => 'Alaska (US)',
            'AP' => 'Armed Forces Pacific (US)',
            'AZ' => 'Arizona (US)',
            'AR' => 'Arkansas (US)',
            'CA' => 'California (US)',
            'CO' => 'Colorado (US)',
            'CT' => 'Connecticut (US)',
            'DE' => 'Delaware (US)',
            'DC' => 'District of Columbia (US)',
            'FL' => 'Florida (US)',
            'GA' => 'Georgia (US)',
            'GU' => 'Guam (US)',
            'HI' => 'Hawaii (US)',
            'ID' => 'Idaho (US)',
            'IL' => 'Illinois (US)',
            'IN' => 'Indiana (US)',
            'IA' => 'Iowa (US)',
            'KS' => 'Kansas (US)',
            'KY' => 'Kentucky (US)',
            'LA' => 'Louisiana (US)',
            'ME' => 'Maine (US)',
            'MD' => 'Maryland (US)',
            'MA' => 'Massachusetts (US)',
            'MI' => 'Michigan (US)',
            'MN' => 'Minnesota (US)',
            'MS' => 'Mississippi (US)',
            'MO' => 'Missouri (US)',
            'MT' => 'Montana (US)',
            'NE' => 'Nebraska (US)',
            'NV' => 'Nevada (US)',
            'NH' => 'New Hampshire (US)',
            'NJ' => 'New Jersey (US)',
            'NM' => 'New Mexico (US)',
            'NY' => 'New York (US)',
            'NC' => 'North Carolina (US)',
            'ND' => 'North Dakota (US)',
            'OH' => 'Ohio (US)',
            'OK' => 'Oklahoma (US)',
            'OR' => 'Oregon (US)',
            'PA' => 'Pennsylvania (US)',
            'RI' => 'Rhode Island (US)',
            'SC' => 'South Carolina (US)',
            'SD' => 'South Dakota (US)',
            'TN' => 'Tennessee (US)',
            'TX' => 'Texas (US)',
            'UT' => 'Utah (US)',
            'VI' => 'Virgin Islands (US)',
            'VT' => 'Vermont (US)',
            'VA' => 'Virginia (US)',
            'WA' => 'Washington (US)',
            'WV' => 'West Virginia (US)',
            'WI' => 'Wisconsin (US)',
            'WY' => 'Wyoming (US)',
        );
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        $value = parent::get($name);

        if ('name' == $name) {
            $value = $this->getNameUPS($value);
        }

        return $value;
    }

    /**
     * Get UPS containers list 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUPSContainersList()
    {
        return array(
            1  => 'UPS Letter / UPS Express Envelope',
            2  => 'Package',
            3  => 'UPS Tube',
            4  => 'UPS Pak',
            21 => 'UPS Express Box',
            24 => 'UPS 25 Kg Box&reg;',
            25 => 'UPS 10 Kg Box&reg;',
            30 => 'Pallet',
        );
    }

    /**
     * Get UPS container dimensions
     * 
     * @param integer $index      Container code
     * @param boolean $inches_lbs Use inches / lbs. istead kg. / sm. OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUPSContainerDims($index, $inches_lbs = true)
    {
        $dims = array();

        // all dimension/weight set in inches/lbs
        switch ($index) {
            case '1':   // UPS Letter / UPS Express Envelope
                $dims = array(
                    'name'         => 'UPS Letter / UPS Express Envelope',
                    'width'        => 12.5,
                    'length'       => 9.5,
                    'height'       => 0.25,
                    'weight_limit' => 0,
                );
                break;

            case '3':   // UPS Tube
                $dims = array(
                    'name'         => 'UPS Tube',
                    'width'        => 38,
                    'length'       => 6,
                    'height'       => 6,
                    'weight_limit' => 0,
                );
                break;

            case '4':   // UPS Pak
                $dims = array(
                    'name'         => 'UPS Pak',
                    'width'        => 16,
                    'length'       => 12.75,
                    'height'       => 2,
                    'weight_limit' => 0,
                );
                break;

            case '21':  // UPS Express Box
                $dims = array(
                    'name'         => 'UPS Express Box',
                    'width'        => 18,
                    'length'       => 13,
                    'height'       => 3,
                    'weight_limit' => 30,
                );
                break;

            case '24':  // UPS 25kg Box
                $dims = array(
                    'name'         => 'UPS 25kg Box&reg;',
                    'width'        => 19.375,
                    'length'       => 17.375,
                    'height'       => 14,
                    'weight_limit' => 55.1,
                );
                break;

            case '25':  // UPS 10kg Box
                $dims = array(
                    'name'         => 'UPS 10kg Box&reg;',
                    'width'        => 16.5,
                    'length'       => 13.25,
                    'height'       => 10.75,
                    'weight_limit' => 22,
                );
                break;

            case '30':  // Pallet
                $dims = array(
                    'name'         => 'Pallet',
                    'width'        => $this->config->UPSOnlineTools->width,
                    'length'       => $this->config->UPSOnlineTools->length,
                    'height'       => $this->config->UPSOnlineTools->height,
                    'weight_limit' => 150,
                );
                break;

            case '2':   // Your (user-defined) package
            default:
                $dims = array(
                    'name'         => 'Your package',
                    'width'        => $this->config->UPSOnlineTools->width,
                    'length'       => $this->config->UPSOnlineTools->length,
                    'height'       => $this->config->UPSOnlineTools->height,
                    'weight_limit' => 0,
                );
                break;
        }

        if ($inches_lbs) {
            $dims['units'] = 'inches/lbs';
            $dims['length_unit'] = 'inches';
            $dims['weight_unit'] = 'lbs';

        } else {
            $dims['width']        = round($dims['width'] * 2.54);
            $dims['height']       = round($dims['height'] * 2.54);
            $dims['length']       = round($dims['length'] * 2.54);
            $dims['weight_limit'] = round($dims['weight_limit'] / 2.2);
            $dims['units']        = 'kg/sm';
            $dims['length_unit']  = 'sm';
            $dims['weight_unit']  = 'kg';
        }

        return $dims;
    }

    /**
     * Get license 
     * 
     * @param string &$license License
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLicense(&$license) 
    {
        $time = time();

        if ($this->session->get('ups_license_update_time') + 3600 < $time) {
            $this->session->set('ups_license', '');
        }

        $license = $this->session->get('ups_license');
        if ($license) {
            return 0;
        }

        $devlicense = $this->config->UPSOnlineTools->devlicense;

        $request = <<<EOT
<?xml version="1.0" encoding="ISO-8859-1"?>
<AccessLicenseAgreementRequest>
    <Request>
        <TransactionReference>
            <CustomerContext>License Test</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>AccessLicense</RequestAction>
        <RequestOption></RequestOption>
    </Request>
    <DeveloperLicenseNumber>$devlicense</DeveloperLicenseNumber>
    <AccessLicenseProfile>
        <CountryCode>US</CountryCode>
        <LanguageCode>EN</LanguageCode>
    </AccessLicenseProfile>
    <OnLineTool>
        <ToolID>TrackXML</ToolID>
        <ToolVersion>1.0</ToolVersion>
    </OnLineTool>
</AccessLicenseAgreementRequest>
EOT;

        $result = $this->request($request, null, 'License', false);

        if ($this->error) {
            $this->session->set('ups_license', '');
            $this->session->set('ups_license_update_time', 0);

            return 1;
        }

        $license = $result['ACCESSLICENSEAGREEMENTRESPONSE']['ACCESSLICENSETEXT'];

        $this->session->set('ups_license', $license);
        $this->session->set('ups_license_update_time', $time);

        return 0;
    }

    /**
     * Preprocess agreement 
     * 
     * @param string &$license License
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAgreement(&$license) 
    {
        if ($this->getLicense($license)) {
            $license = '<div align="justify"><font style="FONT-FAMILY: Courier; FONT-SIZE: 10px;">'
                . 'Sorry, license agreement is temporary unavailable. Try again later.' 
                . '</font></div>';

            return 1;
        }

        $license = preg_replace('/\s([0-9]{1,2}\.[0-9]*)([^0-9]+)/U', '<br /><br /><b />\1</b>\2\3', $license);
        $license = preg_replace('/([^a-zA-Z]+)([\s]+)(\([a-h]+\))/', '\1\2<br /><br /><b>\3</b>', $license);
        $license = preg_replace('/(\(\"UPS\"\).)[\s]*(This)/', '\1<br /><br />\2', $license);
        $license = str_replace('DO YOU AGREE', '<br /><br />DO YOU AGREE', $license);
        $license = '<div align="justify"><font style="FONT-FAMILY: Courier; FONT-SIZE: 10px;">'
            . $license
            . '</font></div>';

        return 0;
    }

    /**
     * Get shipping cache expiration 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingCacheExpiration()
    {
        return $this->config->UPSOnlineTools->cache_autoclean * 86400;
    }

    /**
     * Get XML parser
     * 
     * @return \XLite\Module\CDev\UPSOnlineTools\Model\XML
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getObjectXML()
    {
        return new \XLite\Model\XML();
    }

    /**
     * Get key hash (unqiue) 
     * 
     * @param integer $pos    Hash position
     * @param integer $length Hash length
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getKeyHash($pos, $length)
    {
        return substr(md5(uniqid(rand())), $pos, $length);
    }

    /**
     * Decode string
     * 
     * @param string $s String
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function decode($s)
    {
        $enc = \XLite\Module\CDev\UPSOnlineTools\Main::CRYPT_SALT ^ $this->decodeChar($s, 0);
        $result = '';
        // $i=2 to skip salt
        for ($i = 2; $i < strlen($s); $i += 2) {
            $result .= chr($this->decodeChar($s, $i) ^ $enc++);
            if ($enc > 255) {
                $enc = 0;
            }
        }

        return $result;
    }

    /**
     * Decode character
     * 
     * @param string  $s String
     * @param integer $i Position
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function decodeChar($s, $i)
    {
        return (ord(substr($s, $i, 1)) - \XLite\Module\CDev\UPSOnlineTools\Main::START_CHAR_CODE) * 16
            + ord(substr($s, $i + 1, 1)) - \XLite\Module\CDev\UPSOnlineTools\Main::START_CHAR_CODE;
    }

    /**
     * Encode string
     * 
     * @param string $s String
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function encode($s)
    {
        $enc = rand(1, 255);
        $result = $this->encodeChar($enc);
        $enc ^= \XLite\Module\CDev\UPSOnlineTools\Main::CRYPT_SALT;
        for ($i = 0; $i < strlen($s); $i++) {
            $r = ord(substr($s, $i, 1)) ^ $enc++;
            if ($enc > 255) {
                $enc = 0;
            }

            $result .= $this->encodeChar($r);
        }

        return $result;
    }

    /**
     * Encode character
     * 
     * @param string $c Character
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function encodeChar($c)
    {
        return chr(\XLite\Module\CDev\UPSOnlineTools\Main::START_CHAR_CODE + ($c & 240) / 16)
            . chr(\XLite\Module\CDev\UPSOnlineTools\Main::START_CHAR_CODE + ($c & 15));
    }

    /**
     * Get XML node value by XPath query
     * 
     * @param string  $query      XPath query
     * @param DOMNode $parentNode Context node OPTIONAL
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getXMLNodeValue($query, $parentNode = null)
    {
        $result = $parentNode
            ? $this->xpath->query($query, $parentNode)
            : $this->xpath->query($query);

        return $result->length ? $result->item(0)->nodeValue : null;
    }

}
