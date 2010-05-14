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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Promotion_Model_Category extends XLite_Model_Category implements XLite_Base_IDecorator
{
    function delete()
    {
        $so = new XLite_Module_Promotion_Model_SpecialOffer();
        $soDeletedCategories = $so->findAll("category_id='" . $this->get('category_id') . "'");
        if (is_array($soDeletedCategories) && count($soDeletedCategories) > 0) {
            foreach ($soDeletedCategories as $sodp) {
                $sodp->markInvalid();
            }
        }

        $bp = new XLite_Module_Promotion_Model_BonusPrice();
        $bpDeletedCategories = $bp->findAll("category_id='" . $this->get('category_id') . "'");
        if (is_array($bpDeletedCategories) && count($bpDeletedCategories) > 0) {
            foreach ($bpDeletedCategories as $bpdp) {
                $sodp = new XLite_Module_Promotion_Model_SpecialOffer($bpdp->get('offer_id'));
                $sodp->markInvalid();
            }
        }

        // delete category
        parent::delete();
    }
}
