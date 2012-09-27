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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Console;

/**
 * Db services controller
 *
 */
class Db extends \XLite\Controller\Console\AConsole
{
    /**
     * Load fixtures from YAML file
     *
     * @return void
     * @throws \PDOException
     */
    protected function doActionLoadFixtures()
    {
        $isTemporary = false;
        $path = \XLite\Core\Request::getInstance()->path;

        if (!$path && $this->isInputStream()) {
            $path = $this->saveInputStream();
            $isTemporary = true;
        }

        if (!$path) {
            $this->printError('Path is not specified');

        } elseif (!file_exists($path) || !is_readable($path)) {
            $this->printError('Path is invalid');

        } else {
            try {
                $loadedLines = \XLite\Core\Database::getInstance()->loadFixturesFromYaml($path);
                $this->printContent('Loaded lines: ' . $loadedLines);

            } catch (\PDOException $e) {
                $this->printError(strip_tags($e->getMessage()));
                throw $e;
            }
        }

        if ($isTemporary && $path) {
            unlink($path);
        }
    }

    /**
     * Get help for loadFixtures action
     *
     * @return string
     */
    protected function getHelpLoadFixtures()
    {
        return '--path="<path>"   Option with path to YAML file';
    }

    /**
     * Unload fixtures based on YAML file
     *
     * @return void
     * @throws \PDOException
     */
    protected function doActionUnloadFixtures()
    {
        $isTemporary = false;
        $path = \XLite\Core\Request::getInstance()->path;

        if (!$path && $this->isInputStream()) {
            $path = $this->saveInputStream();
            $isTemporary = true;
        }

        if (!$path) {
            $this->printError('Path is not specified');

        } elseif (!file_exists($path) || !is_readable($path)) {
            $this->printError('Path is invalid');

        } else {
            try {
                $unloadedLines = \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($path);
                $this->printContent('Unloaded lines: ' . $unloadedLines);

            } catch (\PDOException $e) {
                $this->printError(strip_tags($e->getMessage()));
                throw $e;
            }
        }

        if ($isTemporary && $path) {
            unlink($path);
        }
    }

    /**
     * Get help for unloadFixtures action
     *
     * @return string
     */
    protected function getHelpUnloadFixtures()
    {
        return '--path="<path>"   Option with path to YAML file';
    }

    /**
     * Export DB schema
     *
     * @return void
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
            $this->pureOutput = true;
            $this->printContent($contents);
        }
    }

    /**
     * Help for export_schema action
     *
     * @return string
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
     */
    protected function doActionUpdateSchema()
    {
        $lines = \XLite\Core\Database::getInstance()->updateDBSchema();
        $this->printContent('Executed lines: ' . $lines);
    }

    /**
     * Drop DB schema
     *
     * @return void
     */
    protected function doActionDropSchema()
    {
        $lines = \XLite\Core\Database::getInstance()->dropDBSchema();
        $this->printContent('Executed lines: ' . $lines);
    }

    /**
     * Recreate schema
     *
     * @return void
     */
    protected function doActionRecreateSchema()
    {
        $lines = \XLite\Core\Database::getInstance()->dropDBSchema();
        $lines += \XLite\Core\Database::getInstance()->updateDBSchema();
        $this->printContent('Executed lines: ' . $lines);
    }

    /**
     * Truncate all data
     *
     * @return void
     */
    protected function doActionTruncate()
    {
        $type = \XLite\Core\Request::getInstance()->type;
        $type = $type ? strtolower($type) : 'all';

        $lines = 0;
        $types = array_map('trim', explode(',', $type));

        foreach ($types as $type) {
            switch ($type) {
                case \XLite\Model\Repo\ARepo::TYPE_STORE:
                case \XLite\Model\Repo\ARepo::TYPE_SECONDARY:
                case \XLite\Model\Repo\ARepo::TYPE_SERVICE:
                    $lines += \XLite\Core\Database::getInstance()->truncateByType($type);
                    break;

                default:
                    $lines += \XLite\Core\Database::getInstance()->truncate($list);
            }
        }

        $this->printContent('Truncated tables: ' . $lines);
    }
}
