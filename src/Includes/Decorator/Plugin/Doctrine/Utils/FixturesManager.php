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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Utils;

/**
 * FixturesManager 
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class FixturesManager extends \Includes\Decorator\Plugin\Doctrine\ADoctrine
{
    /**
     * Fixtures cache
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
    protected static $fixtures;

    /**
     * Get fixtures paths list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getFixtures()
    {
        if (!isset(static::$fixtures)) {
            static::$fixtures = array();
            $path = static::getFixturesFilePath();

            if (\Includes\Utils\FileManager::isFileReadable($path)) {
                foreach (parse_ini_file($path, false) as $file) {

                    if (static::checkFile($file)) {
                        static::$fixtures[] = $file;
                    }
                }
            }
        }

        return static::$fixtures;
    }

    /**
     * Remove fixtures
     *
     * @return void
     */
    public static function removeFixtures()
    {
        static::$fixtures = null;

        \Includes\Utils\FileManager::deleteFile(static::getFixturesFilePath());
    }

    /**
     * Add path to fixtures list
     *
     * @param string $file Fixture file path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addFixtureToList($file)
    {
        static::$fixtures[] = LC_DIR_ROOT . $file;

        static::saveFile();
    }

    /**
     * Get file path with fixtures paths
     *
     * @return string
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
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function saveFile()
    {
        $string  = '';

        foreach (array_values(array_unique(static::getFixtures())) as $index => $value) {
            $string .= ++$index . ' = "' . $value . '"' . PHP_EOL;
        }

        \Includes\Utils\FileManager::write(static::getFixturesFilePath(), '; <?php /*' . PHP_EOL . $string . '; */ ?>');
    }

    /**
     * Check if module is active
     *
     * @param string $file File name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected static function checkFile($file)
    {
        $module = \Includes\Utils\ModulesManager::getFileModule($file);

        return !isset($module) || \Includes\Utils\ModulesManager::isActiveModule($module);
    }
}
