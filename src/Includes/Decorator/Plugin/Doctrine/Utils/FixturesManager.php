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
 * @subpackage Includes_Decorator_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Utils;

/**
 * Fixtures manager 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class FixturesManager extends \Includes\Decorator\Plugin\Doctrine\ADoctrine
{
    /**
     * Modules list file name
     */
    const FIXTURES_FILE_NAME = '.decorator.fixtures.ini.php';


    /**
     * Get fixtures paths list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getFixtures()
    {
        $list = array();

        $path = static::getFixturesFilePath();

        if ($path) {
            foreach (parse_ini_file($path, false) as $p) {

                if (!file_exists($p)) {
                    $p = LC_DIR . LC_DS . $p;
                }

                if (file_exists($p) && is_readable($p)) {
                    $list[] = $p;
                }
            }

            $list = array_unique($list);
        }

        return $list;
    }

    /**
     * Remove fixtures 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function removeFixtures()
    {
        $path = static::getFixturesFilePath();
        if ($path) {
            @unlink($path);
        }
    }

    /**
     * Add path to fixtures list 
     * 
     * @param string $path Fixture file path
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function addFixtureToList($path)
    {
        $list = static::getFixtures();

        $path = preg_match('/^(?:sql|classes)/Ss', $path)
            ? $path
            : substr($path, strlen(LC_DIR) + 1);

        $list[] = LC_DIR . LC_DS . $path;

        static::saveFile($list);
    }

    /**
     * Get file path with fixtures paths
     *
     * @return string|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getFixturesFilePath()
    {
        $path = LC_VAR_DIR . static::FIXTURES_FILE_NAME;

        return (file_exists($path) && is_readable($path)) ? $path : null;
    }

    /**
     * Save fixtures to file 
     * 
     * @param array $list Fixtures paths list
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function saveFile(array $list)
    {
        $path = LC_VAR_DIR . static::FIXTURES_FILE_NAME;
        $fp = fopen($path, 'w');

        fwrite($fp, '; <' . '?php /*' . PHP_EOL);

        $i = 1;
        foreach (array_unique($list) as $p) {
            fwrite($fp, $i . ' = "' . $p . '"' . PHP_EOL);
            $i++;
        }

        fwrite($fp, '; */ ?' . '>');
        fclose($fp);
    }

}
