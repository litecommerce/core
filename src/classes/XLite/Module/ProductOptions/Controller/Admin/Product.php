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

namespace XLite\Module\ProductOptions\Controller\Admin;

/**
 * Product modify
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    /**
     * Constructor
     * 
     * @param array $params Parameters
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->pages['product_options'] = 'Product options';
        $this->pageTemplates['product_options'] = 'modules/ProductOptions/product_options_lander.tpl';

        if (!in_array('language', $this->params)) {
            $this->params[] = 'language';
        }
        
    }

    /**
     * Update option groups list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateOptionGroups()
    {
        $data = \XLite\Core\Request::getInstance()->data;

        if (is_array($data) && $data) {
            $options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->findByIds(array_keys($data));

            if ($options) {
                foreach ($options as $option) {
                    $cell = $data[$option->getGroupId()];
                    $cell['enabled'] = isset($cell['enabled']) && $cell['enabled'];

                    $option->map($cell);

                    \XLite\Core\Database::getEM()->persist($option);
                }
                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    'The product option groups have been successfully updated'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The product option groups have not been successfully updated',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * Delete selected option groups 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteOptionGroups()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;
        if (is_array($mark) && $mark) {
            $options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->findByIds($mark);
            if ($options) {
                foreach ($options as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    'The product option groups have been deleted'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The product option groups have not been deleted',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }
}
