<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * XLite_Controller_Admin_Module 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
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

