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

namespace XLite\Module\DrupalConnector\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class LandingLink extends \XLite\Model\AModel
{
    /**
     * Record TTL (seconds)
     */
    const TTL = 60;

    /**
     * Link id validation pattern (regular expression)
     */
    const ID_PATTERN = '/^[a-f0-9]{32}$/Ss';

    public static $_removed = false;

    public $fields = array(
            'link_id'    => '',
            'session_id' => '',
            'expiry'     => 0,
        );

    public $primaryKey = array('link_id');
    public $alias = 'landing_links';
    public $defaultOrder = 'expiry';

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

        $this->removeExpired();
    }

    /**
     * Create link
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create()
    {
        mt_srand();

        $this->setProperties(
            array(
                'link_id'    => md5(mt_rand(0, time())),
                'session_id' => \XLite\Core\Session::getInstance()->getID(),
                'expiry'     => time() + self::TTL,
            )
        );

        return parent::create();
    }

    /**
     * Get link 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLink()
    {
        $link = null;

        if ($this->isExists() && $this->get('link_id')) {
            $options = \XLite::getInstance()->getOptions('host_details');

            $link = 'http://' . $options['http_host'] . $options['web_dir'];
            if (substr($link, -1) != '/') {
                $link .= '/';
            }

            $link .= 'cart.php?target=cmsconnector&action=landing&id=' . $this->get('link_id');
        }

        return $link;
    }

    /**
     * Remove expired links
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function removeExpired()
    {
        if (!self::$_removed) {
            $query = 'DELETE FROM ' . $this->getTable() . ' WHERE expiry < ' . time();
            $this->db->query($query);

            self::$_removed = true;
        }
    }
}
