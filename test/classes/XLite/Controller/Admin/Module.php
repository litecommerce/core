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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Module extends XLite_Controller_Admin_Abstract
{	
    /**
     * params 
     * FIXME - must be protected (at first)
     * FIXME - to remove; see Core/Handler.php (lowest priority task)
     * 
     * @var    array
     * @access public
     * @since  3.0.0
     */
    public $params = array('target', 'page');
    
    /**
     * Return current module options
     * 
     * @return array 
     * @access protected
     * @since  3.0.0
     */
    protected function getOptions()
    {
        // TODO - check if we need to cache this
        $config = new XLite_Model_Config(); 

        return $config->getByCategory($this->page);
    }


    /**
     * Update module settings 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function action_update()
    {
        foreach ($this->getOptions() as $option) {

            $name  = $option->get('name');
            $value = XLite_Core_Request::getInstance()->$name;

            switch ($option->get('type')) {

                case 'checkbox':
                    $value = isset($value) ? 'Y' : 'N';
                    break;

                case 'serialized':
                    $value = serialize($value);
                    break;

                default:
                    $value = trim($value);
            }

            $option->set('value', $value);
            $option->update();
        }
    }
}
