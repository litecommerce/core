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
 * @since      3.0.0
 */

namespace XLite\RemoteModel;

/**
 * Marketplace requests collector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Marketplace extends \XLite\Base\Singleton
{

    /**
     * Script of marketplace
     */
    const INFO_SCRIPT_PATH  = 'get_info.php';

    /**
     * URL of marketplace
     */
    const MARKETPLACE_URL = 'https://www.litecommerce.com/marketplace/';

    const ADDONS_UPDATED  = 'addonsUpdated';
    const VERSION_UPDATED = 'versionUpdated';
    const LAST_VERSION    = 'lastVersion';
    const LAST_UPDATE_TTL = 86400;

    /**
     * Param to force update addons 
     */
    const P_FORCE_UPDATE = 'forceMPRequest';

    /**
     * Error status
     */
    const STATUS_ERROR = 0;

    /**
     * Success target
     */
    const STATUS_SUCCESS = 1;

    /**
     * Get action
     */
    const GET_ACTION = 'get';

    /**
     * License target
     */
    const LICENSE_TARGET = 'license';

    /**
     * Addons list target
     */
    const ADDONS_LIST_TARGET = 'addons';

    /**
     * Addon target 
     */
    const ADDON_TARGET = 'addon';

    /**
     * Version target 
     */
    const VERSION_TARGET = 'version';

    /**
     * Get module information by license key target
     */
    const INFO_BY_KEY_TARGET = 'info_by_key';

    /**
     * Author variable in request 
     */
    const MODULE_AUTHOR = 'author';

    /**
     * Module name variable in request
     */
    const MODULE_NAME = 'module';


    /**
     * Error message
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $error = null;


    /**
     * Return error message
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getError()
    {
        return $this->error;
    }


    /**  
     * Get marketplace URL
     * TODO: remove debug condition before release
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */

    public function getMarketplaceURL()
    {
        $debugOptions = \XLite::getInstance()->getOptions('debug');

        return isset($debugOptions['marketplace_dev_url'])
            ? $debugOptions['marketplace_dev_url']
            : static::MARKETPLACE_URL;
    }


    /**
     * Grab modules XML from the marketplace
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddonsXML()
    {   
        return $this->requestMarketplace(
            static::ADDONS_LIST_TARGET,
            static::GET_ACTION
        );
    }


    /**
     * Get LICENSE text for specific marketplace module
     * 
     * @param integer $moduleId Identificator of module in local database
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLicense($moduleId)
    {
        return $this->requestMarketplace(
            static::LICENSE_TARGET,
            static::GET_ACTION,
            $this->getModuleInfo($moduleId)
        );        
    }


    /**
     * Download module package to the Local Repository catalog
     * 
     * @param integer $moduleId       Identificator of module in local database
     * @param array   $additionalData Some additional data for request. (key for paid module)
     *  
     * @return string Status of downloading
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function retriveToLocalRepository($moduleId, $additionalData = array())
    {
        $moduleInfo = $this->getModuleInfo($moduleId);

        $file = $this->requestMarketplace(
            static::ADDON_TARGET,
            static::GET_ACTION,
            $additionalData + $moduleInfo
        );

        $result = static::STATUS_ERROR;

        $filename = $moduleInfo[static::MODULE_AUTHOR] . '_' . $moduleInfo[static::MODULE_NAME] . '.phar';

        if (is_null($this->getError())) {

            // TODO Retrive module name first!!
            $result = file_put_contents(
                LC_LOCAL_REPOSITORY . $filename,
                $file
            );

            $result = (false !== $result && 0 < $result)
                ? $filename
                : static::STATUS_ERROR;
        }

        return $result;
    }

    /**
     * Get last version
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastVersion()
    {   
        if (static::isVersionUpdateNeeded()) {
            $version = $this->requestMarketplace(
                static::VERSION_TARGET,
                static::GET_ACTION
            );

            \XLite\Core\TmpVars::getInstance()->LAST_VERSION = trim($version);
        }

        return \XLite\Core\TmpVars::getInstance()->LAST_VERSION;
    }

    /**
     * Retrive module information by license key
     * 
     * @param string $key License key
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleInfoByKey(string $key)
    {
        $response = $this->requestMarketplace(
            static::INFO_BY_KEY_TARGET,
            static::GET_ACTION,
            array(
                'license_key' => $key,
            )
        );

        $xml = new \DOMDocument();

        $result = $xml->loadXML($response);

        if (false === $result) {

            \XLite\Logger::getInstance()->log(
                'Bad XML response from Marketplace: '
                . PHP_EOL . $response,
                LOG_ERR
            );

            $result = array(
                'error' => 'Bad response from marketplace. Check log files.',
            );

        } else {

            $error = $xml->getElementsByTagName('error');

            if (0 >= $error->length) {

                $result = array(
                    'module' => $xml->getElementsByTagName('module')->item(0)->nodeValue,
                    'author' => $xml->getElementsByTagName('author')->item(0)->nodeValue,
                );

            } else {

                $result = array(
                    'error' => $error->item(0)->nodeValue,
                );
            }
        }

        return $result;
    }

    /**
     * Get Module information for Marketplace request
     * 
     * @param integer $moduleId Identificator of module in local database
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleInfo($moduleId)
    {
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);

        return array(
            static::MODULE_NAME   => $module->getName(),
            static::MODULE_AUTHOR => $module->getAuthor(),
        );
    }


    /**
     * Request of marketplace
     * 
     * @param string $target         Target of request (category)
     * @param string $action         Action of request (action)
     * @param array  $additionalData Some additional information
     *  
     * @return string Marketplace response
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function requestMarketplace($target, $action, array $additionalData = array())
    {
        $response = ''; 

        $request = new \XLite\Model\HTTPS();

        $request->url = \XLite\Core\Converter::getInstance()->buildURL(
            $target,
            $action,
            $additionalData,
            static::getMarketplaceURL() . static::INFO_SCRIPT_PATH
        );

        $request->method = 'GET';

        if (
            $request::HTTPS_SUCCESS == $request->request()
            && $request->response
        ) {
            // Success
            $response = $request->response;

        } else {

            // Error occured
            $this->error = $request->error;
        }

        return $response;
    }

    /**
     * Returns timestamp of the last version update
     * from the marketplace
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isVersionInfoActual()
    {
        return \XLite\Core\TmpVars::getInstance()->{\XLite\RemoteModel\Marketplace::VERSION_UPDATED}
            && (
                \XLite\Core\TmpVars::getInstance()->{\XLite\RemoteModel\Marketplace::VERSION_UPDATED}
                + \XLite\RemoteModel\Marketplace::LAST_UPDATE_TTL
            ) > LC_START_TIME;
    }

    /**
     * Check if needs to retreive latest version information 
     * from the market place
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isVersionUpdateNeeded()
    {
        return !static::isVersionInfoActual()
            || \XLite\Core\Request::getInstance()->{static::P_FORCE_UPDATE};
    }

}
