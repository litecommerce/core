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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Core;

/**
 * Marketplace 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Marketplace extends \XLite\Base\Singleton
{
    /**
     * Marketplace request types
     */
    const ACTION_GET_CORE_VERSIONS = 'get_core_versions';
    const ACTION_GET_CORE_PACK     = 'get_core_pack';
    const ACTION_GET_CORE_HASH     = 'get_core_hash';
    const ACTION_GET_ADDONS_LIST   = 'get_addons_list';
    const ACTION_GET_ADDON_PACK    = 'get_addon_pack';
    const ACTION_GET_ADDON_LICENSE = 'get_addon_license';
    const ACTION_GET_ADDON_INFO    = 'get_addon_info';

    /**
     * Protocol data fields - request
     */
    const REQUEST_FIELD_VERSION_MAJOR        = 'major';
    const REQUEST_FIELD_VERSION_MINOR        = 'minor';
    const REQUEST_FIELD_VERSION_CORE_CURRENT = 'currentCoreVersion';
    const REQUEST_FIELD_VERSION_CORE         = 'coreVersion';
    const REQUEST_FIELD_VERSION_MODULE       = 'moduleVersion';
    const REQUEST_FIELD_IS_PACK_GZIPPED      = 'gzipped';
    const REQUEST_FIELD_MODULE_ID            = 'moduleID';
    const REQUEST_FIELD_MODULE_KEY           = 'key';

    /**
     * Protocol data fields - response
     */
    const RESPONSE_FIELD_ERROR   = 'error';
    const RESPONSE_FIELD_MESSAGE = 'message';


    /**
     * Error code
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $errorCode;

    /**
     * Error message
     *
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $errorMessage;


    // {{{ Interface: public methods (wrappers)

    /**
     * Return markeplace URL
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarketplaceURL()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('marketplace', 'url'));
    }

    /**
     * The "get_core_versions" request handler
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCoreVersions()
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_CORE_VERSIONS
        );
    }

    /**
     * The "get_core_pack" request handler
     * 
     * @param string $versionMajor Major version of core to get
     * @param string $versionMinor Minor version of core to get
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCorePack($versionMajor, $versionMinor)
    {
        // :TODO: add check for GZ
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_CORE_PACK,
            array(
                self::REQUEST_FIELD_VERSION_CORE    => $this->getVersionField($versionMajor, $versionMinor),
                self::REQUEST_FIELD_IS_PACK_GZIPPED => 0,
            )
        );
    }

    /**
     * The "get_core_hash" request handler
     *
     * @param string $versionMajor Major version of core to get
     * @param string $versionMinor Minor version of core to get
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCoreHash($versionMajor, $versionMinor)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_CORE_HASH,
            array(
                self::REQUEST_FIELD_VERSION_CORE => $this->getVersionField($versionMajor, $versionMinor),
            )
        );
    }

    /**
     * The "get_addons_list" request handler
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddonsList()
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDONS_LIST
        );
    }

    /**
     * The "get_addon_pack" request handler
     * 
     * @param string $moduleID External module identifier
     * @param string $key      Module license key OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddonPack($moduleID, $key = null)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDON_PACK,
            array(
                self::REQUEST_FIELD_MODULE_ID  => $moduleID,
                self::REQUEST_FIELD_MODULE_KEY => $key,
            )
        );
    }

    /**
     * The "get_addon_license" request handler
     *
     * @param string $moduleID External module identifier
     * @param string $key      Module license key OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddonLicense($moduleID, $key = null)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDON_LICENSE,
            array(
                self::REQUEST_FIELD_MODULE_ID  => $moduleID,
                self::REQUEST_FIELD_MODULE_KEY => $key,
            )
        );
    }

    /**
     * The "get_addon_info" request handler
     *
     * @param string $moduleID External module identifier
     * @param string $key      Module license key OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddonInfo($moduleID, $key = null)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDON_INFO,
            array(
                self::REQUEST_FIELD_MODULE_ID  => $moduleID,
                self::REQUEST_FIELD_MODULE_KEY => $key,
            )
        );
    }

    // }}}

    // {{{ Protocol (handlers)

    /**
     * Common data for all request types
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCommonData()
    {
        return array(
            self::REQUEST_FIELD_VERSION_CORE_CURRENT => $this->getVersionField(
                \XLite::getInstance()->getMajorVersion(),
                \XLite::getInstance()->getMinorVersion()
            ),
        );
    }

    /**
     * Send request to marketplace endpoint and return the response
     * 
     * @param string $action Name of the action
     * @param array  $data   Custom data to send in request
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendRequestToMarkeplace($action, array $data = array())
    {
        // Prepare for request
        $this->clearError();

        return $this->prepareResponse($this->getRequest($action, $data)->sendRequest());
    }

    /**
     * Return prepared request object
     *
     * @param string $action Action name
     * @param array  $data   Request data
     *
     * @return \XLite\Model\HTTPS
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequest($action, array $data = array())
    {
        $request = new \XLite\Core\HTTP\Request($this->getMarketplaceActionURL($action));
        $request->body = $data + $this->getRequestData($action);

        return $request;
    }

    /**
     * Get enpoint URL for certain action
     * 
     * @param string $action Action name
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMarketplaceActionURL($action)
    {
        return \Includes\Utils\Converter::trimTrailingChars($this->getMarketplaceURL(), '/') . '/' . $action;
    }

    /**
     * Return data for current request
     *
     * @param string $action Action name
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequestData($action)
    {
        $data = $this->getCommonData();

        if (method_exists($this, $method = $this->getMethodToGetRequestData($action))) {

            // For most actions it's not needed to add any custom data
            $data = $this->$method() + $data;
        }

        return $data;
    }

    /**
     * Prepare the marketplace response
     * 
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareResponse(\PEAR2\HTTP\Request\Response $response)
    {
        $result = null;

        if (200 == $response->code) {

            // Check response format
            if (is_null($result = json_decode($response->body, true))) {
                $this->setError(-1, 'Marketplace response is not in JSON format');
            } else {
                $result = $this->parseResponse($result);
            }

        } else {

            // Probably the 404 error
            $this->setError(-1, 'Unable to access marketplace by the URL "' . $request->url . '"');
        }

        if ($result && method_exists($this, $method = $this->getMethodToPrepareResponse($action))) {

            // Since we develop the marketplace by ourselves,
            // a full-fledged subsystem to parse responses is not needed.
            // We can modify marketplace API instead
            $this->$method($result);
        }

        return $result;
    }

    /**
     * Check request structure
     * 
     * @param array $data Response from marketplace
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse(array $data)
    {
        if (isset($data[self::RESPONSE_FIELD_ERROR])) {

            if (!isset($data[self::RESPONSE_FIELD_MESSAGE])) {
                $data[self::RESPONSE_FIELD_MESSAGE] = 'Unknown error';
            }

            $this->setError($data[self::RESPONSE_FIELD_ERROR], $data[self::RESPONSE_FIELD_MESSAGE]);
            $data = null;
        }

        return $data;
    }

    // }}}

    // {{{ Error handling

    /**
     * Check if an error occured
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkForError()
    {
        return isset($this->errorCode) || isset($this->errorMessage);
    }

    /**
     * Return error info
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getError()
    {
        return array($this->errorCode, $this->errorMessage);
    }

    /**
     * Unset the error-related variables
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearError()
    {
        $this->errorCode = $this->errorMessage = null;
    }

    /**
     * Common setter
     *
     * @param integer $code    Error code
     * @param string  $message Error description
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setError($code, $message)
    {
        $this->errorCode    = intval($code);
        $this->errorMessage = $message;
    }

    // }}}

    // {{{ Auxiliary methods

    /**
     * Return name of the method to get request data for current action
     *
     * @param string $action Action name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMethodToGetRequestData($action)
    {
        return 'getDataFor' . \Includes\Utils\Converter::convertToPascalCase($action) . 'Request';
    }

    /**
     * Return name of the method to prepare response for current action
     * 
     * @param string $action Action name
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMethodToPrepareResponse($action)
    {
        return 'prepareResponseFor' . \Includes\Utils\Converter::convertToPascalCase($action) . 'Action';
    }

    /**
     * The "get_core_hash" request handler
     *
     * @param string $versionMajor Major version of core to get
     * @param string $versionMinor Minor version of core to get
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getVersionField($versionMajor, $versionMinor)
    {
        return array(
            self::REQUEST_FIELD_VERSION_MAJOR => $versionMajor,
            self::REQUEST_FIELD_VERSION_MINOR => $versionMinor,
        );
    }

    // }}}
}
