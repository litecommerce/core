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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Language selector for editor page
 * 
 * @package XLite
 * @see     ____class_see____
 * @see        ____class_see____
 * @since   3.0.0
 */
class XLite_View_EditorLanguageSelector extends XLite_View_Abstract
{
    /**
     * Current language code 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentCode = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/language_selector_edit.tpl';
    }

    /**
     * Get languages list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLanguages()
    {
        $list = array();

        foreach (XLite_Core_Database::getRepo('XLite_Model_Language')->findActiveLanguages() as $l) {
            $list[$l->code] = $l->name;
        }

        return $list;
    }

    /**
     * Check - language is selected or not
     * 
     * @param string $code Language code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isLanguageSelected($code)
    {
        if (!isset($this->currentCode)) {
            $this->currentCode = XLite_Core_Request::getInstance()->language;
            if (!$this->currentCode) {
                $this->currentCode = XLite_Core_Translation::getCurrentLanguageCode();
            }
        }

        return $code == $this->currentCode;
    }
}
