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

/**
 * XLite_Model_ActionStatus 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_ActionStatus extends XLite_Base
{
    /**
     * Allowed statuses 
     */

    const STATUS_UNDEFINED = -1;
    const STATUS_ERROR     = 0;
    const STATUS_SUCCESS   = 1;


    /**
     * Action status 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $status = self::STATUS_UNDEFINED;

    /**
     * Code 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $code = null;

    /**
     * Status info
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $message = null;

    /**
     * allowedStatuses 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedStatuses = array(
        self::STATUS_ERROR,
        self::STATUS_SUCCESS,
    );


    /**
     * checkStatus 
     * 
     * @param mixed $status value to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkStatus($status)
    {
        return in_array($status, $this->allowedStatuses);
    }


    /**
     * isError 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isError()
    {
        return self::STATUS_ERROR === $this->status;
    }

    /**
     * isSuccess 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isSuccess()
    {
        return self::STATUS_SUCCESS === $this->status;
    }

    /**
     * getStatus 
     * 
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * getCode 
     * 
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getMessage 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getMessage()
    {
        return $this->getMessage;
    }

    /**
     * __construct 
     * 
     * @param int    $status  action status
     * @param string $message status info
     * @param int    $code    code
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($status, $message = '', $code = 0)
    {
        parent::__construct();

        if ($this->checkStatus($status)) {
            $this->status  = $status;
            $this->message = $message;
            $this->code    = $code;
        } else {
            $this->doDie('XLite_Model_ActionStatus::__construct(): unallowed status - "' . strval($status) . '"');
        }
    }
}
