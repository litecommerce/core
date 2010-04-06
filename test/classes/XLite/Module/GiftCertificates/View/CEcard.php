<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ECard preview
 *  
 * @category  Litecommerce
 * @package   Litecommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

class XLite_Module_GiftCertificates_View_CEcard extends XLite_View_Abstract
{    
    public $gc = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return '';
    }

    function getTemplate()
    {
        return 'modules/GiftCertificates/ecards/' . $this->getComplex('gc.ecard.template') . '.tpl';
    }

    function getTemplateFile()
    {
        $layout = XLite_Model_Layout::getInstance();

        return 'skins/mail/' . $layout->get('locale') . '/' . $this->get('template');
    }
}
