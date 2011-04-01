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
 * @since     3.0.0
 */

namespace XLite\View\Order\Details\Admin;

/**
 * Model 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Model extends \XLite\View\Order\Details\Base\AModel
{
    /**
     * Main order info
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $schemaMain = array(
        'order_id' => array(
            self::SCHEMA_CLASS => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL => 'Order ID',
        ),
        'date' => array(
            self::SCHEMA_CLASS => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL => 'Order date',
        ),
        'status' => array(
            self::SCHEMA_CLASS => '\XLite\View\FormField\Select\OrderStatus',
            self::SCHEMA_LABEL => 'Order status',
        ),
    );


    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        $this->sections['main'] = 'Info';

        parent::__construct($params, $sections);
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'order/invoice/style.css';

        return $list;
    }


    /**
     * Alias
     * 
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOrder()
    {
        return $this->getModelObject();
    }

    /**
     * Format order date
     * 
     * @param array &$data Widget params
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareFieldParamsDate(array &$data)
    {
        $data[self::SCHEMA_VALUE] = $this->time_format($this->getModelObject()->getDate());
    }
}
