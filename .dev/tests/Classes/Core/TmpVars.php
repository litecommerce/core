<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Core\TmpVars class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Core_TmpVars extends XLite_Tests_TestCase
{
    /**
     * testCreate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetSet()
    {
        \XLite\Core\TmpVars::getInstance()->test = 123;
        $this->assertEquals(123, \XLite\Core\TmpVars::getInstance()->test, 'test 1');

        \XLite\Core\TmpVars::getInstance()->test = array(1, 2, 3);
        $this->assertEquals(array(1, 2, 3), \XLite\Core\TmpVars::getInstance()->test, 'test 2');
    }

}
