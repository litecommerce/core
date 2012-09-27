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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model;

/**
 * \XLite\Model\ActionStatus
 *
 */
class ActionStatus extends \XLite\Base
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
     * @var integer
     */
    protected $status = self::STATUS_UNDEFINED;

    /**
     * Code
     *
     * @var integer
     */
    protected $code = null;

    /**
     * Status info
     *
     * @var string
     */
    protected $message = null;

    /**
     * allowedStatuses
     *
     * @var array
     */
    protected $allowedStatuses = array(
        self::STATUS_ERROR,
        self::STATUS_SUCCESS,
    );


    /**
     * __construct
     *
     * @param integer $status  Action status
     * @param string  $message Status info OPTIONAL
     * @param integer $code    Code OPTIONAL
     *
     * @return void
     */
    public function __construct($status, $message = '', $code = 0)
    {
        parent::__construct();

        if ($this->checkStatus($status)) {
            $this->status  = $status;
            $this->message = $message;
            $this->code    = $code;
        } else {
            $this->doDie('\XLite\Model\ActionStatus::__construct(): unallowed status - "' . strval($status) . '"');
        }
    }

    /**
     * isError
     *
     * @return boolean
     */
    public function isError()
    {
        return self::STATUS_ERROR === $this->status;
    }

    /**
     * isSuccess
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return self::STATUS_SUCCESS === $this->status;
    }

    /**
     * getStatus
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * getCode
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getMessage;
    }


    /**
     * checkStatus
     *
     * @param mixed $status Value to check
     *
     * @return boolean
     */
    protected function checkStatus($status)
    {
        return in_array($status, $this->allowedStatuses);
    }
}
