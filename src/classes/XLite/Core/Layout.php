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

namespace XLite\Core;

/**
 * Layout manager
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Layout extends \XLite\Base\Singleton
{
    /**
     * Repository paths 
     */

    const PATH_SKIN    = 'skins';
    const PATH_COMMON  = 'common';
    const PATH_ADMIN   = 'admin';
    const PATH_CONSOLE = 'console';
    const PATH_MAIL    = 'mail';


    /**
     * Web URL output types
     */

    const WEB_PATH_OUTPUT_SHORT = 'sort';
    const WEB_PATH_OUTPUT_FULL  = 'full';
    const WEB_PATH_OUTPUT_URL   = 'url';


    /**
     * Current skin
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $skin;

    /**
     * Current locale
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $locale;

    /**
     * Current skin path
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $path;

    /**
     * Current interface 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentInterface = \XLite::CUSTOMER_INTERFACE;

    /**
     * Main interface of mail. 
     * For example body.tpl of mail is inside MAIL interface 
     * but the inner widgets and templates in this template are inside CUSTOMER or ADMIN interfaces
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mailInterface = \XLite::CUSTOMER_INTERFACE;

    /**
     * Substutional skins list
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $substutionalSkins = array();

    /**
     * Skin paths 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $skinPaths = array();

    /**
     * Resources cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $resourcesCache = array();

    /**
     * Substutional skins cache flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $substutionalSkinsCache = false;

    // {{{ Common getters

    /**
     * Return skin name
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * Returns the layout path
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPath()
    {
        return $this->path;
    }

    // }}}

    // {{{ Substitutional skins routines

    /**
     * Add substutional skin 
     * 
     * @param string $name      Skin name
     * @param string $interface Interface code OPTONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addSubstutionalSkin($name, $interface = \XLite::CUSTOMER_INTERFACE)
    {
        if (!isset($this->substutionalSkins[$interface])) {
            $this->substutionalSkins[$interface] = array();
        }

        array_unshift($this->substutionalSkins[$interface], $name);
    }

    /**
     * Remove substutional skin 
     * 
     * @param string $name      Skin name
     * @param string $interface Interface code OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeSubstutionalSkin($name, $interface = null)
    {
        if (isset($interface)) {
            if (isset($this->substutionalSkins[$interface])) {
                $key = array_search($name, $this->substutionalSkins[$interface]);
                if (false !== $key) {
                    unset($this->substutionalSkins[$interface][$key]);
                }
            }

        } else {
            foreach ($this->substutionalSkins as $interface => $list) {
                $key = array_search($name, $list);
                if (false !== $key) {
                    unset($this->substutionalSkins[$interface][$key]);
                }
            }
        }
    }

    /**
     * Get skins list
     * 
     * @param string $interface Interface code
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSkins($interface = null)
    {
        $interface = $interface ?: $this->currentInterface;

        $list = isset($this->substutionalSkins[$interface]) ? $this->substutionalSkins[$interface] : array();

        $list[] = $this->getBaseSkinByInterface($interface);

        return $list;
    }

    /**
     * Get template full path 
     * 
     * @param string $shortPath       Template short path
     * @param string $currentSkin     Current skin
     * @param string $currentTemplate Current template short path
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTemplateFullPath($shortPath, $currentSkin, $currentTemplate)
    {
        $parts = explode(':', $shortPath, 2);

        if (1 == count($parts)) {
            if ('parent' == $shortPath) {
                $result = $this->getResourceParentFullPath($this->currentTemplate, $currentSkin);

            } else {
                $result = $this->getResourceFullPath($shortPath);
            }

        } elseif ('parent' == $parts[0]) {
            $result = $this->getResourceParentFullPath($parts[1], $currentSkin);

        } else {
            $result = $this->getResourceSkinFullPath($parts[1], $parts[0]);
        }

        return $result;
    }

    /**
     * Returns the resource full path
     * 
     * @param string  $shortPath Short path
     * @param string  $interface Interface code OPTIONAL
     * @param boolean $doMail    Flag to change mail interface
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResourceFullPath($shortPath, $interface = null, $doMail = true)
    {
        $interface = $interface ?: $this->currentInterface;

        if ($doMail && \XLite::MAIL_INTERFACE === $this->currentInterface) {

            $this->currentInterface = $this->mailInterface;
        }

        $key = $this->currentInterface . '.' . $interface . '.' . $shortPath;

        if (!isset($this->resourcesCache[$key])) {

            foreach ($this->getSkinPaths($interface) as $path) {

                $fullPath = $path['fs'] . LC_DS . $shortPath;

                if (file_exists($fullPath)) {

                    $this->resourcesCache[$key] = $path;

                    break;
                }
            }
        }

        return isset($this->resourcesCache[$key])
            ? $this->resourcesCache[$key]['fs'] . LC_DS . $shortPath
            : null;
    }

    /**
     * Returns the resource full path before parent skin
     * 
     * @param string $shortPath  Short path
     * @param string $parentSkin Parent skin
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResourceParentFullPath($shortPath, $parentSkin)
    {
        $result = null;
        $found = false;

        foreach ($this->getSkinPaths($this->currentInterface) as $path) {
            if ($found) {
                $fullPath = $path['fs'] . LC_DS . $shortPath;
                if (file_exists($fullPath)) {
                    $result = $fullPath;
                    break;
                }
            }

            if ($path['name'] == $parentSkin) {
                $found = true;
            }
        }

        return $result;
    }

    /**
     * Returns the resource full path by skin
     * 
     * @param string $shortPath Short path
     * @param string $skin      Skin name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResourceSkinFullPath($shortPath, $skin)
    {
        $result = null;

        foreach ($this->getSkinPaths($this->currentInterface) as $path) {
            if ($path['name'] == $skin) {
                $fullPath = $path['fs'] . LC_DS . $shortPath;
                if (file_exists($fullPath)) {
                    $result = $fullPath;
                }
                break;
            }
        }

        return $result;
    }

    /**
     * Returns the resource web path
     * 
     * @param string $shortPath  Short path
     * @param string $outputType Output type OPTIONAL
     * @param string $interface  Interface code OPTIONAL
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResourceWebPath($shortPath, $outputType = self::WEB_PATH_OUTPUT_SHORT, $interface = null)
    {
        $interface = $interface ?: $this->currentInterface;
        $key = $interface . '.' . $shortPath;

        if (!isset($this->resourcesCache[$key])) {
            foreach ($this->getSkinPaths($interface) as $path) {
                $fullPath = $path['fs'] . LC_DS . $shortPath;
                if (file_exists($fullPath)) {
                    $this->resourcesCache[$key] = $path;
                    break;
                }
            }
        }

        return isset($this->resourcesCache[$key])
            ? $this->prepareResourceURL($this->resourcesCache[$key]['web'] . '/' . $shortPath, $outputType)
            : null;
    }

    /**
     * Prepare skin URL 
     * 
     * @param string $shortPath  Short path
     * @param string $outputType Output type OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function prepareSkinURL($shortPath, $outputType = self::WEB_PATH_OUTPUT_SHORT)
    {
        $skins = $this->getSkinPaths($this->currentInterface);
        $path = array_pop($skins);

        return $this->prepareResourceURL($path['web'] . '/' . $shortPath, $outputType);

    }

    /**
     * Save substitutonal skins data into cache
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function saveSubstitutonalSkins()
    {
        \XLite\Core\Database::getCacheDriver()->save(
            get_called_class() . '.SubstitutonalSkins',
            $this->resourcesCache
        );
    }

    /**
     * Get skin paths (file system and web)
     * 
     * @param string  $interface Interface code OPTIONAL
     * @param boolean $reset     Local cache reset flag OPTIONAL
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSkinPaths($interface = null, $reset = false)
    {
        $interface = $interface ?: $this->currentInterface;

        if (!isset($this->skinPaths[$interface]) || $reset) {
            $this->skinPaths[$interface] = array();

            $locale = \XLite::COMMON_INTERFACE == $interface
                ? false
                : $this->locale;

            foreach ($this->getSkins($interface) as $skin) {
                $this->skinPaths[$interface][] = array(
                    'name' => $skin,
                    'fs'   => LC_SKINS_DIR . $skin . ($locale ? LC_DS . $locale : ''),
                    'web'  => static::PATH_SKIN . '/' . $skin . ($locale ? '/' . $locale : ''),
                );
            }
        }

        return $this->skinPaths[$interface];
    }

    /**
     * Get base skin by interface code
     * 
     * @param string $interface Interface code
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBaseSkinByInterface($interface = null)
    {
        switch ($interface) {
            case \XLite::ADMIN_INTERFACE:
                $skin = static::PATH_ADMIN;
                break;

            case \XLite::CONSOLE_INTERFACE:
                $skin = static::PATH_CONSOLE;
                break;

            case \XLite::MAIL_INTERFACE:
                $skin = static::PATH_MAIL;
                break;

            case \XLite::COMMON_INTERFACE:
                $skin = static::PATH_COMMON;
                break;

            default:
                $options = \XLite::getInstance()->getOptions('skin_details');
                $skin = $options['skin'];
        }

        return $skin;
    }

    /**
     * Prepare resource URL 
     * 
     * @param string $url        URL
     * @param string $outputType Output type
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareResourceURL($url, $outputType)
    {
        if ($url && self::WEB_PATH_OUTPUT_SHORT != $outputType) {
            $type = self::WEB_PATH_OUTPUT_FULL == $outputType
                ? \Includes\Utils\URLManager::URL_OUTPUT_SHORT
                : \Includes\Utils\URLManager::URL_OUTPUT_FULL;

            $url = \Includes\Utils\URLManager::getShopURL(
                $url,
                \XLite\Core\Request::getInstance()->isHTTPS(),
                array(),
                $type
            );
        }

        return $url;
    }

    /**
     * Restore substitutonal skins data from cache
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function restoreSubstitutonalSkins()
    {
        $data = \XLite\Core\Database::getCacheDriver()->fetch(
            get_called_class() . '.SubstitutonalSkins'
        );

        if ($data && is_array($data)) {

            $this->resourcesCache = $data;
        }
    }

    // }}}

    // {{{ Initialization routines

    /**
     * Set current skin as the admin one
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setAdminSkin()
    {
        $this->currentInterface = \XLite::ADMIN_INTERFACE;
        $this->setSkin(static::PATH_ADMIN);
    }

    /**
     * Set current skin as the admin one
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setConsoleSkin()
    {
        $this->currentInterface = \XLite::CONSOLE_INTERFACE;
        $this->setSkin(static::PATH_CONSOLE);
    }

    /**
     * Set current skin as the mail one
     * 
     * @param string $interface Interface to use after MAIL one
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMailSkin($interface = \XLite::CUSTOMER_INTERFACE)
    {
        $this->currentInterface = \XLite::MAIL_INTERFACE;

        $this->mailInterface = $interface;

        $this->setSkin(static::PATH_MAIL);
    }

    /**
     * Set current skin as the customer one
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setCustomerSkin()
    {
        $this->skin = null;
        $this->locale = null;
        $this->currentInterface = \XLite::CUSTOMER_INTERFACE;

        $this->setOptions();
    }


    /**
     * Set current skin 
     * 
     * @param string $skin New skin
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
        $this->setPath();
    }

    /**
     * Set some class properties
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setOptions()
    {
        $options = \XLite::getInstance()->getOptions('skin_details');

        foreach (array('skin', 'locale') as $name) {
            if (!isset($this->$name)) {
                $this->$name = $options[$name];
            }
        }

        $this->setPath();
    }

    /**
     * Set current skin path
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setPath()
    {
        $this->path = self::PATH_SKIN . LC_DS . $this->skin . LC_DS . $this->locale . LC_DS;
    }

    /**
     * Constructor
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->setOptions();

        $this->substutionalSkinsCache = (bool)\XLite::getInstance()
            ->getOptions(array('performance', 'substutional_skins_cache'));

        if ($this->substutionalSkinsCache) {
            $this->restoreSubstitutonalSkins();
            register_shutdown_function(array($this, 'saveSubstitutonalSkins'));
        }
    }

    // }}}
}
