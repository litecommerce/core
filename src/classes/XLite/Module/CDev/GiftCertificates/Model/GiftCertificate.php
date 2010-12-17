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

namespace XLite\Module\CDev\GiftCertificates\Model;

/**
 * Gift certifiocate
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class GiftCertificate extends \XLite\Model\AModel
{
    const GC_DOESNOTEXIST = 1;
    const GC_OK           = 2;
    const GC_DISABLED     = 3;
    const GC_EXPIRED      = 4;

    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'giftcerts';

    /**
     * Object properties (table filed => default value)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
        'gcid'                   => '', // GC unique ID (primary key)
        'profile_id'             => '', // certificate creator
        'purchaser'              => '', // 'From' field
        'recipient'              => '', // 'To' field
        'send_via'               => '', // 'E' (e-mail) / 'P' (post)
        'recipient_email'        => '',
        'recipient_firstname'    => '',
        'recipient_lastname'     => '',
        'recipient_address'      => '',
        'recipient_city'         => '',
        'recipient_state'        => '',
        'recipient_custom_state' => '',
        'recipient_zipcode'      => '',
        'recipient_country'      => '',
        'recipient_phone'        => '',
        'message'                => '',
        'greetings'              => '',
        'farewell'               => '',
        'amount'                 => '',
        'border'                 => '',
        'debit'                  => '',
        'status'                 => '', // A (active), D (disabled), U (used), P (pending), depending on order status, E (expired)
        'add_date'               => '',
        'expiration_date'        => '',
        'exp_email_sent'         => 0,
        'ecard_id'               => '',
    );

    /**
     * Primary keys names
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $primaryKey = array('gcid');

    /**
     * Gift certificate e-card (cache)
     * 
     * @var    \XLite\Module\CDev\GiftCertificates\Model\ECard
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ecard = null;

    /**
     * Recipient state (cache)
     * 
     * @var    \XLite\Model\State
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $recipientState = null;

    /**
     * Recipient country (cache)
     * 
     * @var    \XLite\Model\Country
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $recipientCountry = null;

    /**
     * Profile (cache)
     * 
     * @var    \XLite\Model\Profile
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gcProfile = null;

    /**
     * Get formatted message 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFormattedMessage()
    {
        return nl2br(htmlspecialchars($this->get('message')));
    }

    /**
     * Get recipient state 
     * 
     * @return \XLite\Model\State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRecipientState()
    {
        if (is_null($this->recipientState)) {
            $this->recipientState = \XLite\Core\Database::getEM()->find('\XLite\Model\State', $this->get('recipient_state'));
        }

        return $this->recipientState;
    }

    /**
     * Get recipient country 
     * 
     * @return \XLite\Model\Country
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRecipientCountry()
    {
        if (is_null($this->recipientCountry)) {
            $this->recipientCountry = \XLite\Core\Database::getEM()->find('\XLite\Model\Country', $this->get('recipient_country'));
        }

        return $this->recipientCountry;
    }

    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        if (
            'status' == $name
            && 'A' == $value
            && 'A' != $this->get('status')
            && 'E' == $this->get('send_via')
        ) {
            // send GC by e-mail
            $mail = new \XLite\View\Mailer();
            $mail->gc = $this;
            $mail->compose(
                $this->config->Company->site_administrator, 
                $this->get('recipient_email'),
                'modules/GiftCertificates'
            );
            $mail->send();
        }

        parent::set($name, $value);
    }

    /**
     * Generate fit certificate id 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function generateGC()
    {
        return generate_code(8);
    }

    /**
     * Validate gift certificate
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function validate()
    {
        $result = self::GC_OK;

        if (!$this->isExists()) {
            $result = self::GC_DOESNOTEXIST;

        } elseif ('E' == $this->get('status')) {
            $result = self::GC_EXPIRED;

        } elseif (time() > $this->get('expirationDate')) {
            $this->set('status', 'E');
            $this->update();

            $result = self::GC_EXPIRED;

        } elseif ($this->get('status') != 'A') {
            $result = self::GC_DISABLED;

        }

        return $result;
    }

    /**
     * Check - has database any e-cards or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasECards()
    {
        $ec = new \XLite\Module\CDev\GiftCertificates\Model\ECard();

        return 0 < count($ec->findAll('enabled = 1'));
    }

    /**
     * Get gift certificate e-card 
     * 
     * @return \XLite\Module\CDev\GiftCertificates\Model\ECard
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getECard()
    {
        if (is_null($this->ecard) && $this->get('ecard_id')) {
            $this->ecard = new \XLite\Module\CDev\GiftCertificates\Model\ECard($this->get('ecard_id'));
        }

        return $this->ecard;
    }

    /**
     * Show e-card body
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function showECardBody()
    {
        $c = new \XLite\Module\CDev\GiftCertificates\View\CEcard();
        $c->gc = $this;
        $c->init();
        $c->display();
    }

    /**
     * Get border height (footer or header)
     * 
     * @param boolean $bottom Bottom border flag OPTIONAL
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBorderHeight($bottom = false)
    {
        $layout = \XLite\Model\Layout::getInstance();
        $borderFile = LC_ROOT_DIR
            . 'skins/mail/'
            . $layout->get('locale')
            . '/modules/GiftCertificates/ecards/borders/'
            . $this->get('border')
            . ($bottom ? '_bottom' : '')
            . '.gif';

        $h = 0;

        if (is_readable($borderFile)) {
            list($w, $h) = getimagesize($borderFile);
        }

        return $h;
    }

    /**
     * Get bottom border height 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBottomBorderHeight()
    {
        return $this->getBorderHeight(true);
    }

    /**
     * Get borders directory
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBordersDir()
    {
        $layout = \XLite\Model\Layout::getInstance();

        return $this->xlite->getShopUrl(
            'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards/borders/'
        );
    }

    /**
     * Get border image URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBorderUrl()
    {
        return $this->getBordersDir() . $this->get('border') . '.gif';
    }

    /**
     * Get images directory 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImagesDir()
    {
        return $this->xlite->getShopUrl('');
    }

    /**
     * Get default gift certificate expiration period (mounths)
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultExpirationPeriod()
    {
        return $this->config->CDev->GiftCertificates->expiration;
    }

    /**
     * Get profile 
     * 
     * @return \XLite\Model\Profile
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProfile()
    {
        if (is_null($this->gcProfile)) {
            $this->gcProfile = new \XLite\Model\Profile($this->get('profile_id'));
        }

        return $this->gcProfile;
    }

    /**
     * Get expiration date 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExpirationDate()
    {
        $date = $this->get('expiration_date');
        if (0 >= $date) {
            $date = $this->get('add_date') + $this->getDefaultExpirationPeriod() * 30 * 24 * 3600;
        }

        return $date;
    }

    /**
     * Check - display (and send) expiration warning or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayWarning()
    {
        $result = false;

        $expDate = $this->getExpirationDate();
        $warnDate = $expDate - $this->config->CDev->GiftCertificates->expiration_warning_days * 24 * 3600;

        if (
            time() >= $warnDate
            && time() <= $expDate
        ) {
            if (
                $this->config->CDev->GiftCertificates->expiration_email
                && !$this->get('exp_email_sent')
                && 0 < $this->get('debit')
                && 'A' == $this->get('status')
            ) {
                // send warning notification
                $mailer = new \XLite\View\Mailer();
                $mailer->cert = $this;
                $mailer->compose(
                    $this->config->Company->site_administrator,
                    $this->get('recipient_email'),
                    'modules/GiftCertificates/expiration_notification'
                );
                $mailer->send();

                $this->set('exp_email_sent', 1);
                $this->update();
            }

            $result = true;
        }

        return $result;
    }

    /**
     * Get gift ceritficate expiration conditions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExpirationConditions()
    {
        $now = time();
        $expTime = $now + $this->config->CDev->GiftCertificates->expiration_warning_days * 24 * 3600;

        return array(
            'expiration_date > ' . $now . ' AND expiration_date < ' . $expTime,
            'debit > 0',
            'exp_email_sent = 0',
            'status = \'A\'',
        );
    }
}
