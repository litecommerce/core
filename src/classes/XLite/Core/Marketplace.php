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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * Marketplace
 *
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
    const ACTION_GET_HOSTING_SCORE = 'get_hosting_score';

    /**
     * Request/response fields
     */
    const FIELD_VERSION_CORE_CURRENT  = 'currentCoreVersion';
    const FIELD_VERSION               = 'version';
    const FIELD_VERSION_MAJOR         = 'major';
    const FIELD_VERSION_MINOR         = 'minor';
    const FIELD_REVISION              = 'revision';
    const FIELD_REVISION_DATE         = 'revisionDate';
    const FIELD_LENGTH                = 'length';
    const FIELD_GZIPPED               = 'gzipped';
    const FIELD_NAME                  = 'name';
    const FIELD_KEY_TYPE              = 'keyType';
    const FIELD_MODULE                = 'module';
    const FIELD_MODULES               = 'modules';
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
    const FIELD_LICENSE               = 'license';
    const FIELD_SHOP_ID               = 'shopID';
    const FIELD_SHOP_DOMAIN           = 'shopDomain';
    const FIELD_ERROR_CODE            = 'error';
    const FIELD_ERROR_MESSAGE         = 'message';
    const FIELD_IS_SYSTEM             = 'isSystem';

    /**
     * Some predefined TTLs
     */
    const TTL_LONG  = 86400;
    const TTL_SHORT = 3600;

    /**
     * Some regexps
     */
    const REGEXP_VERSION  = '/\d+\.?[\w-\.]*/';
    const REGEXP_WORD     = '/[\w\"\']+/';
    const REGEXP_NUMBER   = '/\d+/';
    const REGEXP_HASH     = '/\w{32}/';
    const REGEXP_CURRENCY = '/[A-Z]{1,3}/';
    const REGEXP_CLASS    = '/[\w\\\\]+/';

    /**
     * Error codes
     */
    const ERROR_CODE_REFUND = 1030;

    /**
     * Dedicated return code for the "performActionWithTTL" method
     */
    const TTL_NOT_EXPIRED = '____TTL_NOT_EXPIRED____';

    /**
     * Error message
     *
     * @var mixed
     */
    protected $error = null;

    /**
     * Get last error message from bouncer
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    // {{{ "Check for updates" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return array
     */
    public function checkForUpdates($ttl = self::TTL_LONG)
    {
        return $this->performActionWithTTL($ttl, static::ACTION_CHECK_FOR_UPDATES, $this->getCheckForUpdatesData());
    }

    /**
     * Return specific data array for "Check for updates" request
     *
     * @return array
     */
    protected function getCheckForUpdatesData()
    {
        $data = array();

        if ($this->isSendShopDomain()) {
            $data[static::FIELD_SHOP_DOMAIN]
                = \Includes\Utils\ConfigParser::getOptions(array('host_details', 'http_host'));
        }

        $modules = \XLite\Core\Database::getRepo('XLite\Model\Module')->search($this->getCheckForUpdatesDataCnd());

        if ($modules) {
            $data[static::FIELD_MODULES] = array();
            foreach ($modules as $module) {
                $data[static::FIELD_MODULES][] = array(
                    static::FIELD_NAME   => $module->getName(),
                    static::FIELD_AUTHOR => $module->getAuthor(),
                    static::FIELD_VERSION_MAJOR  => $module->getMajorVersion(),
                    static::FIELD_VERSION_MINOR  => $module->getMinorVersion(),
                );
            }
        }

        return $data;
    }

    /**
     * Return conditions for search modules for "Check for updates" request
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getCheckForUpdatesDataCnd()
    {
        $cnd = new \XLite\Core\CommonCell();

        $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        return $cnd;
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
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
     */
    protected function validateResponseForCheckForUpdatesAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getSchemaResponseForCheckForUpdatesAction());
    }

    /**
     * Return response schema for certian action
     *
     * @return array
     */
    protected function getSchemaResponseForCheckForUpdatesAction()
    {
        return array(
            static::FIELD_IS_UPGRADE_AVAILABLE  => FILTER_VALIDATE_BOOLEAN,
            static::FIELD_ARE_UPDATES_AVAILABLE => FILTER_VALIDATE_BOOLEAN,
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
     */
    public function getCores($ttl = self::TTL_LONG)
    {
        $result = $this->performActionWithTTL($ttl, static::ACTION_GET_CORES);

        if (static::TTL_NOT_EXPIRED !== $result) {
            $this->clearUpgradeCell();
        }

        return $result;
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
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
     */
    protected function getSchemaResponseForGetCoresAction()
    {
        return array(
            static::FIELD_VERSION => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => static::REGEXP_VERSION),
            ),
            static::FIELD_REVISION_DATE => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
            static::FIELD_LENGTH => array(
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
     */
    protected function prepareResponseForGetCoresAction(array $data)
    {
        $result = array();

        foreach ($data as $core) {
            $coreVersion = $core[static::FIELD_VERSION];
            $coreVersionMajor = $coreVersion[static::FIELD_VERSION_MAJOR];
            $coreVersionMinor = $coreVersion[static::FIELD_VERSION_MINOR];

            if (isset($result[$coreVersionMajor])) {
                $currentVersion = $result[$coreVersionMajor][static::FIELD_VERSION];
                $currentVersionMinor = $currentVersion[static::FIELD_VERSION_MINOR];
            }

            if (!isset($currentVersionMinor) || version_compare($currentVersionMinor, $coreVersionMinor, '<')) {
                $result[$coreVersionMajor] = array(
                    $coreVersionMinor,
                    $core[static::FIELD_REVISION_DATE],
                    $core[static::FIELD_LENGTH]
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
     */
    public function getCorePack($versionMajor, $versionMinor)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_GET_CORE_PACK,
            array(
                static::FIELD_VERSION => $this->getVersionField($versionMajor, $versionMinor),
                static::FIELD_GZIPPED => $this->canCompress(),
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
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
     */
    public function getCoreHash($versionMajor, $versionMinor)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_GET_CORE_HASH,
            array(
                static::FIELD_VERSION => $this->getVersionField($versionMajor, $versionMinor),
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
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
     */
    protected function validateResponseForGetCoreHashAction(array $data)
    {
        return !empty($data) && empty($data['error']);
    }

    // }}}

    // {{{ "Get addons list" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return void
     */
    public function saveAddonsList($ttl = self::TTL_LONG)
    {
        $result = $this->performActionWithTTL($ttl, static::ACTION_GET_ADDONS_LIST, array(), false);

        if (static::TTL_NOT_EXPIRED !== $result) {
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->updateMarketplaceModules((array) $result);
            $this->clearUpgradeCell();
        }

        return (bool) $result;
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
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
     */
    protected function validateResponseForGetAddonsAction(array $data)
    {
        $result = true;

        foreach ($data as $module) {
            $result = $result
                && is_array($module)
                && $this->validateAgainstSchema($module, $this->getSchemaResponseForGetAddonInfoAction());
        }

        return $result;
    }

    /**
     * Prepare data for certain response
     *
     * @param array $data Data received from marketplace
     *
     * @return array
     */
    protected function prepareResponseForGetAddonsAction(array $data)
    {
        $result = array();

        foreach ($data as $module) {

            // Module key fields
            $author = $this->getField($module, static::FIELD_AUTHOR);
            $name   = $this->getField($module, static::FIELD_NAME);

            // Arrays passed in response
            $version = $this->getField($module, static::FIELD_VERSION) ?: array();
            $rating  = $this->getField($module, static::FIELD_RATING)  ?: array();

            // Module versions
            $majorVersion = $this->getField($version, static::FIELD_VERSION_MAJOR);
            $minorVersion = $this->getField($version, static::FIELD_VERSION_MINOR);

            // Short names
            $key = $author . '_' . $name . '_' . $majorVersion;

            // To make modules list unique
            if (!isset($result[$key]) || version_compare($result[$key]['minorVersion'], $minorVersion, '<')) {

                // It's the structure of \XLite\Model\Module class data
                $result[$key] = array(
                    'name'            => $name,
                    'author'          => $author,
                    'fromMarketplace' => true,
                    'rating'          => $this->getField($rating, static::FIELD_RATING_RATE),
                    'votes'           => $this->getField($rating, static::FIELD_RATING_VOTES_COUNT),
                    'downloads'       => $this->getField($module, static::FIELD_DOWNLOADS_COUNT),
                    'price'           => $this->getField($module, static::FIELD_PRICE),
                    'currency'        => $this->getField($module, static::FIELD_CURRENCY),
                    'majorVersion'    => $majorVersion,
                    'minorVersion'    => $minorVersion,
                    'revisionDate'    => $this->getField($module, static::FIELD_REVISION_DATE),
                    'moduleName'      => $this->getField($module, static::FIELD_READABLE_NAME),
                    'authorName'      => $this->getField($module, static::FIELD_READABLE_AUTHOR),
                    'description'     => $this->getField($module, static::FIELD_DESCRIPTION),
                    'iconURL'         => $this->getField($module, static::FIELD_ICON_URL),
                    'pageURL'         => $this->getField($module, static::FIELD_PAGE_URL),
                    'authorPageURL'   => $this->getField($module, static::FIELD_AUTHOR_PAGE_URL),
                    'dependencies'    => (array) $this->getField($module, static::FIELD_DEPENDENCIES),
                    'packSize'        => $this->getField($module, static::FIELD_LENGTH),
                    'isSystem'        => (bool) $this->getField($module, static::FIELD_IS_SYSTEM),
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
     */
    public function getAddonPack($moduleID, $key = null)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_GET_ADDON_PACK,
            array(
                static::FIELD_MODULE_ID => $moduleID,
                static::FIELD_KEY       => $key,
                static::FIELD_GZIPPED   => $this->canCompress(),

            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
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
     */
    public function getAddonInfo($moduleID, $key = null)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_GET_ADDON_INFO,
            array(
                static::FIELD_MODULE_ID => $moduleID,
                static::FIELD_KEY       => $key,
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
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
     */
    protected function validateResponseForGetAddonInfoAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getSchemaResponseForGetAddonInfoAction());
    }

    /**
     * Return validation schema for certain action
     *
     * @return array
     */
    protected function getSchemaResponseForGetAddonInfoAction()
    {
        return array(
            static::FIELD_VERSION => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => static::REGEXP_VERSION),
            ),
            static::FIELD_REVISION_DATE => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('max_range' => time()),
            ),
            static::FIELD_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_NAME => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_READABLE_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_READABLE_NAME   => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_MODULE_ID => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_HASH),
            ),
            static::FIELD_DESCRIPTION => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_PRICE => FILTER_VALIDATE_FLOAT,
            static::FIELD_CURRENCY => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_CURRENCY),
            ),
            static::FIELD_ICON_URL => FILTER_SANITIZE_URL,
            static::FIELD_PAGE_URL => FILTER_SANITIZE_URL,
            static::FIELD_AUTHOR_PAGE_URL => FILTER_SANITIZE_URL,
            static::FIELD_RATING => array(
                'filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                'flags'   => FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_FRACTION,
            ),
            static::FIELD_DEPENDENCIES => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'flags'   => FILTER_REQUIRE_ARRAY,
                'options' => array('regexp' => static::REGEXP_CLASS),
            ),
            static::FIELD_DOWNLOADS_COUNT => array(
                'filter'  => FILTER_VALIDATE_INT,
                'options' => array('min_range' => 0),
            ),
            static::FIELD_LENGTH => array(
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
     */
    public function getAddonHash($moduleID, $key = null)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_GET_ADDON_HASH,
            array(
                static::FIELD_MODULE_ID => $moduleID,
                static::FIELD_KEY       => $key,
            )
        );
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return string
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
     */
    protected function validateResponseForGetAddonHashAction(array $data)
    {
        return !empty($data) && empty($data['error']);
    }

    // }}}

    // {{{ "Check addon key" request

    /**
     * The certain request handler
     *
     * @param string $key Module license to check
     *
     * @return array
     */
    public function checkAddonKey($key)
    {
        return $this->sendRequestToMarketplace(
            static::ACTION_CHECK_ADDON_KEY,
            array(
                static::FIELD_KEY => $key,
            )
        );
    }

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return boolean
     */
    public function checkAddonsKeys($ttl = self::TTL_LONG)
    {
        $repoModuleKey = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey');

        $keys = array_unique(
            \Includes\Utils\ArrayManager::getObjectsArrayFieldValues(
                $repoModuleKey->findAll(),
                'getKeyValue',
                true
            )
        );

        $result = $this->performActionWithTTL(
            $ttl,
            static::ACTION_CHECK_ADDON_KEY,
            array(static::FIELD_KEY => $keys),
            false
        );

        if (static::TTL_NOT_EXPIRED !== $result) {
            $repoModule = \XLite\Core\Database::getRepo('\XLite\Model\Module');

            foreach ((array) $result as $key => $addonsInfo) {
                $repoModuleKey->deleteInBatch($repoModuleKey->findBy(array('keyValue' => $key)));

                foreach ($addonsInfo as $info) {
                    $module = $repoModule->findOneBy(
                        array(
                            'author' => $info['author'],
                            'name'   => $info['name'],
                        )
                    );

                    if ($module) {
                        $repoModuleKey->insert($info + array('keyValue' => $key));

                        // Clear cache for proper installation
                        \XLite\Core\Marketplace::getInstance()->clearActionCache(\XLite\Core\Marketplace::ACTION_GET_ADDONS_LIST);

                    } else {
                        // No module has been found
                    }
                }
            }
        }

        return (bool) $result;
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
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
     */
    protected function validateResponseForCheckAddonKeyAction(array $data)
    {
        $result = true;

        $schema = $this->getSchemaResponseForCheckAddonKeyAction();

        foreach ($data as $key => $addons) {
            foreach ($addons as $addon) {

                $result = $result
                    && is_array($addon)
                    && $this->validateAgainstSchema($addon, $schema);
            }
        }

        return $result;
    }

    /**
     * Return response schema for certian action
     *
     * @return array
     */
    protected function getSchemaResponseForCheckAddonKeyAction()
    {
        return array(
            static::FIELD_AUTHOR => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_NAME => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_KEY_TYPE => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_NUMBER),
            ),
        );
    }

    /**
     * Validate response for error message
     *
     * @param array $data Response data
     *
     * @return array
     */
    protected function validateResponseForErrorAction(array $data)
    {
        return $this->validateAgainstSchema($data, $this->getSchemaResponseForError());
    }

    /**
     * Return response schema for errors
     *
     * @return array
     */
    protected function getSchemaResponseForError()
    {
        return array(
            static::FIELD_ERROR_CODE => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
            static::FIELD_ERROR_MESSAGE => array(
                'filter'  => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => static::REGEXP_WORD),
            ),
        );
    }

    // }}}



    // {{{ "Get hosting score" request

    /**
     * The certain request handler
     *
     * @param integer $ttl Data TTL OPTIONAL
     *
     * @return array
     */
    public function getHostingScore($ttl = self::TTL_LONG)
    {
        return $this->performActionWithTTL($ttl, static::ACTION_GET_HOSTING_SCORE);
    }

    /**
     * Parse response for certian action
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     *
     * @return array
     */
    protected function parseResponseForGetHostingScoreAction(\PEAR2\HTTP\Request\Response $response)
    {
        return $this->parseJSON($response);
    }

    /**
     * Validate response for certian action
     *
     * FIXME: use a schema
     *
     * @param array $data Response data
     *
     * @return array
     */
    protected function validateResponseForGetHostingScoreAction(array $data)
    {
        $result = true;

        foreach ($data as $row) {
            if (
                !is_array($row)
                || !empty($row['name'])
                || !isset($row['score'])
                || !ctype_digit($row['score'])
                || (isset($row['link']) && !is_string($row['link']))
            ) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    // {{{ Common methods to send request to marketplace

    /**
     * Send request to marketplace endpoint and return the response
     *
     * @param string $action Name of the action
     * @param array  $data   Custom data to send in request OPTIONAL
     *
     * @return string
     */
    protected function sendRequestToMarketplace($action, array $data = array())
    {
        $result = null;

        // Run bouncer
        $response = $this->getRequest($action, $data)->sendRequest();

        if ($response) {

            $error = $this->checkForErrors($response, $data);

            if ($error) {

                $this->logError($action, $error);

            } else {

                $result = $this->prepareResponse($response, $action);
            }

        } else {

            $this->logError($action, 'Bouncer general error (response is not received)');
        }

        return $result;
    }

    /**
     * Return prepared request object
     *
     * @param string $action Action name
     * @param array  $data   Request data OPTIONAL
     *
     * @return \XLite\Core\HTTP\Request
     */
    protected function getRequest($action, array $data = array())
    {
        $url   = $this->getMarketplaceActionURL($action);
        $data += $this->getRequestCommonData();

        $request = new \XLite\Core\HTTP\Request($url);
        $request->body = $data;

        $this->logInfo($action, 'The "{{url}}" URL requested', array('url' => $url), $data);

        return $request;
    }

    /**
     * Common data for all request types
     *
     * @return array
     */
    protected function getRequestCommonData()
    {
        return array(
            static::FIELD_SHOP_ID => md5(\Includes\Utils\ConfigParser::getOptions(array('host_details', 'http_host'))),
            static::FIELD_VERSION_CORE_CURRENT => $this->getVersionField(
                \XLite::getInstance()->getMajorVersion(),
                \XLite::getInstance()->getMinorVersion()
            ),
        );
    }

    /**
     * Check for response errors
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     * @param array                        $data     Request data
     *
     * @return string  Error message
     * @return boolean False if there are no errors
     */
    protected function checkForErrors(\PEAR2\HTTP\Request\Response $response, array $data)
    {
        $result = false;
        $errorBlock = $this->parseJSON($response);

        if (
            is_array($errorBlock)
            && $this->validateResponseForErrorAction($errorBlock)
        ) {
            $this->doErrorAction($errorBlock, $data);

            $result = 'Error code ('
                . $errorBlock[static::FIELD_ERROR_CODE] . '): '
                . $errorBlock[static::FIELD_ERROR_MESSAGE];
        }

        return $result;
    }

    /**
     * Do some actions concerning errors
     *
     * @param array $error Error block
     * @param array $data  Request data
     *
     * @return void
     */
    protected function doErrorAction(array $error, array $data)
    {
        if (static::ERROR_CODE_REFUND === $error[static::FIELD_ERROR_CODE]) {

            // Refunded Module license key must be removed from shop
            $key = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')
                ->findOneBy(array('keyValue' => $data[static::FIELD_KEY]));

            if ($key) {

                \XLite\Core\Database::getEM()->remove($key);

                \XLite\Core\Database::getEM()->flush();
            }
        }
    }

    /**
     * Prepare the marketplace response
     *
     * @param \PEAR2\HTTP\Request\Response $response Response to prepare
     * @param string                       $action   Current action
     *
     * @return mixed
     */
    protected function prepareResponse(\PEAR2\HTTP\Request\Response $response, $action)
    {
        $result = null;
        $method = 'ResponseFor' . \Includes\Utils\Converter::convertToPascalCase($action) . 'Action';

        if (200 == $response->code) {

            if (isset($response->body)) {

                $result = $this->{'parse' . $method}($response);

            } else {

                $this->logError($action, 'An empty response received');
            }

        } else {

            $this->logError($action, 'Returned the "{{code}}" code', array('code' => $response->code));
        }

        if (is_array($result)) {

            if ($this->{'validate' . $method}($result)) {

                if (method_exists($this, 'prepare' . $method)) {

                    $result = $this->{'prepare' . $method}($result);
                }

                $this->logInfo($action, 'Valid response received', array(), $result);

            } else {

                $this->logError($action, 'Response has an invalid format', array(), $result);

                $result = null;
            }
        }

        return $result;
    }

    // }}}

    // {{{ Cache-related routines

    /**
     * Clearing the temporary cache for a given marketplace action
     *
     * @param string $action Marketplace action
     *
     * @return mixed
     */
    public function clearActionCache($action)
    {
        list($cellTTL, $cellData) = $this->getActionCacheVars($action);

        \XLite\Core\TmpVars::getInstance()->$cellData = null;
        \XLite\Core\TmpVars::getInstance()->$cellTTL  = null;
    }

    /**
     * Return action cache variables
     *
     * @param string $action Marketplace action
     *
     * @return array
     */
    protected function getActionCacheVars($action)
    {
        return array(
            $action . 'TTL',
            $action . 'Data'
        );
    }

    /**
     * Perform some action if a TTL is expired
     *
     * @param integer $ttl           Time to live
     * @param string  $action        Marketplace action
     * @param array   $data          Data to send to marketplace OPTIONAL
     * @param boolean $saveInTmpVars Flag OPTIONAL
     *
     * @return mixed
     */
    protected function performActionWithTTL($ttl, $action, array $data = array(), $saveInTmpVars = true)
    {
        $result = static::TTL_NOT_EXPIRED;

        list($cellTTL, $cellData) = $this->getActionCacheVars($action);

        // Check if expired
        if (!$this->checkTTL($cellTTL, $ttl)) {

            // Call method
            $result = $this->sendRequestToMarketplace($action, $data);

            if ($saveInTmpVars) {
                // Save in DB (if needed)
                \XLite\Core\TmpVars::getInstance()->$cellData = $result;
            }

            // Set new expiration time
            if (isset($result)) {
                $this->setTTLStart($cellTTL);
            }
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
     */
    protected function getVersionField($versionMajor, $versionMinor)
    {
        return array(
            static::FIELD_VERSION_MAJOR => $versionMajor,
            static::FIELD_VERSION_MINOR => $versionMinor,
        );
    }

    /**
     * Alias
     *
     * @param array  $data  Data to get field value from
     * @param string $field Name of field to get
     *
     * @return mixed
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
     */
    protected function writeDataToFile(\PEAR2\HTTP\Request\Response $response)
    {
        if (!\Includes\Utils\FileManager::isDir(LC_DIR_TMP)) {
            \Includes\Utils\FileManager::mkdir(LC_DIR_TMP);
        }

        if (!\Includes\Utils\FileManager::isDirWriteable(LC_DIR_TMP)) {
            \Includes\ErrorHandler::fireError('Directory "' . LC_DIR_TMP . '" is not writeable');
        }

        $path = \Includes\Utils\FileManager::getUniquePath(
            LC_DIR_TMP,
            uniqid() . '.' . \Includes\Utils\PHARManager::getExtension() ?: 'tar'
        );

        return (isset($response->body) && \Includes\Utils\FileManager::write($path, $response->body)) ? $path : null;
    }

    /**
     * Common method to validate response
     *
     * FIXME: must ignore unknown fields in data from marketplace
     *
     * @param array $data   Data to validate
     * @param array $schema Validation schema
     *
     * @return boolean
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
     */
    public function getMarketplaceURL()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('marketplace', 'url'));
    }

    /**
     * Return true if store should send its domain name to marketplace
     *
     * @return boolean
     */
    public function isSendShopDomain()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('marketplace', 'send_shop_domain'));
    }

    /**
     * Get enpoint URL for certain action
     *
     * @param string $action Action name
     *
     * @return string
     */
    protected function getMarketplaceActionURL($action)
    {
        return \Includes\Utils\Converter::trimTrailingChars($this->getMarketplaceURL(), '/') . '/' . $action;
    }

    /**
     * To determine what type of archives to download
     *
     * @return boolean
     */
    protected function canCompress()
    {
        return \Includes\Utils\PHARManager::canCompress();
    }

    /**
     * Clear saved data
     *
     * @return void
     */
    protected function clearUpgradeCell()
    {
        \XLite\Core\TmpVars::getInstance()->{\XLite\Upgrade\Cell::CELL_NAME} = null;
    }

    // }}}

    // {{{ Error handling

    /**
     * Log error
     *
     * @param string $action  Current request action
     * @param string $message Message to log
     * @param array  $args    Message args OPTIONAL
     * @param array  $data    Data sent/received OPTIONAL
     *
     * @return void
     */
    protected function logError($action, $message, array $args = array(), array $data = array())
    {
        $this->error = $message;

        $this->logCommon('Error', $action, $message, $args, $data);
    }

    /**
     * Log warning
     *
     * @param string $action  Current request action
     * @param string $message Message to log
     * @param array  $args    Message args OPTIONAL
     * @param array  $data    Data sent/received OPTIONAL
     *
     * @return void
     */
    protected function logWarning($action, $message, array $args = array(), array $data = array())
    {
        $this->logCommon('Warning', $action, $message, $args, $data);
    }

    /**
     * Log info
     *
     * @param string $action  Current request action
     * @param string $message Message to log
     * @param array  $args    Message args OPTIONAL
     * @param array  $data    Data sent/received OPTIONAL
     *
     * @return void
     */
    protected function logInfo($action, $message, array $args = array(), array $data = array())
    {
        $this->logCommon('Info', $action, $message, $args, $data);
    }

    /**
     * Common logging procedure
     *
     * @param string $method  Method to call
     * @param string $action  Current request action
     * @param string $message Message to log
     * @param array  $args    Message args OPTIONAL
     * @param array  $data    Data sent/received OPTIONAL
     *
     * @return void
     */
    protected function logCommon($method, $action, $message, array $args = array(), array $data = array())
    {
        $message = 'Marketplace [' . $action . ']: ' . lcfirst($message);

        if (!empty($data) && \Includes\Utils\ConfigParser::getOptions(array('marketplace', 'log_data'))) {
            $message .= '; data: ' . PHP_EOL . '{{data}}';
            $args += array('data' => print_r($data, true));
        }

        \XLite\Upgrade\Logger::getInstance()->{'log' . $method}($message, $args, false);
    }

    // }}}
}
