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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\ViewLists;

/**
 * Decorator plugin to generate widget lists
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * getMappedPHPClasses 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMappedPHPClasses()
    {
        return array_filter(
            \Includes\Decorator::getInstance()->getClassesTree()->getIndex(),
            function (\Includes\Decorator\DataStructure\ClassData\Node $node) {
                return !is_null($node->getTag('ListChild'));
            }
        );
    }

    /**
     * getRepo 
     * 
     * @return \XLite\Model\Repo\ViewList
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ViewList');
    }

    /**
     * clearAll 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearAll()
    {
        $this->getRepo()->deleteInBatch($this->getRepo()->findAll());
    }

    /**
     * handlePHPClasses 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function handlePHPClasses()
    {
        /* foreach ($this->getMappedPHPClasses() as $node) {

        } */ 
    }


    /**
     * Generate widget lists
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerRun()
    {
        // Truncate old
        $this->clearAll();

        // Create new
        $this->handlePHPClasses();
    }
}
