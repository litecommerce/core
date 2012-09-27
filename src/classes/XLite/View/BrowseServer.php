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

namespace XLite\View;

/**
 * File Selector Dialog widget
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class BrowseServer extends \XLite\View\SimpleDialog
{
    /**
     * File entries cache
     *
     * @var array
     *
     */
    protected $fsEntries = array('catalog' => array(), 'file' => array());

    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'browse_server';

        return $list;
    }

    /**
     * Return files catalog repository. {lc_catalog}/files
     *
     * @return string
     */
    public static function getFilesCatalog()
    {
        return LC_DIR_ROOT . 'files';
    }

    /**
     * Check path to be inside the files catalog repository. {lc_catalog}/files
     * Return full path that inside the repository.
     * If path is out the one then returns the catalog repository path.
     *
     * @return string
     */
    public static function getNormalizedPath($path)
    {
        $filesCatalog = \XLite\View\BrowseServer::getFilesCatalog();

        $path = \Includes\Utils\FileManager::getRealPath(
            $filesCatalog . LC_DS . $path
        );

        return ($filesCatalog !== substr($path, 0, strlen($filesCatalog)))
            ? $filesCatalog
            : $path;
    }

    /**
     * Return title. "Browse server"
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Browse server';
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        return 'browse_server/body.tpl';
    }

    /**
     * Return current catalog
     *
     * @return string
     */
    protected function getCurrentCatalog()
    {
        return \XLite\View\BrowseServer::getNormalizedPath(\XLite\Core\Request::getInstance()->catalog);
    }

    /**
     * Return catalog info for AJAX JS structure
     * current_catalog - current catalog
     * up_catalog      - catalog path to UP level link
     *
     * @return array
     */
    protected function getCatalogInfo()
    {
        $currentCatalog = $this->getCurrentCatalog();
        $filesCatalog = $this->getFilesCatalog();

        return array(
            'current_catalog'   => str_replace($filesCatalog, '', $currentCatalog),
            'up_catalog'        => str_replace(
                $filesCatalog,
                '',
                $currentCatalog === $filesCatalog ? $currentCatalog : dirname($currentCatalog)
            ),
        );
    }

    /**
     * Return files entries structure
     * type      - 'catalog' or 'file' value
     * extension - extension of file entry. CSS class will be added according this parameter
     * name      - name of entry (catalog/file) inside the current catalog.
     *
     * Catalog entries go first in the entries list
     *
     * @return array
     */
    protected function getFSEntries()
    {
        $iterator = new \FilesystemIterator($this->getCurrentCatalog());

        foreach ($iterator as $file) {
            $path = $file->getPathname();

            $type = $file->isDir() ? 'catalog' : 'file';

            $this->fsEntries[$type][$path] = array(
                'type'      => $type,
                'extension' => pathinfo($path, PATHINFO_EXTENSION),
                'name'      => $file->getBasename(),
                'fullName'  => $file->getBasename(),
            );
        }

        return $this->fsEntries['catalog'] + $this->fsEntries['file'];
    }

    /**
     * Return true if there is no files or catalogs inside the current one
     *
     * @return boolean
     */
    protected function isEmptyCatalog()
    {
        return count($this->fsEntries['catalog'] + $this->fsEntries['file']) == 0;
    }

    /**
     * Get file entry class 
     * 
     * @param array $entry Entry
     *  
     * @return string
     */
    protected function getItemClass(array $entry)
    {
        return 'type-' . $entry['type'] . ' extension-unknown'
            . ($entry['extension'] ? ' extension-' . $entry['extension'] : '');
    }
}
