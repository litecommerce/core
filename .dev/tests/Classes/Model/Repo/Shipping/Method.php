<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Repo\Shipping\Method class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Repo_Shipping_Method extends XLite_Tests_TestCase
{
    /**
     * testFindMethodsByProcessor 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindMethodsByProcessor()
    {
        $methods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findMethodsByProcessor('offline');

        $this->assertTrue(is_array($methods), 'findMethodsByProcessor() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'findMethodsByProcessor() must return an array of \XLite\Model\Shipping\Method instances');
        }
    }

    /**
     * findMethodsByIds 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindMethodsByIds()
    {
        $methods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findMethodsByIds(array(100, 101));

        $this->assertTrue(is_array($methods), 'findMethodsByIds() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'findMethodsByIds() must return an array of \XLite\Model\Shipping\Method instances');
            $this->assertTrue(in_array($method->getMethodId(), array(100,101)), 'findMethodsByIds() returned unexpected data');
        }
    }

}
