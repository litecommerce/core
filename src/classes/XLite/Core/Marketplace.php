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
    const ACTION_CHECK_FOR_UPDATES = 'check_for_updates';
    const ACTION_GET_CORES         = 'get_cores';
    const ACTION_GET_CORE_PACK     = 'get_core_pack';
    const ACTION_GET_CORE_HASH     = 'get_core_hash';
    const ACTION_GET_ADDONS_LIST   = 'get_addons';
    const ACTION_GET_ADDON_PACK    = 'get_addon_pack';
    const ACTION_GET_ADDON_INFO    = 'get_addon_info';
    const ACTION_GET_ADDON_HASH    = 'get_addon_hash';
    const ACTION_CHECK_ADDON_KEY   = 'check_addon_key';

    /**
     * Request/response fields
     */
    const FIELD_VERSION_CORE_CURRENT  = 'currentCoreVersion';
    const FIELD_VERSION               = 'version';
    const FIELD_VERSION_MAJOR         = 'major';
    const FIELD_VERSION_MINOR         = 'minor';
    const FIELD_REVISION_DATE         = 'revisionDate';
    const FIELD_LENGTH                = 'length';
    const FIELD_GZIPPED               = 'gzipped';
    const FIELD_NAME                  = 'name';
    const FIELD_MODULE                = 'module';
    const FIELD_AUTHOR                = 'author';
    const FIELD_KEY                   = 'key';
    const FIELD_IS_UPGRADE_AVAILABLE  = 'isUpgardeAvailable';
    const FIELD_ARE_UPDATES_AVAILABLE = 'areUpdatesAvailable';
    const FIELD_READABLE_NAME         = 'readableName';
    const FIELD_READABLE_AUTHOR       = 'readableAuthor';
    const FIELD_MODULE_ID             = 'moduleId';
    const FIELD_DESCRIPTION           = 'description';
    const FIELD_PRICE                 = 'price';
    const FIELD_CURRENCY              = 'currency';
    const FIELD_ICON_URL              = 'iconURL';
    const FIELD_PAGE_URL              = 'pageURL';
    const FIELD_AUTHOR_PAGE_URL       = 'authorPageURL';
    const FIELD_DEPENDENCIES          = 'dependencies';
    const FIELD_RATING                = 'rating';
    const FIELD_RATING_RATE           = 'rate';
    const FIELD_RATING_VOTES_COUNT    = 'votesCount';
    const FIELD_DOWNLOADS_COUNT       = 'downloadCount';

    /**
     * Some predefined TTLs
     */
    const TTL_LONG  = 86400;
    const TTL_SHORT = 3600;

    /**
     * Some regexps
     */
    const REGEXP_VERSION  = '/\d+\.?[\w-\.]*/';
    const REGEXP_WORD     = '/\w+/';
    const REGEXP_HASH     = '/\w{32}/';
    const REGEXP_CURRENCY = '/[A-Z]{1,3}/';
    const REGEXP_CLASS    = '/[\w\\\\]+/';

    /**
     * Dedicated return code for the "performActionWithTTL" method
     */
    const TTL_NOT_EXPIRED = '____TTL_NOT_EXPIRED____';


    /**
     * Error info (code, message, arguments)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $error;


    // {{{ "Check for updates" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkForUpdates($ttl = self::TTL_LONG)
    {
        return $this->performActionWithTTL($ttl, self::ACTION_CHECK_FOR_UPDATES);
    }

    /**
     * Parse response for certian action
     * 
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForCheckForUpdatesAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForCheckForUpdatesAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getSchemaResponseForCheckForUpdatesAction());
    }

    /**
     * Return response schema for certian action
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSchemaResponseForCheckForUpdatesAction()
    {
        return array(
            self::FIELD_IS_UPGRADE_AVAILABLE  => FILTER_VALIDATE_BOOLEAN,
            self::FIELD_ARE_UPDATES_AVAILABLE => FILTER_VALIDATE_BOOLEAN,
        );
    }

    // }}}

    // {{{ "Get cores" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCores($ttl = self::TTL_LONG)
    {
        return $this->performActionWithTTL($ttl, self::ACTION_GET_CORES);
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetCoresAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForGetCoresAction(array $data)
    {
        $result = true;

        foreach ($data as $core) {
            $result = $result && $this->validateAgainstSchema($core, $this->getSchemaResponseForGetCoresAction());
        }

        return $result;
    }

    /**
     * Return response schema for certian action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSchemaResponseForGetCoresAction()
    {
        return array(
            self::FIELD_VERSION => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => self::REGEXP_VERSION),
            ),
            self::FIELD_REVISION_DATE => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
            self::FIELD_LENGTH => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
        );
    }

    /**
     * Prepare response schema for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareResponseForGetCoresAction(array $data)
    {
        $result = array();

        foreach ($data as $core) {
            $coreVersion = $core[self::FIELD_VERSION];
            $coreVersionMajor = $coreVersion[self::FIELD_VERSION_MAJOR];
            $coreVersionMinor = $coreVersion[self::FIELD_VERSION_MINOR];

            if (isset($result[$coreVersionMajor])) {
                $currentVersion = $result[$coreVersionMajor][self::FIELD_VERSION];
                $currentVersionMinor = $currentVersion[self::FIELD_VERSION_MINOR];
            }

            if (!isset($currentVersionMinor) || version_compare($currentVersionMinor, $coreVersionMinor, '<')) {
                $result[$coreVersionMajor] = array(
                    $coreVersionMinor,
                    $core[self::FIELD_REVISION_DATE],
                    $core[self::FIELD_LENGTH]
                );
            }
        }

        return $result;
    }

    // }}}

    // {{{ "Get core pack" request

    /**
     * The certain request handler
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
                self::FIELD_VERSION => $this->getVersionField($versionMajor, $versionMinor),
                self::FIELD_GZIPPED => $this->canCompress(),
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetCorePackAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->writeDataToFile($response);
    }

    // }}}

    // {{{ "Get core hash" request

    /**
     * The certain request handler
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
                self::FIELD_VERSION => $this->getVersionField($versionMajor, $versionMinor),
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetCoreHashAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForGetCoreHashAction(array $data)
    {
        return !empty($data);
    }

    // }}}

    // {{{ "Get addons list" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function saveAddonsList($ttl = self::TTL_LONG)
    {
        $result = $this->performActionWithTTL($ttl, self::ACTION_GET_ADDONS_LIST, array(), false);

        if (self::TTL_NOT_EXPIRED !== $result) {
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->updateMarketplaceModules((array) $result);
        }

        return (bool) $result;
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetAddonsAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForGetAddonsAction(array $data)
    {
        $result = true;

        foreach ($data as $module) {
            $result = $result && $this->validateAgainstSchema($module, $this->getResponseSchemaForGetAddonInfoAction());
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

            // Module key fields
            $author = $this->getField($module, self::FIELD_AUTHOR);
            $name   = $this->getField($module, self::FIELD_NAME);

            // Arrays passed in response
            $version = $this->getField($module, self::FIELD_VERSION) ?: array();
            $rating  = $this->getField($module, self::FIELD_RATING)  ?: array();

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
                    'marketplaceID' => $this->getField($module, self::FIELD_MODULE_ID),
                    'rating'        => $this->getField($rating, self::FIELD_RATING_RATE),
                    'votes'         => $this->getField($rating, self::FIELD_RATING_VOTES_COUNT),
                    'downloads'     => $this->getField($module, self::FIELD_DOWNLOADS_COUNT),
                    'price'         => $this->getField($module, self::FIELD_PRICE),
                    'currency'      => $this->getField($module, self::FIELD_CURRENCY),
                    'majorVersion'  => $majorVersion,
                    'minorVersion'  => $minorVersion,
                    'revisionDate'  => $this->getField($module, self::FIELD_REVISION_DATE),
                    'moduleName'    => $this->getField($module, self::FIELD_READABLE_NAME),
                    'authorName'    => $this->getField($module, self::FIELD_READABLE_AUTHOR),
                    'description'   => $this->getField($module, self::FIELD_DESCRIPTION),
                    'iconURL'       => $this->getField($module, self::FIELD_ICON_URL),
                    'pageURL'       => $this->getField($module, self::FIELD_PAGE_URL),
                    'authorPageURL' => $this->getField($module, self::FIELD_AUTHOR_PAGE_URL),
                    'dependencies'  => (array) $this->getField($module, self::FIELD_DEPENDENCIES),
                    'packSize'      => $this->getField($module, self::FIELD_LENGTH),
                );

            } else {

                // :TODO: add logging here
            }
        }

        return $result;
    }

    // }}}

    // {{{ "Get addon pack" request

    /**
     * The certain request handler
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
                self::FIELD_MODULE_ID => $moduleID,
                self::FIELD_KEY       => $key,
                self::FIELD_GZIPPED   => $this->canCompress(),

            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetAddonPackAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->writeDataToFile($response);
    }

    // }}}

    // "Get addon info" request

    /**
     * The certain request handler
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
                self::FIELD_MODULE_ID => $moduleID,
                self::FIELD_KEY       => $key,
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetAddonInfoAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForGetAddonInfoAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getResponseSchemaForGetAddonInfoAction());
    }

    /**
     * Return validation schema for certain action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResponseSchemaForGetAddonInfoAction()
    {
        return array(
            self::FIELD_VERSION => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => self::REGEXP_VERSION),
            ),
            self::FIELD_REVISION_DATE => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
            self::FIELD_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_WORD),
            ),
            self::FIELD_NAME => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_WORD),
            ),
            self::FIELD_READABLE_AUTHOR => FILTER_SANITIZE_STRING,
            self::FIELD_READABLE_NAME   => FILTER_SANITIZE_STRING,
            self::FIELD_MODULE_ID => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_HASH),
            ),
            self::FIELD_DESCRIPTION => FILTER_SANITIZE_STRING,
            self::FIELD_PRICE => FILTER_VALIDATE_FLOAT,
            self::FIELD_CURRENCY => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_CURRENCY),
            ),
            self::FIELD_ICON_URL => FILTER_SANITIZE_URL,
            self::FIELD_PAGE_URL => FILTER_SANITIZE_URL,
            self::FIELD_AUTHOR_PAGE_URL => FILTER_SANITIZE_URL,
            self::FIELD_RATING => array(
                'filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                'flags'   => FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_FRACTION,
            ),
            self::FIELD_DEPENDENCIES => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => self::REGEXP_CLASS),
            ),
            self::FIELD_DOWNLOADS_COUNT => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
            self::FIELD_LENGTH => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
        );
    }

    // }}}

    // {{{ "Get addon hash" action

    /**
     * The certain request handler
     *
     * @param string $moduleID External module identifier
     * @param string $key      Module license key OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAddonHash($moduleID, $key = null)
    {
        return $this->sendRequestToMarkeplace(
            self::ACTION_GET_ADDON_HASH,
            array(
                self::FIELD_MODULE_ID => $moduleID,
                self::FIELD_KEY       => $key,
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForGetAddonHashAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForGetAddonHashAction(array $data)
    {
        return !empty($data);
    }

    // }}}

    // {{{ "Check addon key" request

    /**
     * The certain request handler
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
                self::FIELD_KEY => $key,
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseResponseForCheckAddonKeyAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * @param array $data Response data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateResponseForCheckAddonKeyAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getResponseSchemaForCheckAddonKeyAction());
    }

    /**
     * Return response schema for certian action
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSchemaResponseForCheckAddonKeyAction()
    {
        return array(
            self::FIELD_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_WORD),
            ),
            self::FIELD_NAME => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => self::REGEXP_WORD),
            ),
        );
    }

    // }}}

    // {{{ Common methods to send request to marketplace

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
        $result = null;

        // Prepare for request
        $this->clearError();

        // Run bouncer
        $response = $this->getRequest($action, $data)->sendRequest();

        if ($response) {
            $result = $this->prepareResponse($response, $action);
        } else {
            $this->setError(null, 'Bouncer general error, see the log');
        }

        // For developer mode only
        if (LC_DEVELOPER_MODE) {
            $this->showDeveloperTopMessage($action);
        }

        return $result;
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
        $request->body = $data + $this->getRequestCommonData();

        return $request;
    }

    /**
     * Common data for all request types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestCommonData()
    {
        return array(
            self::FIELD_VERSION_CORE_CURRENT => $this->getVersionField(
                \XLite::getInstance()->getMajorVersion(),
                \XLite::getInstance()->getMinorVersion()
            ),
        );
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
        $method = 'ResponseFor' . \Includes\Utils\Converter::convertToPascalCase($action) . 'Action';

        if (200 == $response->code) {

            if (isset($response->body)) {
                $result = $this->{'parse' . $method}($response);

            } else {
                $this->setError(null, 'An empty response recieved');
            }

        } else {
            $this->setError(null, 'Unable to access marketplace by the URL "' . $response->uri . '"');
        }

        if (is_array($result)) {
            if ($this->{'validate' . $method}($result)) {

                if (method_exists($this, 'prepare' . $method)) {
                    $result = $this->{'prepare' . $method}($result);
                }

            } else {
                $result = null;
            }
        }

        return $result;
    }

    /**
     * Show diagnostic message
     *
     * @param string $action Current action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showDeveloperTopMessage($action)
    {
        list($code, $message, $args) = $this->getError();
        $common = '["' . \XLite\Core\Request::getInstance()->target . '"' . ($code ? ', code ' . $code : '') . ']: ';

        if (isset($message)) {
            \XLite\Core\TopMessage::getInstance()->addError(
                $common . 'Marketplace connection error (' . $action . ', "' . $message . '")',
                $args
            );
        } else {
            \XLite\Core\TopMessage::getInstance()->addInfo(
                $common . 'Successfully connected to marketplace (' . $action . ')',
                $args
            );
        }
    }

    // }}}

    // {{{ Error handling

    /**
     * Return error info
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getError()
    {
        return $this->error;
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
        list(, $message, $args) = $this->getError();

        if (!empty($message)) {
            \XLite\Core\TopMessage::getInstance()->addError($message, $args);
        }
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
        $this->setError(null, null, array());
    }

    /**
     * Common setter
     *
     * @param integer $code    Error code
     * @param string  $message Error description
     * @param array   $args    List of message arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setError($code, $message, array $args = array())
    {
        $this->error = array($code, $message, $args);
    }

    // }}}

    // {{{ Cache-related routines

    /**
     * Perform some action if a TTL is expired
     * 
     * @param integer $ttl           Time to live
     * @param string  $action        Marketplace action
     * @param array   $data          Data to send to marketplace OPTIONAL
     * @param boolean $saveInTmpVars Flag OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionWithTTL($ttl, $action, array $data = array(), $saveInTmpVars = true)
    {
        $result = self::TTL_NOT_EXPIRED;

        $cellTTL  = $action . 'TTL';
        $cellData = $action . 'Data';

        // Check if expired
        if (!$this->checkTTL($cellTTL, $ttl)) {

            // Call method
            $result = $this->sendRequestToMarkeplace($action, $data);

            if ($saveInTmpVars) {
                // Save in DB (if needed)
                \XLite\Core\TmpVars::getInstance()->$cellData = $result;
            }

            // Set new expiration time
            $this->setTTLStart($cellTTL);
        }

        return $saveInTmpVars ? \XLite\Core\TmpVars::getInstance()->$cellData : $result;
    }

    /**
     * Check and update cache TTL
     *
     * @param string  $cell Name of the cache cell
     * @param integer $ttl  TTL value (in seconds)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkTTL($cell, $ttl)
    {
        // Fetch a certain cell value
        $start = \XLite\Core\TmpVars::getInstance()->$cell;

        return isset($start) && time() < ($start + $ttl);
    }

    /**
     * Renew TTL cell value
     *
     * @param string $cell Name of the cache cell
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setTTLStart($cell)
    {
        \XLite\Core\TmpVars::getInstance()->$cell = time();
    }

    // }}}

    // {{{ Parsers and validators

    /**
     * Compose versions into one field
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
     * Parse JSON string
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to get data
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function parseJSON(\PEAR2\HTTP\Request\Response $response)
    {
        return json_decode($response->body, true);
    }

    /**
     * Write data from request into a file
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to get data
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function writeDataToFile(\PEAR2\HTTP\Request\Response $response)
    {
        $path = \Includes\Utils\FileManager::getUniquePath(
            LC_DIR_TMP, 
            uniqid() . '.' . \Includes\Utils\PHARManager::getExtension() ?: 'tar'
        );

        return \Includes\Utils\FileManager::write($path, $response->body) ? $path : null;
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

    // {{{ Misc methods

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
     * To determine what type of archives to download
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function canCompress()
    {
        return \Includes\Utils\PHARManager::canCompress();
    }

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        // Set variable format
        $this->clearError();
    }

    // }}}
}
