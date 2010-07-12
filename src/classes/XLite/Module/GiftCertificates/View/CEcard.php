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

namespace XLite\Module\GiftCertificates\View;

/**
 * E-card (customer)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CEcard extends \XLite\View\AView
{
    /**
     * Gift certificate (cache)
     * 
     * @var    \XLite\Module\GiftCertificates\Model\GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gc = null;

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

    /**
     * Return current template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return 'modules/GiftCertificates/ecards/' . $this->getComplex('gc.ecard.template') . '.tpl';
    }

    /**
     * Return full template file name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplateFile()
    {
        $layout = \XLite\Model\Layout::getInstance();

        return 'skins/mail/' . $layout->get('locale') . '/' . $this->get('template');
    }
}
