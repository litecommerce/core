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
     * Delimiter 
     */
    const DELIMIER = ',';

    /**
     * IMport step TTL (seconds) 
     */
    const IMPORT_TTL = 10;

    /**
     * COlumn type codes 
     */
    const TYPE_STRING  = 'string';
    const TYPE_FLOAT   = 'float';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE    = 'date';

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

            foreach ($this->defineProductColumns() as $name => $col) {
                $this->columns[$name] = array(
                    'type'       => 'product',
                    'method'     => ucfirst($name),
                    'typeMethod' => isset($col['type']) ? ('By' . ucfirst($col['type'])) : null,
                    'length'     => isset($col['length']) ? $col['length'] : null,
                    'required'   => isset($col['requried']) && $col['requried'],
                );
            }

            foreach ($this->defineCalculatedColumns() as $name => $col) {
                $this->columns[$name] = array(
                    'type'       => 'calculated',
                    'method'     => ucfirst($col['method']),
                    'typeMethod' => isset($col['type']) ? ('By' . ucfirst($col['type'])) : null,
                    'length'     => isset($col['length']) ? $col['length'] : null,
                    'required'   => isset($col['requried']) && $col['requried'],
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
            'productId'        => array(),
            'sku'              => array('type' => static::TYPE_STRING, 'length' => 32, 'required' => true),
            'name'             => array('type' => static::TYPE_STRING, 'length' => 255, 'required' => true),
            'description'      => array('type' => static::TYPE_STRING, 'length' => 65536),
            'briefDescription' => array('type' => static::TYPE_STRING, 'length' => 65536),
            'metaTags'         => array('type' => static::TYPE_STRING, 'length' => 255),
            'metaDesc'         => array('type' => static::TYPE_STRING, 'length' => 65536),
            'metaTitle'        => array('type' => static::TYPE_STRING, 'length' => 255),
            'price'            => array('type' => static::TYPE_FLOAT),
            'enabled'          => array('type' => static::TYPE_BOOLEAN),
            'weight'           => array('type' => static::TYPE_FLOAT),
            'freeShipping'     => array('type' => static::TYPE_BOOLEAN),
            'cleanUrl'         => array('type' => static::TYPE_STRING, 'length' => 255),
            'arrivalDate'      => array('type' => static::TYPE_DATE),
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
            'categories'       => array(
                'method' => 'categories',
            ),
            'images'           => array(
                'method' => 'images',
            ),
            'inventoryEnabled' => array(
                'method' => 'inventory',
                'type'   => static::TYPE_BOOLEAN,
            ),
            'lowLimitEnabled'  => array(
                'method' => 'inventory',
                'type'   => static::TYPE_BOOLEAN,
            ),
            'lowLimitAmount'   => array(
                'method' => 'inventory',
                'type'   => static::TYPE_INTEGER,
            ),
            'amount'           => array(
                'method' => 'inventory',
                'type'   => static::TYPE_INTEGER,
            ),
            'classes'          => array(
                'method' => 'classes',
            ),
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
            $product = $product[0];
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
                if (isset($info['typeMethod']) && method_exists($this, 'export' . $info['typeMethod'])) {
                    $cell = $this->{'export' . $info['typeMethod']}($product->{'get' . $info['method']}());

                } else {
                    $cell = $product->{'get' . $info['method']}();
                }
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
     * Export boolean-type field
     *
     * @param mixed $data Data
     *
     * @return string Y or N
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function exportByBoolean($data)
    {
        return $data ? 'Y' : 'N';
    }

    /**
     * Export date-type field
     * 
     * @param integer $date Date as UNIX timestamp
     *  
     * @return string RFC 2822 formatted date
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function exportByDate($date)
    {
        return gmdate('r', $date);
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
        if ('inventoryEnabled' == $name) {
            $name = 'enabled';
        }

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
     * Ge loaded status
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isImportFileLoaded()
    {
        return \XLite\Core\Request::getInstance()->loaded && $this->getImportCell();
    }

    /**
     * Run controller
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function run()
    {
        if (\XLite\Core\Request::getInstance()->cancel_import) {
            $this->clearImportCell();
        }

        parent::run();
    }

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

                try {
                    $this->processImportStep($cell);

                } catch (\Exception $e) {
                    $this->valid = false;
                    \XLite\Core\TopMessage::getInstance()->add(
                        'The import process failed. For more detailed informations see the log',
                        array(),
                        null,
                        \XLite\Core\TopMessage::ERROR,
                        false,
                        false
                    );
                    \XLite\Logger::getInstance()->registerException($e);
                }

            } else {
                $this->valid = false;
                \XLite\Core\TopMessage::getInstance()->add(
                    'Interior storage for the import has been lost',
                    array(),
                    null,
                    \XLite\Core\TopMessage::ERROR,
                    false,
                    false
                );
            }

            $this->setPureAction(true);
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

        // Validation
        if (is_array($cell)) {
            if (!isset($cell['path']) || !file_exists($cell['path'])) {
                \XLite\Core\Session::getInstance()->importCell = null;
                $cell = null;
            }
        }

        // Sanitize
        if (is_array($cell)) {
            if (!isset($cell['position'])) {
                $cell['position'] = 0;
            }
            if (!isset($cell['new'])) {
                $cell['new'] = 0;
            }
            if (!isset($cell['old'])) {
                $cell['old'] = 0;
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
        $this->filePointer = fopen($cell['path'], 'rb');

        $columns = $this->getColumns();

        if ($cell['position']) {
            $counter = $cell['position'] + 1;
            while (0 < $counter) {
                fgetcsv($this->filePointer, 0, static::DELIMIER);
                $counter--;
            }

            $headers = $cell['headers'];

        } else {
            $headers = fgetcsv($this->filePointer, 0, static::DELIMIER);
            $cell['headers'] = $headers;
            $cell['row_length'] = count($headers);
            foreach ($headers as $index => $name) {
                if (!isset($columns[$name])) {
                    $this->logImportWarning(
                        static::t(
                            'Import mechanism does not know the field of X and it can not be imported',
                            array('name' => $name)
                        ),
                        0
                    );
                    $cell['row_length']--;
                }
            }
        }

        $expire = time() + static::IMPORT_TTL;
        while (time() < $expire && !feof($this->filePointer)) {

            $row = fgetcsv($this->filePointer, 0, static::DELIMIER);
            $row = is_array($row) ? array_map('trim', $row) : array();

            if ($row && preg_grep('/^.+$/Ss', $row)) {

                // Assemble associated list
                $list = array();
                foreach ($headers as $index => $name) {
                    if (isset($columns[$name])) {
                        $list[$name] = isset($row[$index]) ? $row[$index] : null;
                    }
                }

                if (count($list) != $cell['row_length']) {
                    $this->logImportWarning(
                        static::t(
                            'The string is different from that of the title number of columns - X instead of Y',
                            array('right' => $cell['row_length'], 'wrong' => count($list))
                        ),
                        $cell['position']
                    );
                }

                // Detect and get product
                $product = $this->getProduct($list);

                if (!$product->getId()) {
                    foreach ($columns as $name => $info) {
                        if (!isset($list[$name]) || !$list[$name]) {
                            $this->logImportWarning(
                                static::t(
                                    'Required field X is not defined or empty',
                                    array('name' => $name)
                                ),
                                $cell['position'],
                                $name
                            );
                        }
                    }
                }

                $this->importRow($product, $list, $columns);

                if ($product->getId()) {
                    $cell['old']++;

                } else {
                    $cell['new']++;
                }

                \XLite\Core\Database::getEM()->flush();
            }
            
            $cell['position']++;
        }

        if (feof($this->filePointer)) {
            $this->clearImportCell();
            \XLite\Core\Event::importFinish();
            $label = null;
            if ($cell['new'] && $cell['old']) {
                $label = 'Successfully imported X new products and upgraded Y old products';

            } elseif ($cell['new']) {
                $label = 'Successfully imported X new products';

            } elseif ($cell['old']) {
                $label = 'Successfully upgraded Y old products';

            }

            if ($label) {
                \XLite\Core\TopMessage::getInstance()->add(
                    $label,
                    array(
                        'new' => $cell['new'],
                        'old' => $cell['old'],
                    ),
                    null,
                    \XLite\Core\TopMessage::INFO,
                    false,
                    false
                );
            }

        } else {
            \XLite\Core\Session::getInstance()->importCell = $cell;
            $position = ftell($this->filePointer);
            fseek($this->filePointer, 0, SEEK_END);
            \XLite\Core\Event::importAfterStep(array('position' => $position, 'length' => ftell($this->filePointer)));
        }

        fclose($this->filePointer);
    }

    /**
     * Import one row 
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $list    Row data
     * @param array                $columns Columns info
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importRow(\XLite\Model\Product $product, array $list, array $columns)
    {
        // Import row data
        foreach ($list as $name => $data) {
            $info = $columns[$name];

            $method = 'import' . $info['method'];

            if ('calculated' == $info['type']) {

                if (isset($info['typeMethod'])) {
                    $data = $this->{'decode' . $info['typeMethod']}($data, $info);
                }

                $this->$method($product, $data, $name);

            } elseif ('product' == $info['type']) {
                if (method_exists($this, $method)) {
                    $this->$method($product, $data, $name);

                } elseif (isset($info['typeMethod']) && $info['typeMethod']) {
                    $data = $this->{'decode' . $info['typeMethod']}($data, $info);
                    $product->{'set' . $info['method']}($data);
                }
            }
        }
    }

    /**
     * Clear import cell 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function clearImportCell()
    {
        $cell = \XLite\Core\Session::getInstance()->importCell;
        if (is_array($cell) && isset($cell['path']) && $cell['path'] && file_exists($cell['path'])) {
            @unlink($cell['path']);
        }

        \XLite\Core\Session::getInstance()->importCell = null;
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
            if ($list['productId']) {
                $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(intval($list['productId']));
            }
            unset($list['productId']);
        }

        if (!$product && isset($list['sku']) && $list['sku']) {
            $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => $list['sku']));
        }

        if (!$product) {

            // Create product
            $product = new \XLite\Model\Product;
            \XLite\Core\Database::getEM()->persist($product);

            // Initialize required fields
            $product->setSku('');
            $product->setName('');
        }

        return $product;
    }

    /**
     * Log import notice 
     * 
     * @param string  $message  Message
     * @param integer $position Row position
     * @param string  $column   Column name
     * @param string  $cell     CEll value
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function logImportWarning($message, $position = null, $column = null, $cell = null)
    {
        $message = trim($message);

        if (isset($position)) {
            $message .= PHP_EOL . 'Row number: ' . $position;
        }

        if (isset($column)) {
            $message .= PHP_EOL . 'Column: ' . $column;
        }

        if (isset($cell)) {
            $message .= PHP_EOL . 'Cell value: ' . var_export($cell, true);
        }

        \XLite\Logger::getInstance()->log($message . PHP_EOL, LOG_WARNING);
    }

    // {{{ Import product fields

    /**
     * Import string-type field
     *
     * @param string $data Data
     * @param array  $info Column info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function decodeByString($data, array $info)
    {
        $data = strval($data);

        if (function_exists('mb_substr')) {
            $data = mb_substr($data, 0, $info['length']);

        } else {
            $data = preg_replace('/^(.{' . $info['length'] . '})/Ssu', '$1', $data);
        }

        return $data;
    }

    /**
     * Import float-type field
     *
     * @param string $data Data
     * @param array  $info Column info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function decodeByFloat($data, array $info)
    {
        return doubleval($data);
    }

    /**
     * Import integer-type field
     *
     * @param string $data Data
     * @param array  $info Column info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function decodeByInteger($data, array $info)
    {
        return intval($data);
    }

    /**
     * Import boolean-type field
     *
     * @param string $data Data
     * @param array  $info Column info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function decodeByBoolean($data, array $info)
    {
        $data = strtolower(strval($data));
        if (0 < strlen($data)) {
            $data = in_array($data, array('1', 'y', 'true'));

        } else {
            $data = false;
        }

        return $data;
    }

    /**
     * Import date-type field
     *
     * @param string $data Data
     * @param array  $info Column info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function decodeByDate($data, array $info)
    {
        $time = strtotime(strval($data));
        return false !== $time ? $time : time();
    }

    // }}}

    // {{{ Import complex fields

    /**
     * Import categories 
     * 
     * @param \XLite\Model\Product $product Product
     * @param string               $data    Data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importCategories(\XLite\Model\Product $product, $data)
    {
        $oldLinks = array();
        foreach ($product->getCategoryProducts() as $link) {
            $oldLinks[] = $link->getId();
        }

        if ($data) {
            $root = \XLite\Core\Database::getRepo('XLite\Model\Category')->find(
                \XLite\Core\Database::getRepo('XLite\Model\Category')->getRootCategoryId()
            );

            foreach (explode(';', $data) as $path) {
                $path = trim($path);

                // Detect category
                $parent = $root;
                foreach (explode('/', $path) as $name) {
                    $name = trim($name);
                    $category = null;
                    foreach ($parent->getChildren() as $cat) {
                        if ($cat->getName() == $name) {
                            $category = $cat;
                            break;
                        }
                    }

                    if (!$category) {
                        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->insert(
                            array('parent_id' => $parent->getCategoryId(), 'name' => $name)
                        );
                    }

                    $parent = $category;
                }

                if ($category) {

                    // Add link to category
                    $link = null;
                    foreach ($product->getCategoryProducts() as $cp) {
                        if ($cp->getCategory()->getCategoryId() == $category->getCategoryId()) {
                            $link = $cp;
                            $key = array_search($link->getId(), $oldLinks);
                            unset($oldLinks[$key]);
                            break;
                        }
                    }

                    if (!$link) {
                        $link = new \XLite\Model\CategoryProducts;
                        $link->setProduct($product);
                        $link->setCategory($category);
                        $product->addCategoryProducts($link);
                        \XLite\Core\Database::getEM()->persist($link);
                    }
                }    
            }
        }

        foreach ($product->getCategoryProducts() as $link) {
            if (in_array($link->getId(), $oldLinks)) {
                $product->getCategoryProducts()->removeElement($link);
                \XLite\Core\Database::getEM()->remove($link);
            }
        }
    }

    /**
     * Import images
     *
     * @param \XLite\Model\Product $product Product
     * @param string               $data    Data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importImages(\XLite\Model\Product $product, $data)
    {
        // Save old images ids' list
        $oldImageIds = array();
        foreach ($product->getImages() as $image) {
            $oldImageIds[] = $image->getImageId();
        }

        if ($data) {

            // Load images
            foreach (explode(';', $data) as $url) {
                $url = trim($url);

                $hash = \Includes\Utils\FileManager::getHash($url);

                $image = null;

                if ($hash) {
                    foreach ($product->getImages() as $i) {
                        if ($i->getHash() == $hash) {
                            $image = $i;
                            $key = array_search($i->getImageId(), $oldImageIds);
                            unset($oldImageIds[$key]);
                            break;
                        }
                    }
                }

                if (!$image) {
                    $image = new \XLite\Model\Image\Product\Image();
                    $image->setProduct($product);

                    if ($image->loadFromURL($url)) {
                        $product->addImages($image);
                        \XLite\Core\Database::getEM()->persist($image);

                    } else {
                        $this->logImportWarning(
                            static::t(
                                'X image unable to load',
                                array('url' => $url)
                            )
                        );
                    }
                }
            }
        }

        // Remove old images
        foreach ($product->getImages() as $image) {
            if (in_array($image->getImageId(), $oldImageIds)) {
                $product->getImages()->removeElement($image);
                \XLite\Core\Database::getEM()->remove($image);
            }
        }
    }

    /**
     * Import inventory data
     *
     * @param \XLite\Model\Product $product Product
     * @param string               $data    Data
     * @param string               $name    Cell name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importInventory(\XLite\Model\Product $product, $data, $name)
    {
        if ('inventoryEnabled' == $name) {
            $name = 'enabled';
        }

        $method = 'set' . ucfirst($name);
        $product->getInventory()->$method($data);
        if (!$product->getInventory()->getInventoryId()) {
            $product->setInventory($product->getInventory());
            $product->getInventory()->setProduct($product);
        }
    }

    /**
     * Import classes
     *
     * @param \XLite\Model\Product $product Product
     * @param string               $data    Data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importClasses(\XLite\Model\Product $product, $data)
    {
        // Remove old classes
        foreach ($product->getClasses() as $class) {
            $class->getProducts()->removeElement($product);
        }
        $product->getClasses()->clear();

        if ($data) {

            // Add classes links
            foreach (explode(';', $data) as $name) {
                $name = trim($name);

                $translation = \XLite\Core\Database::getRepo('XLite\Model\ProductClassTranslation')->findOneBy(array('name' => $name));
                if ($translation) {
                    $class = $translation->getOwner();

                } else {
                    $class = new \XLite\Model\ProductClass;
                    $class->setName($name);
                
                    \XLite\Core\Database::getEM()->persist($class);
                }

                $class->addProducts($product);
                $product->addClasses($class);
            }
        }
    }

    // }}}

    // }}}
}
