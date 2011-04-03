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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Core\Validator;

/**
 * Abstract validator 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class AValidator
{
    /**
     * Validate 
     * 
     * @param mixed $data Data
     *  
     * @return void
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
     * @param array  $arguments Language label arguments OPTIONAL
     * @param mixed  $pathItem  Path item key OPTIONAL
     *  
     * @return \XLite\Core\Validator\Exception
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
     * @param array  $arguments Language label arguments OPTIONAL
     *
     * @return \XLite\Core\ValidateException
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
