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
 * @since     1.0.0
 */

namespace XLite\Core;

/**
 * Marketplace 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Marketplace extends \XLite\Base\Singleton
{
    /**
     * Marketplace request types
     */
    const ACTION_GET_CORE_VERSIONS = 'get_cores';
    const ACTION_GET_CORE_PACK     = 'get_core_pack';
    const ACTION_GET_CORE_HASH     = 'get_core_hash';
    const ACTION_GET_ADDONS_LIST   = 'get_addons';
    const ACTION_GET_ADDON_PACK    = 'get_addon_pack';
    const ACTION_GET_ADDON_INFO    = 'get_addon_info';
    const ACTION_CHECK_ADDON_KEY   = 'check_addon_key';
    const ACTION_CHECK_FOR_UPDATES = 'check_for_updates';

    /**
     * Protocol data fields - common
     */
    const FIELD_VERSION_MAJOR = 'major';
    const FIELD_VERSION_MINOR = 'minor';

    /**
     * Protocol data fields - request
     */
    const REQUEST_FIELD_VERSION_CORE_CURRENT = 'currentCoreVersion';
    const REQUEST_FIELD_VERSION_CORE         = 'version';
    const REQUEST_FIELD_VERSION_MODULE       = 'version';
    const REQUEST_FIELD_IS_PACK_GZIPPED      = 'gzipped';
    const REQUEST_FIELD_MODULE_ID            = 'moduleId';
    const REQUEST_FIELD_MODULE_KEY           = 'key';

    /**
     * Protocol data fields - response (common)
     */
    const RESPONSE_FIELD_ERROR   = 'error';
    const RESPONSE_FIELD_MESSAGE = 'message';

    /**
     * Protocol data fields - response
     */
    const RESPONSE_FIELD_CORE_VERSION       = 'version';
    const RESPONSE_FIELD_CORE_REVISION_DATE = 'revisionDate';

    /**
     * Protocol data fields - response
     */
    const RESPONSE_FIELD_MODULE_NAME               = 'name';
    const RESPONSE_FIELD_MODULE_AUTHOR             = 'author';
    const RESPONSE_FIELD_MODULE_VERSION            = 'version';
    const RESPONSE_FIELD_MODULE_ID                 = 'moduleId';
    const RESPONSE_FIELD_MODULE_READABLE_NAME      = 'readableName';
    const RESPONSE_FIELD_MODULE_READABLE_AUTHOR    = 'readableAuthor';
    const RESPONSE_FIELD_MODULE_DESCRIPTION        = 'description';
    const RESPONSE_FIELD_MODULE_REVISION_DATE      = 'revisionDate';
    const RESPONSE_FIELD_MODULE_PRICE              = 'price';
    const RESPONSE_FIELD_MODULE_CURRENCY           = 'currency';
    const RESPONSE_FIELD_MODULE_ICON_URL           = 'iconURL';
    const RESPONSE_FIELD_MODULE_PAGE_URL           = 'pageURL';
    const RESPONSE_FIELD_MODULE_AUTHOR_PAGE_URL    = 'authorPageURL';
    const RESPONSE_FIELD_MODULE_DEPENDENCIES       = 'dependencies';
    const RESPONSE_FIELD_MODULE_RATING             = 'rating';
    const RESPONSE_FIELD_MODULE_RATING_RATE        = 'rate';
    const RESPONSE_FIELD_MODULE_RATING_VOTES_COUNT = 'votesCount';
    const RESPONSE_FIELD_MODULE_DOWNLOADS_COUNT    = 'downloadCount';
    const RESPONSE_FIELD_MODULE_LICENSE            = 'license';

    /**
     * Protocol data fields - response
     */
    const RESPONSE_FIELD_MODULE_PACK_DATA   = 'data';
    const RESPONSE_FIELD_MODULE_PACK_LENGTH = 'length';

    /**
     * Some regexps 
     */
    const REGEXP_VERSION = '/\d+\.?[\w-\.]*/';


    /**
     * Error code
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorCode;

    /**
     * Error message
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorMessage;


    // {{{ Interface: public methods (wrappers)

    /**
     * Return markeplace URL
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getCorePack($versionMajor, $versionMinor)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_CORE_PACK,
            array(
                self::REQUEST_FIELD_VERSION_CORE    => $this->getVersionField($versionMajor, $versionMinor),
                self::REQUEST_FIELD_IS_PACK_GZIPPED => \Includes\Utils\PHARManager::canCompress(),
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getAddonPack($moduleID, $key = null)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDON_PACK,
            array(
                self::REQUEST_FIELD_MODULE_ID       => $moduleID,
                self::REQUEST_FIELD_MODULE_KEY      => $key,
                self::REQUEST_FIELD_IS_PACK_GZIPPED => \Includes\Utils\PHARManager::canCompress(),

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
     * @since  1.0.0
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

    /**
     * The "check_addon_key" request handler
     *
     * @param string $key Module license to check
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAddonKey($key)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_CHECK_ADDON_KEY,
            array(
                self::REQUEST_FIELD_MODULE_KEY => $key,
            )
        );
    }

    /**
     * The "check_for_updates" request handler
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkForUpdates()
    {
        /*if (!$this->checkTTL(__FUNCTION__, self::TTL_UPGRADE_FLAGS)) {
            \XLite\Core\TmpVars::getInstance()->{self::CACHED_DATA_UPGRADE_FLAGS}
                = $this->sendRequestToMarkeplace(self::ACTION_CHECK_FOR_UPDATES);
            $this->setTTLStart(__FUNCTION__);
        }

        return \XLite\Core\TmpVars::getInstance()->{self::CACHED_DATA_UPGRADE_FLAGS};*/

        return true;
    }

    // }}}

    // {{{ Protocol (handlers)

    /**
     * Common data for all request types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param array  $data   Custom data to send in request OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sendRequestToMarkeplace($action, array $data = array())
    {
        // Prepare for request
        $this->clearError();

        return $this->prepareResponse($this->getRequest($action, $data)->sendRequest(), $action);
    }

    /**
     * Return prepared request object
     *
     * @param string $action Action name
     * @param array  $data   Request data OPTIONAL
     *
     * @return \XLite\Model\HTTPS
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @param string                       $action   Current action
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponse(\PEAR2\HTTP\Request\Response $response, $action)
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
            $this->setError(-1, 'Unable to access marketplace by the URL "' . $response->uri . '"');
        }

        if ($result && method_exists($this, $method = $this->getMethodToPrepareResponse($action))) {

            // Since we develop the marketplace by ourselves,
            // a full-fledged subsystem to parse responses is not needed.
            // We can modify marketplace API instead
            $result = $this->$method($result);
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getError()
    {
        return array($this->errorCode, $this->errorMessage);
    }

    /**
     * Set top message with error info
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setErrorTopMessage()
    {
        list($code, $message) = $this->getError();
        \XLite\Core\TopMessage::getInstance()->addError($message, array(), $code);
    }

    /**
     * Unset the error-related variables
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
     */
    protected function setError($code, $message)
    {
        $this->errorCode    = intval($code);
        $this->errorMessage = $message;
    }

    // }}}

    // {{{ Response schemas

    /**
     * Return validation schema for certain action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResponseSchemaForGetCoresAction()
    {
        return array(
            self::RESPONSE_FIELD_CORE_VERSION       => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => self::REGEXP_VERSION),
            ),
            self::RESPONSE_FIELD_CORE_REVISION_DATE => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
        );
    }

    /**
     * Return validation schema for certain action
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResponseSchemaForGetAddonsAction()
    {
        return array(
            self::RESPONSE_FIELD_MODULE_VERSION         => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => self::REGEXP_VERSION),
            ),
            self::RESPONSE_FIELD_MODULE_REVISION_DATE   => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
            self::RESPONSE_FIELD_MODULE_AUTHOR          => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/\w+/'),
            ),
            self::RESPONSE_FIELD_MODULE_NAME            => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/\w+/'),
            ),
            self::RESPONSE_FIELD_MODULE_READABLE_AUTHOR => FILTER_SANITIZE_STRING,
            self::RESPONSE_FIELD_MODULE_READABLE_NAME   => FILTER_SANITIZE_STRING,
            self::RESPONSE_FIELD_MODULE_ID              => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/\w{32}/'),
            ),
            self::RESPONSE_FIELD_MODULE_DESCRIPTION     => FILTER_SANITIZE_STRING,
            self::RESPONSE_FIELD_MODULE_PRICE           => FILTER_VALIDATE_FLOAT,
            self::RESPONSE_FIELD_MODULE_CURRENCY        => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/[A-Z]{1,3}/'),
            ),
            self::RESPONSE_FIELD_MODULE_ICON_URL        => FILTER_SANITIZE_URL,
            self::RESPONSE_FIELD_MODULE_PAGE_URL        => FILTER_SANITIZE_URL,
            self::RESPONSE_FIELD_MODULE_AUTHOR_PAGE_URL => FILTER_SANITIZE_URL,
            self::RESPONSE_FIELD_MODULE_RATING          => array(
                'filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                'flags'   => FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_FRACTION,
            ),
            self::RESPONSE_FIELD_MODULE_DEPENDENCIES    => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => '/[\w\\\\]+/'),
            ),
            self::RESPONSE_FIELD_MODULE_DOWNLOADS_COUNT => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
        );
    }

    /**
     * Return validation schema for certain action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResponseSchemaForGetAddonPackAction()
    {
        return array(
            self::RESPONSE_FIELD_MODULE_PACK_DATA   => FILTER_UNSAFE_RAW,
            self::RESPONSE_FIELD_MODULE_PACK_LENGTH => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
        );
    }

    /**
     * Return validation schema for certain action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResponseSchemaForCheckAddonKeyAction()
    {
        return array(
            self::RESPONSE_FIELD_MODULE_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/\w+/'),
            ),
            self::RESPONSE_FIELD_MODULE_NAME   => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/\w+/'),
            ),
        );
    }

    // }}}

    // {{{ Certain requests

    /**
     * Prepare data for certain response
     *
     * @param array $data Data recieved from marketplace
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponseForGetCoresAction(array $data)
    {
        $result = array();

        foreach ($data as $core) {

            // Validate data recieved in responese
            if ($this->validateAgainstSchema($core, $this->getResponseSchemaForGetAddonsAction())) {
                $result[] = $core;
            }
        }

        return $result;
    }

    /**
     * Prepare data for certain response
     * 
     * @param array $data Data recieved from marketplace
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponseForGetAddonsAction(array $data)
    {
        $result = array();

        foreach ($data as $module) {

            // Validate data recieved in responese
            if ($this->validateAgainstSchema($module, $this->getResponseSchemaForGetAddonsAction())) {

                // Module key fields
                $author = $this->getField($module, self::RESPONSE_FIELD_MODULE_AUTHOR);
                $name   = $this->getField($module, self::RESPONSE_FIELD_MODULE_NAME);

                // Arrays passed in response
                $version = $this->getField($module, self::RESPONSE_FIELD_MODULE_VERSION) ?: array();
                $rating  = $this->getField($module, self::RESPONSE_FIELD_MODULE_RATING)  ?: array();

                // Module versions
                $majorVersion = $this->getField($version, self::FIELD_VERSION_MAJOR);
                $minorVersion = $this->getField($version, self::FIELD_VERSION_MINOR);

                // Short names
                $key    = $author . '_' . $name . '_' . $majorVersion;
                $search = compact('name', 'author', 'majorVersion', 'minorVersion') + array('installed' => true);

                // To make modules list unique
                if (
                    (!isset($result[$key]) || version_compare($result[$key]['minorVersion'], $minorVersion, '<'))
                    && !\XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy($search)
                ) {

                    // It's the structure of \XLite\Model\Module class data
                    $result[$key] = array(
                        'name'          => $name,
                        'author'        => $author,
                        'marketplaceID' => $this->getField($module, self::RESPONSE_FIELD_MODULE_ID),
                        'rating'        => $this->getField($rating, self::RESPONSE_FIELD_MODULE_RATING_RATE),
                        'votes'         => $this->getField($rating, self::RESPONSE_FIELD_MODULE_RATING_VOTES_COUNT),
                        'downloads'     => $this->getField($module, self::RESPONSE_FIELD_MODULE_DOWNLOADS_COUNT),
                        'price'         => $this->getField($module, self::RESPONSE_FIELD_MODULE_PRICE),
                        'currency'      => $this->getField($module, self::RESPONSE_FIELD_MODULE_CURRENCY),
                        'majorVersion'  => $majorVersion,
                        'minorVersion'  => $minorVersion,
                        'revisionDate'  => $this->getField($module, self::RESPONSE_FIELD_MODULE_REVISION_DATE),
                        'moduleName'    => $this->getField($module, self::RESPONSE_FIELD_MODULE_READABLE_NAME),
                        'authorName'    => $this->getField($module, self::RESPONSE_FIELD_MODULE_READABLE_AUTHOR),
                        'description'   => $this->getField($module, self::RESPONSE_FIELD_MODULE_DESCRIPTION),
                        'iconURL'       => $this->getField($module, self::RESPONSE_FIELD_MODULE_ICON_URL),
                        'pageURL'       => $this->getField($module, self::RESPONSE_FIELD_MODULE_PAGE_URL),
                        'authorPageURL' => $this->getField($module, self::RESPONSE_FIELD_MODULE_AUTHOR_PAGE_URL),
                        'dependencies'  => (array) $this->getField($module, self::RESPONSE_FIELD_MODULE_DEPENDENCIES),
                    );

                } else {

                    // :TODO: add logging here
                }
            }
        }

        return $result;
    }

    /**
     * Prepare data for certain response
     *
     * @param array $data Data recieved from marketplace
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponseForGetAddonPackAction(array $data)
    {
        $result = null;

        // Validate data recieved in responese
        if ($this->validateAgainstSchema($data, $this->getResponseSchemaForGetAddonPackAction())) {
            $result = base64_decode($data['data']);
        }

        return $result;
    }

    /**
     * Prepare data for certain response
     *
     * @param array $data Data recieved from marketplace
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponseForCheckAddonKeyAction(array $data)
    {
        $result = null;

        // Validate data recieved in responese
        if ($this->validateAgainstSchema($data, $this->getResponseSchemaForCheckAddonKeyAction())) {
            $result = $data;
        }

        return $result;
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    protected function getVersionField($versionMajor, $versionMinor)
    {
        return array(
            self::FIELD_VERSION_MAJOR => $versionMajor,
            self::FIELD_VERSION_MINOR => $versionMinor,
        );
    }

    /**
     * Alias
     * 
     * @param array  $data  Data to get field value from
     * @param string $field Name of field to get
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getField(array $data, $field)
    {
        return \Includes\Utils\ArrayManager::getIndex($data, $field, true);
    }

    /**
     * Common method to validate response
     *
     * :FIXME: must ignore unknown fields in data from marketplace
     * 
     * @param array $data   Data to validate
     * @param array $schema Validation schema
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateAgainstSchema(array $data, array $schema)
    {
        // :NOTE: do not change operator to the "===":
        // "Filter" extension changes type for some variables
        return array_intersect_key($data, $filtered = filter_var_array($data, $schema)) == $filtered;
    }

    // }}}

    // {{{ Cache-related routines

    /**
     * Check and update cache TTL
     * 
     * @param string  $type Name (type) of the current element
     * @param integer $ttl  TTL value (in seconds)
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkTTL($type, $ttl)
    {
        $start = \XLite\Core\TmpVars::getInstance()->{$this->getTTLName($type)};

        return isset($start) && time() < ($start + $ttl);
    }

    /**
     * Return name of a TTL cell
     *
     * @param string $type Name (type) of the current element
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTTLName($type)
    {
        return 'marketplace' . $type . 'TTLStart';
    }

    // }}}
}
