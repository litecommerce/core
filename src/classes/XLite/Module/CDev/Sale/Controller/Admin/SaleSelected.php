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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.7
 */

namespace XLite\Module\CDev\Sale\Controller\Admin;

/**
 * Sale selected controller
 *
 * @see   ____class_see____
 * @since 1.0.7
 */
class SaleSelected extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Set the sale price';
    }

    /**
     * Set sale price parameters for products list
     *
     * @return null
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSetSalePrice()
    {
        $form = new \XLite\Module\CDev\Sale\View\Form\SaleSelectedDialog();

        $requestData = $form->getRequestData();

        if ($form->getValidationMessage()) {

            \XLite\Core\TopMessage::addError($form->getValidationMessage());

        } else {

            \XLite\Core\Database::getRepo('\XLite\Model\Product')->updateInBatchById($this->getUpdateInfo());

            \XLite\Core\TopMessage::addInfo(
                'Products information has been successfully updated'
            );
        }

        $this->setReturnURL($this->buildURL('product_list', '', array('mode' => 'search')));
    }

    /**
     * Return result array to update in batch list of products
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpdateInfo()
    {
        return array_fill_keys(
            array_keys($this->getToDelete()),
            $this->getUpdateInfoElement()
        );
    }

    /**
     * Return one element to update.
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpdateInfoElement()
    {
        $data = $this->getPostedData();

        return array('participateSale' => (0 != $data['salePriceValue'])) + $data;
    }
}
