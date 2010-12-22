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

namespace XLite\Module\CDev\DrupalConnector\Model;

/**
 * Landing link
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table  (name="langing_links")
 * @HasLifecycleCallbacks
 */
class LandingLink extends \XLite\Model\AEntity
{
    /**
     * Record TTL (seconds)
     */
    const TTL = 60;

    /**
     * Link id validation pattern (regular expression)
     */
    const ID_PATTERN = '/^[a-f0-9]{32}$/Ss';

    /**
     * Link unique id 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @Column (type="fixedstring", length="32")
     */
    protected $link_id;

    /**
     * Session unique id 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="fixedstring", length="32")
     */
    protected $session_id;

    /**
     * Expiry 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $expiry = 0;

    /**
     * Prepare persist
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     * @PrePersist
     */
    public function create()
    {
        mt_srand();

        $this->setLinkId(md5(mt_rand(0, time())));
        $this->setExpiry(time() + self::TTL);
    }

    /**
     * Get link 
     * 
     * @return string|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLink()
    {
        $link = null;

        if ($this->getLinkId()) {
            $options = \XLite::getInstance()->getOptions('host_details');

            $link = 'http://' . $options['http_host'] . $options['web_dir'];
            if (substr($link, -1) != '/') {
                $link .= '/';
            }

            $link .= 'cart.php?target=cmsconnector&action=landing&id=' . $this->getLinkId();
        }

        return $link;
    }
}
