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

/**
 * Translation-owner abstract class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @MappedSuperclass
 */
abstract class XLite_Model_Base_I18n extends XLite_Model_AbstractEntity
{
    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->translations = new Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get default language code 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultLanguageCode()
    {
        return XLite_Model_Session::getInstance()->getLanguage()->code;
    }

    /**
     * Get translation 
     * 
     * @param string $code Language code
     *  
     * @return XLite_Model_Base_Translation
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTranslation($code = null)
    {
        if (is_null($code)) {
            $code = $this->getDefaultLanguageCode();
        }

        $result = null;

        foreach ($this->translations as $i => $t) {
            if ($t->code == $code) {
                $result = $t;
                break;
            }
        }

        if (!$result) {
            $className = get_called_class() . 'Translation';
            $result = new $className();
            $result->owner = $this;
            $result->code = $code;
            $this->translations[] = $result;
        }

        return $result;
    }

    /**
     * Map translations batch data
     * 
     * @param array $data Data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function mapTranslations(array $data)
    {
        foreach ($data as $lng => $fields) {
            foreach ($fields as $name => $value) {
                $this->getTranslation($lng)->$name = $value;
            }
        }
    }

    /**
     * Check - has object translation or not
     * 
     * @param string $code Language code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasTranslation($code = null)
    {
        if (is_null($code)) {
            $code = $this->getDefaultLanguageCode();
        }

        $result = false;

        foreach ($this->translations as $i => $t) {
            if ($t->code == $code) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
