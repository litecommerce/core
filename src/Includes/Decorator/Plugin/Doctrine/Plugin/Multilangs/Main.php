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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\Multilangs;

/**
 * Routines for Doctrine library
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Return list of classes with multilanguage support
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMultilangModelClasses()
    {
        return static::getClassesTree()->findByCallback(array($this, 'filterByMultilangParent'));
    }


    /**
     * Method to filter multilang classes
     *
     * @param \Includes\Decorator\Data\Classes\Node $node current node
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function filterByMultilangParent(\Includes\Decorator\Data\Classes\Node $node)
    {
        return '\XLite\Model\Base\I18n' === $node->__get(self::N_PARENT_CLASS);
    }

    /**
     * Execute "preprocess" hook handler
     * 
     * FIXME - multiple inheritance is required instead of this
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerPreprocess()
    {
        // FIXME
        \Includes\Decorator::buildMultilangs($this->getMultilangModelClasses());

        /*foreach ($this->getMultilangModelClasses() as $node) {
            // TODO
        }*/
    }
}
