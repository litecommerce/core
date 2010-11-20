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

namespace XLite\Controller\Admin;

/**
 * Payment methods
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PaymentMethods extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Payment methods';
    }

    /**
     * Update payment methods
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $data = \XLite\Core\Request::getInstance()->data;

       if (!is_array($data)) {

            // TODO - add top message

        } else {
            $code = $this->getCurrentLanguage();
            foreach ($data as $id => $row) {
                $m = \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')->find($id);

                if (!$m) {
                    // TODO - add top message
                    continue;
                }

                $m->getTranslation($code)->setName($row['name']);
                $m->getTranslation($code)->setDescription($row['description']);
                $m->setOrderby(intval($row['orderby']));
                $m->setEnabled(isset($row['enabled']) && '1' == $row['enabled']);

                \XLite\Core\Database::getEM()->persist($m);
            }

            \XLite\Core\Database::getEM()->flush();
        }
    }
}
