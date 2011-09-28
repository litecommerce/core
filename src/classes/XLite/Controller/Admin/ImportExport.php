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
 * @since     1.0.10
 */

namespace XLite\Controller\Admin;

/**
 * Import-export controller
 * 
 * @see   ____class_see____
 * @since 1.0.10
 */
class ImportExport extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Import ste length 
     */
    const IMPORT_STEP_LENGTH = 50;

    /**
     * Delimiter 
     */
    const DELIMIER = ',';

    /**
     * FIXME- backward compatibility
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target', 'page');

    /**
     * Columns information
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $columns;

    /**
     * File pointer 
     * 
     * @var   resource
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $filePointer;

    // {{{ Tabs

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        return array(
            'import' => 'Import',
            'export' => 'Export',
        );
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPageTemplates()
    {
        return array(
            'import' => 'import_export/import.tpl',
            'export' => 'import_export/export.tpl',
        );
    }

    /**
     * Get page code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPage()
    {
        $page = $this->page;
        $pages = $this->getPages();

        return $page && isset($pages[$page]) ? $page : key($pages);
    }

    // }}}

    // {{{ Content

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Import / Export';
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    // }}}

    // {{{ Columns definition

    protected function getColumns()
    {
        if (!isset($this->columns)) {
            $this->columns = array();

            foreach ($this->defineProductColumns() as $name) {
                $this->columns[$name] = array(
                    'type'   => 'product',
                    'method' => ucfirst($name),
                );
            }

            foreach ($this->defineCalculatedColumns() as $name => $method) {
                $this->columns[$name] = array(
                    'type'   => 'calculated',
                    'method' => ucfirst($method),
                );
            }
        }

        return $this->columns;
    }

    /**
     * Define product columns 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function defineProductColumns()
    {
        return array(
            'productId',
            'SKU',
            'price',
            'enabled',
            'weight',
            'freeShipping',
            'cleanUrl',
            'arrivalDate',
        );
    }

    /**
     * Define calculated columns 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function defineCalculatedColumns()
    {
        return array(
            'categories'       => 'categories',
            'images'           => 'images',
            'inventoryEnabled' => 'inventory',
            'lowLimitEnabled'  => 'inventory',
            'lowLimit'         => 'inventory',
            'amount'           => 'inventory',
            'classes'          => 'classes',
        );
    }

    // }}}

    // {{{ Export

    /**
     * Export action
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function doActionExport()
    {
        $this->startExport();

        $this->exportHeader();

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Product')->getImportIterator() as $product) {
            $this->exportProduct($product);
            \XLite\Core\Database::getEM()->detach($product);
        }

        $this->finishExport();

    }

    /**
     * Start export 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function startExport()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="catalog.csv"; modification-date="' . date('r') . ';');

        $this->filePointer = fopen('php://output', 'w');
    }

    /**
     * Finish export 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function finishExport()
    {
        fclose($this->filePointer);
        $this->silent = true;
    }

    /**
     * Export file header 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportHeader()
    {
        $this->assembleExportRow(array_keys($this->getColumns()));
    }

    /**
     * Export product 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportProduct(\XLite\Model\Product $product)
    {
        $row = array();

        foreach ($this->getColumns() as $name => $info) {
            $cell = null;

            if ('calculated' == $info['type']) {
                $cell = $this->{'export' . $info['method']}($product, $name);

            } elseif ('product' == $info['type']) {
                $cell = $product->{'get' . $info['method']}();
            }

            $row[$name] = $cell;
        }

        $this->assembleExportRow($row);
    }

    /**
     * Assemble export row 
     * 
     * @param array $row Row data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function assembleExportRow(array $row)
    {
        fputcsv($this->filePointer, $row, static::DELIMIER);
    }

    /**
     * Export categories 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportCategories(\XLite\Model\Product $product)
    {
        $paths = array();

        foreach ($product->getCategories() as $category) {
            $paths[] = $category->getStringPath();
        }

        return implode(';', $paths);
    }

    /**
     * Export images
     *
     * @param \XLite\Model\Product $product Product
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportImages(\XLite\Model\Product $product)
    {
        $list = array();

        foreach ($product->getImages() as $image) {
            $list[] = $image->getFrontURL();
        }

        return implode(';', $list);
    }

    /**
     * Export inventory-based data
     *
     * @param \XLite\Model\Product $product Product
     * @param string               $name    $cell name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportInventory(\XLite\Model\Product $product, $name)
    {
        $inventory = $product->getInventory();
        $method = 'get' . ucfirst($name);

        return $inventory ? $inventory->$method() : null;
    }

    /**
     * Export classes
     *
     * @param \XLite\Model\Product $product Product
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function exportClasses(\XLite\Model\Product $product)
    {
        $list = array();

        foreach ($product->getClasses() as $class) {
            $list[] = $class->getName();
        }

        return implode(';', $list);
    }

    // }}}

    // {{{ Import

    /**
     * Import step
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function doActionImport()
    {
        if ($this->isAJAX()) {
            $cell = $this->getImportCell();

            if ($cell) {

                $this->processImportStep($cell);

                $this->setPureAction(true);

            } else {
                $this->valid = false;
                $this->hardRedirect = true;
                \XLite\Core\TopMessage::addError('Import The name of the tax has not been preserved, because that is not filled');
                $this->setReturnURL($this->buildURL($this->getTarget()));
            }
        }
    }

    /**
     * Get import cell 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getImportCell()
    {
        $cell = \XLite\Core\Session::getInstance()->importCell;

        if (is_array($cell)) {
            if (!isset($cell['path']) || !file_exists($cell['path'])) {
                $cell = null;
            }
        }

        return $cell;
    }

    /**
     * Process import step 
     * 
     * @param array $cell Import cell
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function processImportStep(array $cell)
    {
        $fp = fopen($cell['path'], 'rb');

        $columns = $this->getColumns();

        if ($cell['position']) {
            $counter = $cell['position'];
            while (0 < $counter) {
                fgetcsv($fp, 8192, static::DELIMIER);
                $counter--;
            }

            $headers = $cell['headers'];

        } else {
            $headers = fgetcsv($fp, 8192, static::DELIMIER);
            $cell['headers'] = $headers;
            $cell['position'] = 0;
        }

        $counter = static::IMPORT_STEP_LENGTH;
        while (0 < $counter) {

            $row = fgetcsv($fp, 8192, static::DELIMIER);

            $list = array();
            foreach ($headers as $index => $name) {
                if (isset($columns[$name])) {
                    $list[$name] = isset($row[$index]) ? $row[$index] : null;
                }
            }

            $product = $this->getProduct($list);
    
            foreach ($list as $index => $cell) {
                $info = $columns[$name];

                if ('calculated' == $info['type']) {
                    $this->{'import' . $info['method']}($product, $cell, $name);

                } elseif ('product' == $info['type']) {
                    $cell = $product->{'set' . $info['method']}($cell);
                }
            }
            
            $counter--;
            $cell['position']++;
        }

        \XLite\Core\Session::getInstance()->importCell = $cell;

        if (feof($this->filePointer)) {
            \XLite\Core\Session::getInstance()->importCell = null;
            \XLite\Core\Event::importFinish();

        } else {
            \XLite\Core\Event::importAfterStep();
        }

        fclose($this->filePointer);
    }

    /**
     * Get product (olr or new)
     * 
     * @param array $list Row data
     *  
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getProduct(array &$list)
    {
        $product = null;

        if (isset($list['productId'])) {
            $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(intval($list['productId']));
            unset($list['productId']);
        }

        if (!$product && isset($list['SKU'])) {
            $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('SKU' => $list['SKU']));
            if ($product) {
                unset($list['SKU']);
            }
        }

        if (!$product) {
            $product = new \XLite\Model\Product;
            \XLite\Core\Database::getEM()->persist($product);
        }

        return $product;
    }

    protected function importCategories(\XLite\Model\Product $product, $data)
    {
    }

    // }}}
}
