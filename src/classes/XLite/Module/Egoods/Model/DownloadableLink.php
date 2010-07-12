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

namespace XLite\Module\Egoods\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class DownloadableLink extends \XLite\Model\AModel
{
    public $alias = "downloadable_links";

    public $primaryKey = array('access_key');
    public $defaultOrder = "file_id";

    public $fields = array(
            "access_key"			=> '',
            "file_id"				=> 0,
            "available_downloads"	=> 9999,
            "exp_time"				=> 0,
            "expire_on"				=> 'T', // T - Time, D - downloads, B - time&downloads
            "link_type"				=> 'M', // M - Manual, A - Automatic
            );

    function create()
    {
        if (is_null($this->get('access_key')) || $this->get('access_key') == '') {
            $this->set('access_key', md5(microtime(true)));
        }
        parent::create();
    }

    function printDate($mod1, $mod2, $mod3, $delim = '/')
    {
        return date( "$mod1$delim$mod2$delim$mod3", $this->get('exp_time'));
    }

    function isActive()
    {
        switch ($this->get('expire_on')) {
            case 'T':
                if (time() < $this->get('exp_time')) {
                    return true;
                }
            break;

            case 'D':
                if ($this->get('available_downloads') > 0) {
                    return true;
                }
            break;

            case 'B':
                if (time() < $this->get('exp_time') && $this->get('available_downloads') > 0) {
                    return true;
                }
            break;
        }
        return false;
    }

    function getDeniedReason()
    {
        switch ($this->get('expire_on')) {
            case 'T':
                if (time() >= $this->get('exp_time')) {
                    return 'T';
                }
            break;

            case 'D':
                if ($this->get('available_downloads') < 1) {
                    return 'D';
                }
            break;

            case 'B':
                if (time() > $this->get('exp_time')) {
                    return 'T';
                }
                if ($this->get('available_downloads') < 1) {
                    return 'D';
                }
            break;
        }
        return '';
    }
}
