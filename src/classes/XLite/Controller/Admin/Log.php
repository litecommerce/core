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

namespace XLite\Controller\Admin;

/**
 * Log getter controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Log extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        return parent::checkAccess() && $this->getLogPath();
    }

    /**
     * Get log path 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getLogPath()
    {
        $path = \XLite\Core\Request::getInstance()->log;
        if ($path && !preg_match(\XLite\Logger::LOG_FILE_NAME_PATTERN, $path)) {
            $path = null;
        }
        $path = $path ? (LC_DIR_VAR . 'log' . LC_DS . $path) : null;

        return (!$path || !file_exists($path) || !is_readable($path)) ? null : $path;
    }

    /**
     * Preprocessor for no-action ren
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doNoAction()
    {
        $this->silent = true;

        $path = $this->getLogPath();

        header('Content-Length: ' . filesize($path));
        header('Content-Type: text/plain');
        header(
            'Content-Disposition: attachment;'
            . ' filename="' . substr(basename($path), 0, -4) . '.txt";'
            . ' modification-date="' . date('r', filemtime($path)) . ';'
        );

        readfile($path);
    }

}
