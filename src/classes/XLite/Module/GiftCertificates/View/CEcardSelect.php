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
 * Select e-card (customer)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CEcardSelect extends \XLite\View\AView
{
    const PARAM_GCID  = 'gcid';

    /**
     * E-cards list
     * 
     * @var    array of \XLite\Module\GiftCertificates\Model\ECard
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ecards = null;

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_GCID => new \XLite\Model\WidgetParam\String('Gift certificate id', ''),
        );
    }

    /**
     * Get e-cards list
     * 
     * @return array of \XLite\Module\GiftCertificates\Model\ECard
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getECards()
    {
        if (is_null($this->ecards)) {
            $eCard = new \XLite\Module\GiftCertificates\Model\ECard();
            $this->ecards = $eCard->findAll('enabled = 1');
        }

        return $this->ecards;
    }

    /**
     * Get gift certificate 
     * 
     * @return \XLite\Module\GiftCertificates\Model\GiftCertificate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGC()
    {
        if (is_null($this->gc)) {
            $this->gc = new \XLite\Module\GiftCertificates\Model\GiftCertificate(
                $this->getParam(self::PARAM_GCID)
            );
        }

        return $this->gc;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/GiftCertificates/ecard' . (\XLite::isAdminZone() ? '_select' : '') . '.tpl';
    }

}
