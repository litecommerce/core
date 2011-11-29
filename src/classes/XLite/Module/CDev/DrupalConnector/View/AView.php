<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
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

    // {{{ Static methods

    /**
     * Modify resource path 
     * 
     * @param string $path Absolute resource path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function modifyResourcePath($path)
    {
        return str_replace(LC_DS, '/', static::getDrupalRelativePath() . str_replace(LC_DIR_ROOT, '', $path));
    }

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
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDrupalRelativePath()
    {
        if (!isset(static::$drupalRelativePath)) {
            $basePath  = static::prepareBasePath(base_path());
            $xlitePath = static::prepareBasePath(\XLite::getInstance()->getOptions(array('host_details', 'web_dir')));

            $basePathSize = count($basePath);
            $minPathSize  = min($basePathSize, count($xlitePath));

            for ($i = 0; $i < $minPathSize; $i++) {
                if ($basePath[$i] === $xlitePath[$i]) {
                    unset($xlitePath[$i]);

                } else {
                    break;
                }
            }

            static::$drupalRelativePath = str_repeat('..' . LC_DS, $basePathSize - $i) . implode(LC_DS, $xlitePath) . LC_DS;
        }

        return static::$drupalRelativePath;
    }

    // }}}

    // {{{ Resource routines

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
     * Common method to register resources
     *
     * @param array  $data      Resource description
     * @param string $interface Interface OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function prepareResource(array $data, $interface = null)
    {
        $data = parent::prepareResource($data, $interface);

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $data['file'] = static::modifyResourcePath($data['file']);
        }

        return $data;
    }

    // }}}
}
