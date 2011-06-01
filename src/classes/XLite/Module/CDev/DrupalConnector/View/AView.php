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

namespace XLite\Module\CDev\DrupalConnector\View;

/**
 * Abstract widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AView extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    /**
     * Relative path from web directory path to the XLite web directory
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $drupalRelativePath = null;


    /**
     * prepareBasePath
     *
     * @param string $path Path to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function prepareBasePath($path)
    {
        $path = trim($path, '/');

        return ('' === $path) ? array() : explode('/', $path);
    }

    /**
     * Return relative path from web directory path to the XLite web directory
     * FIXME - it's the hack
     * TODO - check if there is a more convenient way to implement this
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDrupalRelativePath()
    {
        if (!isset(self::$drupalRelativePath)) {

            $basePath  = self::prepareBasePath(base_path());
            $xlitePath = self::prepareBasePath(\XLite::getInstance()->getOptions(array('host_details', 'web_dir')));

            $basePathSize = count($basePath);
            $minPathSize  = min($basePathSize, count($xlitePath));

            for ($i = 0; $i < $minPathSize; $i++) {
                if ($basePath[$i] !== $xlitePath[$i]) {
                    break;
                } else {
                    unset($xlitePath[$i]);
                }
            }

            self::$drupalRelativePath = str_repeat('..' . LC_DS, $basePathSize - $i) . join(LC_DS, $xlitePath) . LC_DS;
        }

        return self::$drupalRelativePath;
    }

    /**
     * Add the relative part to the resources' URLs
     *
     * @param array $data Data to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function modifyResourcePaths(array $data)
    {
        foreach ($data as &$file) {
            $file['file'] = str_replace(LC_DS, '/', static::getDrupalRelativePath() . str_replace(LC_DIR_ROOT, '', $file['file']));
        }

        return $data;
    }

    /**
     * Prepare resources list
     *
     * @param array   $data     Data to prepare
     * @param boolean $isCommon Flag to determine how to prepare URL OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function prepareResources(array $data, $isCommon = false)
    {
        return static::modifyResources(parent::prepareResources($data, $isCommon));
    }

    /**
     * Modify resources list
     *
     * @param mixed $data Data to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function modifyResources(array $data)
    {
        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $data = static::modifyResourcePaths($data);
        }

        return $data;
    }


    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $result = parent::getJSFiles();

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $result[] = 'modules/CDev/DrupalConnector/drupal.js';
        }

        return $result;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {

            $filter = array('css/style.css', 'css/print.css');

            foreach ($list as $k => $v) {
                $fn = is_string($v) ? $v : $v['file'];
                if (in_array($fn, $filter)) {
                    unset($list[$k]);
                }
            }
        }

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {

            // TODO: check if it's really needed
            if (false !== ($key = array_search('js/jquery.min.js', $list['js']))) {
                unset($list['js'][$key]);
            }
        }

        return $list;
    }
}
