<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Core\Converter and \Includes\Utils\Converter classes tests
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

class XLite_Tests_Core_Converter extends XLite_Tests_TestCase
{
    /**
     * Test on \Includes\Utils\Converter::removeCRLF
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.3
     */
    public function testRemoveCRLF()
    {
        $url = $etalonURL = 'admin.php?target=main';
        $this->assertEquals($etalonURL, \Includes\Utils\Converter::removeCRLF($url), 'removeCRLF() test #1 failed');

        $url = '   admin.php?target=main   ';
        $this->assertEquals($etalonURL, \Includes\Utils\Converter::removeCRLF($url), 'removeCRLF() test #2 failed');

        $url =<<<OUT
            admin.php?target=main  
OUT;
        $this->assertEquals($etalonURL, \Includes\Utils\Converter::removeCRLF($url), 'removeCRLF() test #3 failed');

        $this->assertEquals('', \Includes\Utils\Converter::removeCRLF(null), 'removeCRLF() test #4 failed');
        $this->assertEquals('0', \Includes\Utils\Converter::removeCRLF(0), 'removeCRLF() test #5 failed');
        $this->assertEquals('5', \Includes\Utils\Converter::removeCRLF(5), 'removeCRLF() test #6 failed');
    }
}
