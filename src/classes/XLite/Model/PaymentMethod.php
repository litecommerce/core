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

namespace XLite\Model;

/**
 * Payment method 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PaymentMethod extends \XLite\Model\AModel
{
    /**
     * Values returned by habdleRequest($order)
     */
    
    const PAYMENT_SILENT  = 1; // disable output
    const PAYMENT_SUCCESS = 2; // show success page
    const PAYMENT_FAILURE = 3; // show error page


    /**
     * Db table name
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $alias = 'payment_methods';

    /**
     * Db table primary key(s)
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $primaryKey = array('payment_method');

    /**
     * Filed to use in ORDERBY clause 
     * 
     * @var    string
     * @access public
     * @since  3.0.0
     */
    public $defaultOrder = 'orderby';

    /**
     * Db table fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $fields = array(
        'payment_method' => '',  
        'name'           => '',
        'details'        => '',
        'class'          => '',
        'params'         => '',
        'orderby'        => '',
        'enabled'        => 1,
    );

    /**
     * Payment method params 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $params = null;

    /**
     * Determines if there is a configuration form for this payment method 
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $hasConfigurationForm = false;

    /**
     * Payment methods availabled by default
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected static $registeredPaymentMethods = array(
        'PhoneOrdering' => 'Model\PaymentMethod\Offline',
        'FaxOrdeing'    => 'Model\PaymentMethod\Offline',
        'PurchaseOrder' => 'Model\PaymentMethod\Offline',
        'Echeck'        => 'Model\PaymentMethod\Echeck',
        'COD'           => 'Model\PaymentMethod\Offline',
        'MoneyOrdering' => 'Model\PaymentMethod\Offline',
    );

    /**
     * Handler to use/iterate on methods 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $handler = array(
        'class_name'   => null,
        'class_object' => null,
    );

    /**
     * Handle request 
     * 
     * @param \XLite\Model\Cart $cart Cart
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0.0
     */
    public function handleRequest(\XLite\Model\Cart $cart)
    {
    }

    /**
     * Get payment method handler object
     * 
     * @param string $name Payment method name
     *  
     * @return \XLite\Model\PaymentMethod
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandler($name = null)
    {
        if (is_null($name)) {
            $name = $this->get('payment_method');
        }

        if (
            is_null($this->handler['class_object'])
            || $name != $this->handler['class_name']
        ) {
            $this->handler['class_object'] = self::factory($name);
            $this->handler['class_name']   = $name;
        }

        return $this->handler['class_object'];
    }

    /**
     * Define available methods range 
     * 
     * @param string $id method identifier
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        // TODO - check it
        $this->_range .= (empty($this->_range) ? '' : ' AND ') 
            . 'payment_method IN (\'' . join('\',\'', array_keys(self::$registeredPaymentMethods)) . '\')';
    }

    /**
     * A method which registers a new payment method $name.
     * A payment method won't be visible untill you register it.
     * Re-create this object after you call this method, like this:
     * $pm = new \XLite\Model\PaymentMethod();
     * $pm->registerMethod('my_method');
     * $pm = new \XLite\Model\PaymentMethod();
     * $pm->getActiveMethods();
     *
     * @param string $name  method name
     * @param string $class method class
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function registerMethod($name, $class)
    {
        if (!isset(self::$registeredPaymentMethods[$name])) {
            self::$registeredPaymentMethods[$name] = $class;
        }
    }

    /**
     * Check if method is already registered 
     * 
     * @param string $name method name
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public static function isRegisteredMethod($name)
    {
        return isset(self::$registeredPaymentMethods[$name]);
    }

    /**
     * Return list of active payment methods 
     * 
     * @return \XLite\Model\PaymentMethod
     * @access public
     * @since  3.0.0
     */
    public function getActiveMethods()
    {
        return $this->findAll('enabled = \'1\'');
    }

    /**
     * Configuration request handler (controller part)
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleConfigRequest()
    {
        $this->set('params', \XLite\Core\Request::getInstance()->params);
        $this->update();
    }
    
    /**
     * Getter
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function get($name)
    {
        $result = parent::get($name);

        if ('params' == $name) {
            if (!isset($this->params)) {
                $this->params = empty($result) ? array() : @unserialize($result);
                // Backward compatibility; uncomment if needed
                /* if (is_object($this->params)) {
                    $this->params = get_object_vars($this->params);
                } */
            }

            return $this->params;
        }

        return $result;
    }

    /**
     * Setter
     * 
     * @param string $name property name
     * @param mixed  $val  property value
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function set($name, $val)
    {
        if ('params' == $name) {
            $this->params = $val;
            $val = serialize($val);
        }

        parent::set($name, $val);
    }

    /**
     * Return payment method object by it's name
     * 
     * @param string $name payment method name
     *  
     * @return \XLite\Model\PaymentMethod
     * @access public
     * @since  3.0.0
     */
    public static function factory($name)
    {
        if (empty(self::$registeredPaymentMethods[$name])) {
            die ('Payment method "' . $name . '" is not registered');
        }

        $className = '\XLite\\' . self::$registeredPaymentMethods[$name];

        return new $className($name);
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        $value = parent::__get($name);

        if (is_null($value)) {
            $handler = $this->getHandler();

            if (property_exists($handler, $name)) {
                $value = $handler->$name;
            }
        }

        return $value;
    }

    /**
     * Caller
     * 
     * @param string $method Method name
     * @param array  $args   Arguments
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        $handler = $this->getHandler();

        return method_exists($handler, $method)
            ? call_user_func_array(array($handler, $method), $args)
            : parent::__call($method, $args);
    }
}

