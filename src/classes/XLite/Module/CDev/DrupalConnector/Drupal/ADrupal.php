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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * ADrupal 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ADrupal extends \XLite\Base\Singleton
{
    /**
     * Initialized handler instance
     * 
     * @var    \XLite\Module\CDev\DrupalConnector\Handler
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $handler;

    /**
     * Return instance of current CMS connector
     * 
     * @return \XLite\Module\CDev\DrupalConnector\Handler
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandler()
    {
        if (!isset($this->handler)) {
            $this->handler = \XLite\Module\CDev\DrupalConnector\Handler::getInstance();
            $this->handler->init();
        }

        return $this->handler;
    }

    /**
     * Execute a controller action
     *
     * @param string $target Controller target
     * @param string $action Action to perform
     * @param array  $data   Request data
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function runController($target, $action = null, array $data = array())
    {
        $data = array('target' => $target, 'action' => $action) + $data;

        $this->getHandler()->mapRequest(array(\XLite\Core\CMSConnector::NO_REDIRECT => true) + $data);
        $this->getHandler()->runController(md5(serialize($data)));
    }
}
