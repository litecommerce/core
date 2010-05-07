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
 * Payment configuration
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_XPaymentsConnector_Model_Configuration extends XLite_Model_Abstract
{
    /**
     * Object properties (table filed => default value)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
        'confid'         => 0,
        'name'           => '',
        'module'         => '',
        'auth_exp'       => 0,
        'capture_min'    => 1,
        'capture_max'    => 1,
        'hash'           => '',
        'is_auth'        => '',
        'is_capture'     => '',
        'is_void'        => '',
        'is_refund'      => '',
        'is_part_refund' => '',
        'is_accept'      => '',
        'is_decline'     => '',
        'is_get_info'    => '',
    );

    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'xpc_configurations';

    /**
     * Default order file name
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $defaultOrder = 'name';

    /**
     * Db table primary key(s)
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $primaryKey = array('confid');

    /**
     * Returns the specified property of this object. Read the object data from dataase if necessary 
     * 
     * @param string $property field name
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function get($property)
    {
        if ('method_name' == $property) {
            $name = str_replace(
                array(' '),
                array('_'),
                strtolower($this->get('name'))
            );
            $result = preg_replace('/[^a-z0-9]/Ss', '', $name) . '.' . $this->get('confid');

        } else {
            $result = parent::get($property);
        }

        return $result;
    }

    /**
     * Create configuration
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create()
    {
        parent::create();

        $pm = new XLite_Module_XPaymentsConnector_Model_PaymentMethod_XPayment();

        $pm->set('payment_method', $this->get('method_name'));
        $pm->set('name', $this->get('name'));
        $pm->set('class', $this->get('method_name'));
        $pm->set('params', serialize(array()));
        $pm->set('enabled', 0);
        $pm->set('xpc_confid', $this->get('confid'));

        $pm->create();
    }

    /**
     * Delete configuration
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $key = $this->get('method_name');

        parent::delete();

        $pm = new XLite_Module_XPaymentsConnector_Model_PaymentMethod_XPayment();
        $pm = $pm->find('xpc_confid = \'' . $this->get('confid') . '\'');
        if ($pm) {
            $pm->delete();
        }
    }

    /**
     * Delete all configurations
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteAll()
    {
        foreach ($this->findAll() as $c) {
            $c->delete();
        }

        $this->isPersistent = false;
        $this->isRead = false;
    }

}

