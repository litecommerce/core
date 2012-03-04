<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 *XLite\Core\DataSource\Ecwid\Categories class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.3
 */

/**
 * XLite_Tests_Core_DataSource_Ecwid_Categories
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class XLite_Tests_Core_DataSource_Ecwid_Categories extends XLite_Tests_TestCase
{

    /**
     * Test Ecwid categories collection iterator
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testCategories()
    {
        $model = new \XLite\Model\DataSource();
        $model->setParameterValue('storeid', 1003);

        $ecwid = new \XLite\Core\DataSource\Ecwid($model);

        $this->assertTrue($ecwid->isValid());

        $categories = $ecwid->getCategoriesCollection();

        $this->assertNotEmpty($categories->count());

        $this->assertTrue($categories->isValid());
    }

}
