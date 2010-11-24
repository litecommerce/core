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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core\Validator;

/**
 * Abstract validator 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AValidator
{
    /**
     * Validate 
     * 
     * @param mixed $data Data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function validate($data);

    /**
     * Sanitize 
     * 
     * @param mixed $data Daa
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sanitize($data)
    {
        return $data;
    }

    /**
     * Throw error exception
     * 
     * @param string $message   Message
     * @param array  $arguments Language label arguments
     * @param mixed  $pathItem  Path item key OPTIONAL
     *  
     * @return \XLite\Core\Validator\Exception
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function throwError($message, array $arguments = array(), $pathItem = null)
    {
        $exception = new \XLite\Core\Validator\Exception($message);
        $exception->setLabelArguments($arguments);
        if (isset($pathItem)) {
            $exception->addPathItem($pathItem);
        }

        return $exception;
    }

    /**
     * Throw internal error exception
     *
     * @param string $message   Message
     * @param array  $arguments Language label arguments
     *
     * @return \XLite\Core\ValidateException
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function throwInternalError($message, array $arguments = array())
    {
        $exception = new \XLite\Core\Validator\Exception($message);
        $exception->setLabelArguments($arguments);
        $exception->markAsInternal();

        return $exception;
    }
}
