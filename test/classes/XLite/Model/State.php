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
 * @subpackage Model
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
class XLite_Model_State extends XLite_Model_Abstract
{
    /**
    * @var string $alias The State database table alias.
    * @access public
    */	
    public $alias = "states";

    /**
    * @var array $fields The state properties.
    * @access private
    */	
    public $fields = array(
            'state_id' => '',
            'state'    => '',  
            'code'     => '',
            'country_code'     => '',
            'shipping_zone' => 0
        );

    public $autoIncrement = "state_id";
    public $defaultOrder = "state";

    function readAll()
    {
        static $cache;

        if (!isset($cache)) {
            $cache = parent::readAll();
        }
        return $cache;
    }

    function get($name)
    {
        if ($name != 'state_id') {
            $id = $this->get("state_id");
            if ($id == -1) {
                switch ($name) {
                case 'state': return (parent::get("state")) ? parent::get("state") : 'Other';
                case 'country_code': return '';
                case 'code': return '';
                case 'shipping_zone': return 0;
                }
            }
        }
        return parent::get($name);
    }

}
