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

define('NOTIFY_INTERVAL', 24 * 60 * 60);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class WaitingIP extends AModel
{
    public $fields = array(
                    "id" => "0",
                    "ip" => "",
                    "unique_key" => "",
                    "first_date" => "0",
                    "last_date" => "0",
                    "count" => "0"
                    );

    public $autoIncrement = "id";
    public $alias = "waitingips";

    function generateUniqueKey()
    {
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float) $sec + ((float) $usec * 1000000);
        if (isset($_SERVER['REMOTE_ADDR'])) $seed += (float) ip2long($_SERVER['REMOTE_ADDR']);
        if (isset($_SERVER['REMOTE_PORT'])) $seed += (float) $_SERVER['REMOTE_PORT'];
        srand($seed);
        $key = md5(uniqid(rand(), true));
        return $key;
    }

    function addNew($ip)
    {
        $now = time();
        $this->set('ip', $ip);
        $this->set('first_date', $now);
        $this->set('last_date', $now);
        $this->set('count', "1");
        $this->set('unique_key', $this->generateUniqueKey());
        $this->create();
    }

    function notifyAdmin()
    {
        $mail = new \XLite\Model\Mailer();
        $mail->waiting_ip = $this;
        $mail->adminMail = true;
        $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mail->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->site_administrator,
            'new_ip_notify_admin'
        );
        $mail->send();
    }

    function canNotify()
    {
        $now = time();
        $last_date = (int) $this->get('last_date');

        return (($now - $last_date) >= NOTIFY_INTERVAL);
    }

    function approveIP()
    {
        $ip = $this->get('ip');

        if (is_null($this->config->SecurityIP->allow_admin_ip) || !is_array($this->config->SecurityIP->allow_admin_ip)) {
            $list = array();

        } else {
            $list = $this->config->SecurityIP->allow_admin_ip;
            foreach ($list as $ip_array) {
                if ($ip_array['ip'] == $ip) {
                	return;
                }
            }

            $list[] = array('ip' => $ip, 'comment' => '');
        }

        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'SecurityIP',
                'name'     => 'allow_admin_ip',
                'value'    => serialize($list),
                'type'     => 'serialized'
            )
        );
    }
}
