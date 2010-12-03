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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\GiftCertificates\Controller\Admin;

/**
 * E-card
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class GiftCertificateEcard extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'ecard_id');

    /**
     * E-card 
     * 
     * @var    \XLite\Module\CDev\GiftCertificates\Model\ECard
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ecard = null;

    /**
     * Get return URL
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getReturnUrl()
    {
        return $this->buildUrl(
            'gift_certificate_ecards'
        );
    }

    /**
     * Get e-card 
     * 
     * @return \XLite\Module\CDev\GiftCertificates\Model\ECard
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getECard()
    {
        if (is_null($this->ecard)) {
            if ($this->get('ecard_id')) {
                $this->ecard = new \XLite\Module\CDev\GiftCertificates\Model\ECard($this->get('ecard_id'));

            } else {
                $this->ecard = new \XLite\Module\CDev\GiftCertificates\Model\ECard();
                $this->ecard->set('enabled', 1);
            }
        }

        return $this->ecard;
    }
    
    /**
     * Update e-card
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        if (!isset(\XLite\Core\Request::getInstance()->enabled)) {
            \XLite\Core\Request::getInstance()->enabled = 0; // checkbox
        }

        if (!empty(\XLite\Core\Request::getInstance()->new_template)) {
            \XLite\Core\Request::getInstance()->template = \XLite\Core\Request::getInstance()->new_template;
        }

        $this->getECard()->set('properties', \XLite\Core\Request::getInstance()->getData());
        $this->getECard()->modify();

        $this->doActionImages();
    }

    /**
     * Load thumbnail and image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionImages()
    {
        $this->getECard()->get('thumbnail')->handleRequest();
        $this->getECard()->get('image')->handleRequest();
    }

}
