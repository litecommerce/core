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

namespace XLite\Model;

/**
 * Layoue manager
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

    const PATH_SKIN   = 'skins';
    const PATH_COMMON = 'common';
    const PATH_ADMIN  = 'admin';


    /**
     * Current locale
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $skin;

    /**
     * Current skin
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
     * Layouts list 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $list = array();


    // ------------------------------ Common getters -


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
     * Return full URL by the skindir-related one
     *
     * @param string $url Relative URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSkinURL($url)
    {
        return $this->path . $url;
    }

    /** 
     * Return full URL by the common repository-related one
     *
     * @param string $url Relative URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCommonSkinURL($url)
    {
        return self::PATH_SKIN . '/' . self::PATH_COMMON . '/' . $url;
    }

    /**
     * Return last part of the skin URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShortPath()
    {
        return $this->skin . LC_DS . $this->locale . LC_DS;
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


    // ------------------------------ Layout routines -


    /**
     * Override a template by a custom one
     * 
     * @param string $originalTemplate Template to override
     * @param string $overrideTemplate Custom template
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addLayout($originalTemplate, $overrideTemplate)
    {
        $this->list[$originalTemplate] = $overrideTemplate;
    }

    /**
     * Returns the widget template file name for this layout
     * 
     * @param string $template Template to check
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLayout($template)
    {
        return $this->path . (isset($this->list[$template]) ? $this->list[$template] : $template);
    }

    /**
     * Check if template is ovveriden by a module
     * 
     * @param string $template Template to check
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasLayout($template)
    {
        return isset($this->list[$template]);
    }


    // ------------------------------ Initialization routines -


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
        $this->setSkin(self::PATH_ADMIN);
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
            isset($this->$name) ?: ($this->$name = $options[$name]);
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
}
