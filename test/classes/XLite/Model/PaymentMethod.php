<?php

class XLite_Model_PaymentMethod extends XLite_Model_Abstract
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
     * @since  3.0
     */
    protected $alias = 'payment_methods';

    /**
     * Db table primary key(s)
     * 
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $primaryKey = array('payment_method');

    /**
     * Filed to use in ORDERBY clause 
     * 
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $defaultOrder = 'orderby';

    /**
     * Db table fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $fields = array(
		'payment_method' => '',  
		'name'		     => '',
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
     * @since  3.0
     */
    protected $params = null;

    /**
     * Determines if there is a configuration form for this payment method 
     * 
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $hasConfigurationForm = false;

	/**
	 * Payment methods availabled by default
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected static $registeredPaymentMethods = array(
		'PhoneOrdering' => 'Model_PaymentMethod_Offline',
		'FaxOrdeing'    => 'Model_PaymentMethod_Offline',
		'PurchaseOrder' => 'Model_PaymentMethod_Offline',
		'CreditCard'    => 'Model_PaymentMethod_CreditCard',
		'Echeck'        => 'Model_PaymentMethod_Echeck',
		'COD'           => 'Model_PaymentMethod_Offline',
		'MoneyOrdering' => 'Model_PaymentMethod_Offline',
	);

    /**
     * Define available methods range 
     * 
     * @param string $id method identifier
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

		$this->_range .= (empty($this->_range) ? '' : ' AND ') 
			. 'payment_method IN (\'' . join('\',\'', array_keys(self::$registeredPaymentMethods)) . '\')';
    }

    /**
	 * A method which registers a new payment method $name.
     * A payment method won't be visible untill you register it.
     * Re-create this object after you call this method, like this:
     * $pm = new XLite_Model_PaymentMethod();
     * $pm->registerMethod('my_method');
     * $pm = new XLite_Model_PaymentMethod();
     * $pm->getActiveMethods();
	 *
     * @param string $name  method name
     * @param string $class method class
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function registerMethod($name, $class)
    {
		isset(self::$registeredPaymentMethods[$name]) || self::$registeredPaymentMethods[$name] = $class;
    }

    /**
     * Check if method is already registered 
     * 
     * @param string $name method name
     *  
     * @return bool
     * @access public
     * @since  3.0
     */
    public static function isRegisteredMethod($name)
    {
        return isset(self::$registeredPaymentMethods[$name]);
    }

    /**
     * Return list of active payment methods 
     * 
     * @return XLite_Model_PaymentMethod
	 * @access public
     * @since  3.0
     */
    public function getActiveMethods()
    {
		return $this->findAll('enabled = \'1\'');
	}

    /**
     * handleConfigRequest 
     * 
     * @return void
     * @access public
     * @since  3.0
     */
    public function handleConfigRequest()
    {
        $this->set('params', $_POST['params']);
        $this->update();
    }
    
    /**
     * get 
     * 
     * @param mixed $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function get($name)
    {
        $result = parent::get($name);

        if ($name == "params") {
            if (is_null($this->params) && !empty($result)) {
                $this->params = unserialize($result);
                if(is_object($this->params)) { // backward compatibility
                    $this->params = get_object_vars($this->params);
                }
            }
            $result = $this->params;
		}

        return $result;
    }

    /**
     * set 
     * 
     * @param string $name property name
     * @param mixed  $val  property value
     *  
     * @return void
	 * @access public
     * @since  3.0
     */
    public function set($name, $val)
    {
        if ($name == "params") {
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
	 * @return XLite_Model_PaymentMethod
	 * @access public
	 * @since  3.0
	 */
	public static function factory($name)
	{
		$className = 'XLite_' . self::$registeredPaymentMethods[$name];

		return new $className($name);
	}
}

