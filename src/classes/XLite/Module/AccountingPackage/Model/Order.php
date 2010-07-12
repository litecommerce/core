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

namespace XLite\Module\AccountingPackage\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    function getDetailsString()
    {
        $str = array();
        $details = $this->get('details');
        if (is_array($details)) {
            foreach ($details as $name => $val) {
                $name = $this->getDetailLabel($name);
                if (!empty($name)) {
                    $str[] = $name . "=" . $val;
                }
            }
        }
        if (count($str) > 0) {
            $str = implode(", ", $str);
            $str = str_replace("\n", " ", $str);
            $str = str_replace("\r", " ", $str);
            $str = str_replace("\t", " ", $str);
        } else {
            $str = "";
        }

        return $str;
    }
}
