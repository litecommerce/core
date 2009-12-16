<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Metric writer
 *  
 * @category   LiteCommerce_Tests
 * @package    LiteCommerce_Tests
 * @subpackage Main
 * @author     Ruslan R. Fazliev <rrf@x-cart.com> 
 * @copyright  Copyright (c) 2009 Ruslan R. Fazliev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php LiteCommerce license
 * @version    SVN: $Id$
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_MetricWriter
{

    static protected $fp = null;


    static public function init($fileName) {
        if (!is_string($fileName))
            throw new RuntimeException('XLite_Tests_MetricWriter constructor execute with wrong $filename argument');

        self::$fp = @fopen($fileName, 'a');
        if (!self::$fp)
            throw new RuntimeException('Can\'t open ' . $fileName . ' file');

    }

    static public function destruct() {
        if (self::$fp)
            @fclose(self::$fp);
    }

    static public function write($class, $test, $time, $memory) {
        if (self::$fp) {
            fwrite(self::$fp, $class.':' . $test . "\t" . $time . "\t" . $memory . "\n");
        }
    }
}
