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
 * @since     1.0.0
 */

namespace XLite\Module\CDev\Sale\View\Form;

/**
 * "Set the sale price" dialog form class
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class SaleSelectedDialog extends \XLite\View\Form\AForm
{
    /**
     * getDefaultTarget
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTarget()
    {
        return 'sale_selected';
    }

    /**
     * getDefaultAction
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultAction()
    {
        return 'set_sale_price';
    }

    /**
     * Get validator
     *
     * @return \XLite\Core\Validator\HashArray
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getValidator()
    {
        $validator = parent::getValidator();

        $data = $validator->addPair('postedData', new \XLite\Core\Validator\HashArray());
        $this->setDataValidators($data);

        return $validator;
    }

    /**
     * Set validators pairs for products data
     *
     * @param mixed $data Data
     *
     * @return null
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setDataValidators(&$data)
    {
        $this->setSaleDataValidators($data);
    }

    /**
     * Called before the includeCompiledFile()
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function initView()
    {
        parent::initView();

        $toDelete = array();

        if (is_array(\XLite\Core\Request::getInstance()->select)) {
            foreach (\XLite\Core\Request::getInstance()->select as $productId => $value) {

                $toDelete['select[' . $productId . ']'] = $productId;
            }
        }

        $postedData = array(
            'postedData[participateSale]' => true,
        );

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue($toDelete);
        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue($postedData);
    }


}
