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

namespace XLite\Controller\Console;

/**
 * Db services controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Db extends \XLite\Controller\Console\AConsole
{
    /**
     * Load fixtures from YAML file
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionLoadFixtures()
    {
        $path = \XLite\Core\Request::getInstance()->path;
        if (!$path || !file_exists($path) || !is_readable($path)) {
            $this->printError('Path is invalid');

        } else {
            try {
                $loadedLines = \XLite\Core\Database::getInstance()->loadFixturesFromYaml($path);
                $this->printContent('Loaded lines: ' . $loadedLines);

            } catch (\PDOException $e) {
                $this->printError($e->getMessage());
            }
        }
    }

    /**
     * Get help for loadFixtures action
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHelpLoadFixtures()
    {
        return '--path="<path>"   Option with path to YAML file';
    }

    /**
     * Export DB schema 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionExportSchema()
    {
        $type = \XLite\Core\Request::getInstance()->type ?: \XLite\Core\Database::SCHEMA_CREATE;
        $type = strtolower($type);

        switch ($type) {
            case \XLite\Core\Database::SCHEMA_UPDATE;
            case \XLite\Core\Database::SCHEMA_DELETE;
                $schema = \XLite\Core\Database::getInstance()->getDBSchema($type);
                break;

            default:
                $schema = \XLite\Core\Database::getInstance()->getDBSchema(\XLite\Core\Database::SCHEMA_CREATE);
        }

        $contents = implode(';' . PHP_EOL, $schema) . ';' . PHP_EOL;

        if (\XLite\Core\Request::getInstance()->file) {
            $path = realpath(\XLite\Core\Request::getInstance()->file);
            if (!file_exists(dirname($path))) {
                $this->printError('\'' . $path . '\' is not found!');

            } else {
                file_put_contents($path, $contents);
            }

        } else {
            $this->printContent($contents);
        }
    }

    /**
     * Help for export_schema action
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHelpExportSchema()
    {
        return '--type="<type>"   Option with schema type (create or update or delete). Default - create' . PHP_EOL
            . '--file="<path>"   Option with export file path (optional). Default - input';
    }

    /**
     * Update schema
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateSchema()
    {
        $lines = \XLite\Core\Database::getInstance()->updateDBSchema();
        $this->printContent('Executed lines: ' . $lines);
    }
}
