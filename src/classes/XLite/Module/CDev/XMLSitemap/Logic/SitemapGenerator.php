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

namespace XLite\Module\CDev\XMLSitemap\Logic;

/**
 * Sitemap generator 
 * 
 */
class SitemapGenerator extends \XLite\Base\Singleton
{
    /**
     * File index 
     * 
     * @var integer
     */
    protected $fileIndex;

    /**
     * Empty file flag
     * 
     * @var boolean
     */
    protected $emptyFile = false;

    /**
     * Get sitemap index 
     * 
     * @return string
     */
    public function getIndex()
    {
        $string = '<' . '?xml version="1.0" encoding="UTF-8" ?' . '>' . PHP_EOL
            . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*.xml') as $path) {
            $name = basename($path);
            $loc = \XLite::getInstance()->getShopURL(
                \XLite\Core\Converter::buildURL('sitemap', '', array('index' => substr($name, 11, -4)), 'cart.php')
            );
            $time = filemtime($path);
            $string .= '<sitemap>'
                . '<loc>' . htmlentities($loc, ENT_COMPAT, 'UTF-8') . '</loc>'
                . '<lastmod>' . date('Y-m-d', $time) . 'T' . date('H:m:s', $time) . 'Z</lastmod>'
                . '</sitemap>';
            \Includes\Utils\FileManager::deleteFile($path);
        }

        return $string . '</sitemapindex>';
    }

    /**
     * Get sitemap by index
     * 
     * @param integer $index Index
     *  
     * @return string
     */
    public function getSitemap($index)
    {
        $path = LC_DIR_DATA . 'xmlsitemap.' . $index . '.xml';

        return \Includes\Utils\FileManager::isExists($path) ? file_get_contents($path) : null;
    }

    /**
     * Check - sitemap empty or not
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 == $this->getIterator()->count();
    }

    /**
     * Check - sitemaps files generated or not
     * 
     * @return boolean
     */
    public function isGenerated()
    {
        return 0 < count(glob(LC_DIR_DATA . 'xmlsitemap.*.xml'));
    }

    // {{{ Generate sitemaps

    /**
     * Generate index file and sitemap files
     * 
     * @return void
     */
    public function generate()
    {
        $this->clear();
        $this->generateSitemaps();
    }

    /**
     * Clear files directory
     * 
     * @return void
     */
    public function clear()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*.xml') as $path) {
            \Includes\Utils\FileManager::deleteFile($path);
        }
    }

    /**
     * Generate sitemap files
     * 
     * @return void
     */
    protected function generateSitemaps()
    {
        $this->initializeWrite();

        foreach ($this->getIterator() as $record) {
            $this->writeRecord($this->assembleRecord($record));
        }

        $this->finalizeWrite();
    }

    /**
     * Get head 
     * 
     * @return string
     */
    protected function getHead()
    {
        return '<' . '?xml version="1.0" encoding="UTF-8" ?' . '>' . PHP_EOL
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    }

    /**
     * Get footer 
     * 
     * @return string
     */
    protected function getFooter()
    {
        return '</urlset>';
    }

    /**
     * Assemble record 
     * 
     * @param array $record Record
     *  
     * @return string
     */
    protected function assembleRecord(array $record)
    {
        $record['loc'] = \XLite::getInstance()->getShopURL($this->buildLoc($record['loc']));
        $time = $record['lastmod'];
        $record['lastmod'] = date('Y-m-d', $time) . 'T' . date('H:m:s', $time) . 'Z';

        $string = '<url>';
        foreach ($record as $name => $value) {
            $string .= '<' . $name . '>' . htmlentities($value) . '</' . $name . '>';
        }

        return $string . '</url>';
    }

    /**
     * Build location URL
     * 
     * @param array $loc Locationb as array
     *  
     * @return string
     */
    protected function buildLoc(array $loc)
    {
        $target = $loc['target'];
        unset($loc['target']);

        return \XLite\Core\Converter::buildURL($target, '', $loc, 'cart.php');
    }

    /**
     * Get iterator 
     * 
     * @return \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator
     */
    protected function getIterator()
    {
        return new \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator;
    }

    /**
     * Initialize write 
     * 
     * @return void
     */
    protected function initializeWrite()
    {
        if (!\Includes\Utils\FileManager::isExists(LC_DIR_DATA)) {
            \Includes\Utils\FileManager::mkdir(LC_DIR_DATA);
            if (!\Includes\Utils\FileManager::isExists(LC_DIR_DATA)) {
                \XLite\Logger::getInstance()->log(
                    'The directory ' . LC_DIR_DATA . ' can not be created.'
                    . ' Check the permissions to create directories.',
                    LOG_ERR
                );
            }
        }
        $this->fileIndex = null;
        $this->emptyFile = true;
    }

    /**
     * Finalize write 
     * 
     * @return void
     */
    protected function finalizeWrite()
    {
        if ($this->emptyFile) {
            if ($this->fileIndex) {
                \Includes\Utils\FileManager::deleteFile($this->getSitemapPath());
            }

        } else {
            \Includes\Utils\FileManager::write($this->getSitemapPath(), $this->getFooter(), FILE_APPEND);
        }
    }

    /**
     * Write record 
     * 
     * @param string $string String
     *  
     * @return void
     */
    protected function writeRecord($string)
    {
        if (!isset($this->fileIndex)) {
            $this->fileIndex = 1;
            \Includes\Utils\FileManager::write($this->getSitemapPath(), $this->getHead());
        }

        \Includes\Utils\FileManager::write($this->getSitemapPath(), $string, FILE_APPEND);
        $this->emptyFile = false;

        if ($this->needSwitch()) {
            \Includes\Utils\FileManager::write($this->getSitemapPath(), $this->getFooter(), FILE_APPEND);
            $this->fileIndex++;
            \Includes\Utils\FileManager::write($this->getSitemapPath(), $this->getHead(), FILE_APPEND);
            $this->emptyFile = true;
        }
    }

    /**
     * Get sitemap path
     * 
     * @return string
     */
    protected function getSitemapPath()
    {
        return LC_DIR_DATA . 'xmlsitemap.' . $this->fileIndex . '.xml';
    }

    /**
     * Check - need switch to next file or not
     * 
     * @return boolean
     */
    protected function needSwitch()
    {
        return 9000000 < filesize($this->getSitemapPath());
    }

    // }}}
}

