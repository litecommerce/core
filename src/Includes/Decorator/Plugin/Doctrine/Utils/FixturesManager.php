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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Utils;

/**
 * Fixtures manager
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
abstract class FixturesManager extends \Includes\Decorator\Plugin\Doctrine\ADoctrine
{
    /**
     * Get fixtures paths list
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getFixtures()
    {
        $list = array();

        if (\Includes\Utils\FileManager::isFileReadable(static::getFixturesFilePath())) {
            foreach (parse_ini_file(static::getFixturesFilePath(), false) as $file) {

                // :FIXME: is it needed?
                if (!\Includes\Utils\FileManager::isFile($file)) {
                    $file = LC_DIR_ROOT . $file;
                }

                if (\Includes\Utils\FileManager::isFileReadable($file)) {
                    $list[] = $file;
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
     * @since  1.0.0
     */
    public static function removeFixtures()
    {
        \Includes\Utils\FileManager::deleteFile(static::getFixturesFilePath());
    }

    /**
     * Add path to fixtures list
     *
     * @param string $path Fixture file path
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addFixtureToList($path)
    {
        $list = static::getFixtures();
        $list[] = LC_DIR_ROOT . (preg_match('/^(?:sql|classes)/Ss', $path) ? $path : substr($path, strlen(LC_DIR) + 1));

        static::saveFile($list);
    }

    /**
     * Get file path with fixtures paths
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getFixturesFilePath()
    {
        return LC_DIR_VAR . '.decorator.fixtures.ini.php';
    }

    /**
     * Save fixtures to file
     *
     * @param array $list Fixtures paths list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function saveFile(array $list)
    {
        $string  = '';

        foreach (array_values(array_unique($list)) as $index => $value) {
            $string .= ++$index . ' = "' . $value . '"' . PHP_EOL;
        }

        \Includes\Utils\FileManager::write(static::getFixturesFilePath(), '; <?php /*' . PHP_EOL . $string . '; */ ?>');
    }
}
