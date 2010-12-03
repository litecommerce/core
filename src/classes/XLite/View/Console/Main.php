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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Console;

/**
 * Console base widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="cli.center", zone="console")
 */
class Main extends \XLite\View\Console\AConsole
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'base.tpl';
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'main';

        return $result;
    }

    /**
     * Get allowed commands 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllowedCommands()
    {
        $dsQuoted = preg_quote(LC_DS, '/');
        $path = LC_CLASSES_CACHE_DIR . 'XLite' . LC_DS . 'Controller' . LC_DS . 'Console' . LC_DS . '*.php';
        $commands = array();
        foreach (glob($path) as $f) {
            if (!preg_match('/Abstract.php$/Ss', $f) && !preg_match('/' . $dsQuoted . 'A[A-Z]/Ss', $f)) {
                $commands[] = strtolower(substr(basename($f), 0, -4));
            }
        }

        return $commands;
    }
}
