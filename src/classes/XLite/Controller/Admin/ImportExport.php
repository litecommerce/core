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
    const IMPORT_TTL = 8;

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

    /**
     * Export id 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $exportId;

    /**
     * Import cell 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.11
     */
    protected $importCell;

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
                    'required'   => isset($col['required']) && $col['required'],
                );
            }

            foreach ($this->defineCalculatedColumns() as $name => $col) {
                $this->columns[$name] = array(
                    'type'       => 'calculated',
                    'method'     => ucfirst($col['method']),
                    'typeMethod' => isset($col['type']) ? ('By' . ucfirst($col['type'])) : null,
                    'length'     => isset($col['length']) ? $col['length'] : null,
                    'required'   => isset($col['required']) && $col['required'],
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
            'description'      => array('type' => static::TYPE_STRING, 'length' => 65535),
            'briefDescription' => array('type' => static::TYPE_STRING, 'length' => 65535),
            'metaTags'         => array('type' => static::TYPE_STRING, 'length' => 255),
            'metaDesc'         => array('type' => static::TYPE_STRING, 'length' => 65535),
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

        $this->exportId = hash('md4', time());

        $path = LC_DIR_TMP . $this->exportId . LC_DS  . 'images';
        \Includes\Utils\FileManager::mkdirRecursive($path);

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

        if (0 < count($product->getImages())) {

            $path = LC_DIR_TMP . $this->exportId . LC_DS  . 'images' . LC_DS . 'product' . LC_DS . $product->getProductId();
            \Includes\Utils\FileManager::mkdirRecursive($path);
            $path .= LC_DS;

            foreach ($product->getImages() as $image) {
                if ($image->isURL()) {
                    $list[] = $image->getFrontURL();

                } else {
                    $name = $image->getPath() ?: ($image->getId() . '.' . $image->getExtension());
                    $subpath = $path . $name;
                    if (@file_put_contents($subpath, $image->getBody())) {
                        $list[] = $subpath;
                    }
                }
            }
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
            $this->importCell = $this->getImportCell();

            if ($this->importCell) {

                try {
                    $this->processImportStep();

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
            if (!isset($cell['warning_count'])) {
                $cell['warning_count'] = 0;
            }
        }

        return $cell;
    }

    /**
     * Process import step 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function processImportStep()
    {
        $this->startImportStep();

        $expire = time() + static::IMPORT_TTL;
        while (time() < $expire && !feof($this->filePointer)) {

            $row = fgetcsv($this->filePointer, 0, static::DELIMIER);
            $row = is_array($row) ? array_map('trim', $row) : array();

            if ($row && preg_grep('/^.+$/Ss', $row)) {

                // Assemble associated list
                $list = $this->assembleImportRow($row);

                // Detect and get product
                $product = $this->getProduct($list);

                if (!$product->getId()) {
                    if ($this->checkRequiredImportFields($list)) {
                        \XLite\Core\Database::getEM()->persist($product);

                    } else {
                       $product = null; 
                    }
                }

                if ($product) {
                    if (count($list) != $this->importCell['row_length']) {
                        $this->logImportWarning(
                            static::t(
                                'The string is different from that of the title number of columns - X instead of Y',
                                array('right' => $this->importCell['row_length'], 'wrong' => count($list))
                            ),
                            $this->importCell['position'],
                            null,
                            null,
                            $product
                        );
                    }

                    $this->importRow($product, $list);

                    if (!$product->getId()) {
                        $this->importCell['new']++;
                    }

                    \XLite\Core\Database::getEM()->flush();
                }
            }
            
            $this->importCell['position']++;
        }

        if (feof($this->filePointer)) {
            \XLite\Core\Event::importFinish();

            $this->importCell['old'] = \XLite\Core\Database::getRepo('XLite\Model\Product')
                ->countLastUpdated($this->importCell['start']);
            $this->importCell['old'] -= $this->importCell['new'];
            $this->importCell['old'] = max(0, $this->importCell['old']);

            $label = null;
            if ($this->importCell['new'] && $this->importCell['old']) {
                $label = 'Occurred X add product events and Y update product events';

            } elseif ($this->importCell['new']) {
                $label = 'Occurred X add product events';

            } elseif ($this->importCell['old']) {
                $label = 'Occurred Y update product events';

            }

            if ($label) {
                \XLite\Core\TopMessage::getInstance()->add(
                    $label,
                    array(
                        'new' => $this->importCell['new'],
                        'old' => $this->importCell['old'],
                    ),
                    null,
                    \XLite\Core\TopMessage::INFO,
                    false,
                    false
                );
            }

            if (0 < $this->importCell['warning_count']) {
                \XLite\Core\TopMessage::getInstance()->add(
                    'During the import was recorded X errors. You can get them by downloading the log imports.',
                    array(
                        'count' => $this->importCell['warning_count'],
                        'url'   => \XLite\Logger::getInstance()->getCustomLogURL('import'),
                    ),
                    null,
                    \XLite\Core\TopMessage::WARNING,
                    false,
                    false
                );
                \XLite\Core\TopMessage::getInstance()->add(
                    'Some products could have been imported incorrectly',
                    array(),
                    null,
                    \XLite\Core\TopMessage::WARNING,
                    false,
                    false
                );
            }

            $this->clearImportCell();

        } else {
            \XLite\Core\Session::getInstance()->importCell = $this->importCell;
            $position = ftell($this->filePointer);
            fseek($this->filePointer, 0, SEEK_END);
            \XLite\Core\Event::importAfterStep(array('position' => $position, 'length' => ftell($this->filePointer)));
        }

        fclose($this->filePointer);
    }

    /**
     * Start import step 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function startImportStep()
    {
        $this->filePointer = fopen($this->importCell['path'], 'rb');

        if ($this->importCell['position']) {
            $counter = $this->importCell['position'] + 1;
            while (0 < $counter) {
                fgetcsv($this->filePointer, 0, static::DELIMIER);
                $counter--;
            }

        } else {
            $this->importCell['headers'] = fgetcsv($this->filePointer, 0, static::DELIMIER);
            $this->importCell['row_length'] = count($this->importCell['headers']);
            $this->importCell['start'] = time();
            $columns = $this->getColumns();
            foreach ($this->importCell['headers'] as $index => $name) {
                if (!isset($columns[$name])) {
                    $this->logImportWarning(
                        static::t(
                            'Import mechanism does not know the field of X and it can not be imported',
                            array('name' => $name)
                        ),
                        -1
                    );
                    $this->importCell['row_length']--;
                }
            }
        }
    }

    /**
     * Assemble import row 
     * 
     * @param array $row Row
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function assembleImportRow(array $row)
    {
        $columns = $this->getColumns();

        $list = array();
        foreach ($this->importCell['headers'] as $index => $name) {
            if (isset($columns[$name])) {
                $list[$name] = isset($row[$index]) ? $row[$index] : null;
            }
        }

        return $list;
    }

    /**
     * Check import required fields 
     * 
     * @param array $list Row
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function checkRequiredImportFields(array $list)
    {
        $valid = true;

        foreach ($this->getColumns() as $name => $info) {
            if ($info['required'] && (!isset($list[$name]) || !$list[$name])) {
                $valid = false;
                $this->logImportWarning(
                    static::t(
                        'Required field X is not defined or empty',
                        array('name' => $name)
                    ),
                    $this->importCell['position'],
                    $name
                );
            }
        }

        return $valid;
    }

    /**
     * Import one row 
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $list    Row data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function importRow(\XLite\Model\Product $product, array $list)
    {
        $columns = $this->getColumns();

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

        $product->setUpdateDate(time());
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
        if (
            is_array($this->importCell)
            && isset($this->importCell['path'])
            && $this->importCell['path']
            && file_exists($this->importCell['path'])
        ) {
            @unlink($this->importCell['path']);
        }

        \XLite\Core\Session::getInstance()->importCell = null;
        $this->importCell = null;
    }

    /**
     * Get product (old or new)
     * 
     * @param array $list Row data
     *  
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getProduct(array $list)
    {
        $product = null;

        if (isset($list['productId'])) {
            if ($list['productId']) {
                $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(intval($list['productId']));
            }
        }

        if (!$product && isset($list['sku']) && $list['sku']) {
            $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => $list['sku']));
        }

        if (!$product) {

            // Create product
            $product = new \XLite\Model\Product;
        }

        return $product;
    }

    /**
     * Log import notice 
     * 
     * @param string               $message  Message
     * @param integer              $position Row position OPTIONAL
     * @param string               $column   Column name OPTIONAL
     * @param string               $value    Cell value OPTIONAL
     * @param \XLite\Model\Product $product  Product OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function logImportWarning(
        $message,
        $position = null,
        $column = null,
        $value = null,
        \XLite\Model\Product $product = null
    ) {
        $message = trim($message);

        $this->importCell['warning_count']++;

        if (isset($position)) {
            $message .= PHP_EOL . 'Row number: ' . ($position + 2);
        }

        if (isset($column)) {
            $message .= PHP_EOL . 'Column: ' . $column;
        }

        if (isset($value)) {
            $message .= PHP_EOL . 'Cell value: ' . var_export($value, true);
        }
        if (isset($product)) {
            if ($product->getProductId()) {
                $message .= PHP_EOL . 'Product id: ' . $product->getProductId();

            } elseif ($product->getSku()) {
                $message .= PHP_EOL . 'Product SKU: ' . $product->getSku();
            }
        }

        \XLite\Logger::getInstance()->logCustom('import', $message);
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

        return strval($data);
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
        return round(doubleval($data), 4);
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
                $category = null;
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
                        $parent->addChildren($category);
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
            $oldImageIds[] = $image->getId();
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
                            $key = array_search($i->getId(), $oldImageIds);
                            unset($oldImageIds[$key]);
                            break;
                        }
                    }
                }

                if (!$image) {
                    $image = new \XLite\Model\Image\Product\Image();
                    $image->setProduct($product);

                    $result = preg_match('/^(https?|ftp):\/\//Ss', $url)
                        ? $image->loadFromURL($url)
                        : $image->loadFromLocalFile($url);

                    if ($result) {
                        $product->addImages($image);
                        \XLite\Core\Database::getEM()->persist($image);

                    } else {
                        $this->logImportWarning(
                            static::t(
                                'X image unable to load',
                                array('url' => $url)
                            ),
                            $this->importCell['position'],
                            'images',
                            $url,
                            $product
                        );
                    }
                }
            }
        }

        // Remove old images
        foreach ($product->getImages() as $image) {
            if (in_array($image->getId(), $oldImageIds)) {
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
