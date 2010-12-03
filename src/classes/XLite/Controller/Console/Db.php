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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Console;

/**
 * Db services controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Db extends \XLite\Controller\Console\AConsole
{
    /**
     * Loaded lines 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $loadedLines = 0;

    /**
     * Load fixtures from YAML file
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionLoadFixtures()
    {
        $path = \XLite\Core\Request::getInstance()->path;
        if (!$path || !file_exists($path) || !is_readable($path)) {
            $this->operationError = 'Path is invalid';

        } else {
            try {
                $this->loadedLines = \XLite\Core\Database::getInstance()->loadFixturesFromYaml($path);

            } catch (\PDOException $e) {
                $this->operationError = $e->getMessage();
            }
        }
    }

    /**
     * Get help for loadFixtures action
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHelpLoadFixtures()
    {
        return 'Specified --path options with path to YAML file';
    }

    /**
     * Get loaded lines 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLoadedLines()
    {
        return $this->loadedLines;
    }
}
