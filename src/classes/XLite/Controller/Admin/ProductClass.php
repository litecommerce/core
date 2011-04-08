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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Product class controller
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProductClass extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Constants
     */
    const STATUS_ERROR   = 'error';
    const STATUS_INAPPLY = 'inapply';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED  = 'failed';


    /**
     * data 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $data = array(
        'status' => self::STATUS_ERROR,
        'data'   => '',
    );


    /**
     * Remove product class
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRemove()
    {
        if (isset(\XLite\Core\Request::getInstance()->id)) {
            \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->deleteById(
                \XLite\Core\Request::getInstance()->id
            );
        }

        $this->setReturnURL($this->buildURL('product_classes'));
    }

    /**
     * AJAX product class update
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        $data = array(
            'name' => \XLite\Core\Request::getInstance()->name,
        );

        \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->updateById(
            \XLite\Core\Request::getInstance()->id,
            $data
        );
    }

    /**
     * AJAX product class adding
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionAdd()
    {
        $data = \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->insert(
            array(
                'name' => \XLite\Core\Request::getInstance()->name,
            )
        );

        $this->data['data'] = array(
            'id'   => $data->getId(),
            'name' => $data->getName(),
        );

        $this->data['status'] = static::STATUS_SUCCESS;
    }

    /**
     * Send JSON data after "ADD" action
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function actionPostprocessAdd()
    {
        header('Content-type: application/json');
        $data = json_encode($this->data);
        header('Content-Length: ' . strlen($data));

        echo ($data);

        exit (0);
    }

}
