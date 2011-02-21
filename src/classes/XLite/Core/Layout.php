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
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentInterface = \XLite::CUSTOMER_INTERFACE;

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

        $list = isset($this->skinPaths[$interface]) ? $this->skinPaths[$interface] : array();

        $list[] = $this->getBaseSkinByInterface($interface);

        return $list;
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
                    'fs'  => LC_SKINS_DIR . $skin . ($locale ? LC_DS . $locale : ''),
                    'web' => static::PATH_SKIN . '/' . $skin . ($locale ? '/' . $locale : ''),
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
     * Returns the resource full path
     * 
     * @param string $shortPath Short path
     * @param string $interface Interface code OPTIONAL
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSkinResourceFullPath($shortPath, $interface = null)
    {
        $result = null;

        foreach ($this->getSkinPaths($interface) as $path) {
            $fullPath = $path['fs'] . LC_DS . $shortPath;
            if (file_exists($fullPath)) {
                $result = $fullPath;
                break;
            }
        }

        return $result;
    }

    /**
     * Returns the resource web path
     * 
     * @param string $shortPath Short path
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSkinResourceWebPath($shortPath, $outputType = self::WEB_PATH_OUTPUT_SHORT)
    {
        $result = null;

        foreach ($this->getSkinPaths() as $path) {
            $fullPath = $path['fs'] . LC_DS . $shortPath;
            if (file_exists($fullPath)) {
                $result = $path['web'] . '/' . $shortPath;
                break;
            }
        }

        if ($result && self::WEB_PATH_OUTPUT_SHORT != $outputType) {
            $type = self::WEB_PATH_OUTPUT_FULL == $outputType
                ? \Includes\Utils\URLManager::URL_OUTPUT_SHORT
                : \Includes\Utils\URLManager::URL_OUTPUT_FULL;

            $result = \Includes\Utils\URLManager::getShopURL(
                $result,
                \XLite\Core\Request::getInstance()->isHTTPS(),
                array(),
                $type
            );
        }

        return $result;
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
        $this->setSkin(self::PATH_ADMIN);
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
        $this->setSkin(self::PATH_CONSOLE);
    }

    /**
     * Set current skin as the mail one
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMailSkin()
    {
        $this->setSkin(\XLite\View\Mailer::MAIL_SKIN);
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
    }

    // }}}
}
