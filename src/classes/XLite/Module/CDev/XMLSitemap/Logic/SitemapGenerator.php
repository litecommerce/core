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
 * @since     1.0.12
 */

namespace XLite\Module\CDev\XMLSitemap\Logic;

/**
 * Sitemap generator 
 * 
 * @see   ____class_see____
 * @since 1.0.12
 */
class SitemapGenerator extends \XLite\Base\Singleton
{
    /**
     * File index 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.12
     */
    protected $fileIndex;

    /**
     * Empty file flag
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.12
     */
    protected $emptyFile = false;

    /**
     * Get sitemap index 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.12
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
            $string .= '<sitemap>'
                . '<loc>' . $loc . '</loc>'
                . '<lastmod>' . date('Y-m-dh:m:s', filemtime($path)) . '</lastmod>'
                . '</sitemap>';
            unlink($path);
        }

        return $string . '</sitemapindex>';
    }

    /**
     * Get sitemap by index
     * 
     * @param integer $index Index
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function getSitemap($index)
    {
        $path = LC_DIR_DATA . 'xmlsitemap.' . $index . '.xml';

        return file_exists($path) ? file_get_contents($path) : null;
    }

    /**
     * Check - sitemap empty or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function isEmpty()
    {
        return 0 == $this->getIterator()->count();
    }

    /**
     * Check - sitemaps files generated or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.12
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
     * @see    ____func_see____
     * @since  1.0.12
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
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function clear()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*.xml') as $path) {
            unlink($path);
        }
    }

    /**
     * Generate sitemap files
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
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
     * @see    ____func_see____
     * @since  1.0.12
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
     * @see    ____func_see____
     * @since  1.0.12
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
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function assembleRecord(array $record)
    {
        $target = $record['loc']['target'];
        unset($record['loc']['target']);

        $record['loc'] = method_exists('XLite\Core\Converter', 'buildDrupalPath')
            ? \XLite\Core\Converter::buildDrupalURL($target, '', $record['loc'])
            : \XLite\Core\Converter::buildURL($target, '', $record['loc'], 'cart.php');
        $record['loc'] = \XLite::getInstance()->getShopURL($record['loc']);
        $record['lastmod'] = date('Y-m-dh:m:s', $record['lastmod']);

        $string = '<url>';
        foreach ($record as $name => $value) {
            $string .= '<' . $name . '>' . $value . '</' . $name . '>';
        }

        return $string . '</url>';
    }

    /**
     * Get iterator 
     * 
     * @return \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function getIterator()
    {
        return new \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator;
    }

    /**
     * Initialize write 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function initializeWrite()
    {
        $this->fileIndex = null;
        $this->emptyFile = true;
    }

    /**
     * Finalize write 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function finalizeWrite()
    {
        if ($this->emptyFile) {
            if ($this->fileIndex && file_exists($this->getSitemapPath())) {
                @unlink($this->getSitemapPath());
            }

        } else {
            file_put_contents($this->getSitemapPath(), $this->getFooter(), FILE_APPEND);
        }
    }

    /**
     * Write record 
     * 
     * @param string $string String
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function writeRecord($string)
    {
        if (!isset($this->fileIndex)) {
            $this->fileIndex = 1;
            file_put_contents($this->getSitemapPath(), $this->getHead());
        }

        file_put_contents($this->getSitemapPath(), $string, FILE_APPEND);
        $this->emptyFile = false;

        if ($this->needSwitch()) {
            file_put_contents($this->getSitemapPath(), $this->getFooter(), FILE_APPEND);
            $this->fileIndex++;
            file_put_contents($this->getSitemapPath(), $this->getHead(), FILE_APPEND);
            $this->emptyFile = true;
        }
    }

    /**
     * Get sitemap path
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function getSitemapPath()
    {
        return LC_DIR_DATA . 'xmlsitemap.' . $this->fileIndex . '.xml';
    }

    /**
     * Check - need switch to next file or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function needSwitch()
    {
        return 9000000 < filesize($this->getSitemapPath());
    }

    // }}}
}

