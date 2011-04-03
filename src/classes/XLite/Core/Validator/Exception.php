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
 * Validate exception 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Exception extends \XLite\Core\Exception
{
    /**
     * Path 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $path = array();

    /**
     * Message label arguments 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $arguments = array();

    /**
     * Add path item 
     * 
     * @param mixed $item Path item key
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addPathItem($item)
    {
        array_unshift($this->path, $item);
    }

    /**
     * Get path as string
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPath()
    {
        $path = $this->path[0];

        if (1 < count($this->path)) {
            $path .= '[' . implode('][', array_slice($this->path, 1)) . ']';
        }

        return $path;
    }

    /**
     * Mark exception as internal error exception
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function markAsInternal()
    {
        $this->internal = true;
    }

    /**
     * Check - exception is internal or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * Set message arguments 
     * 
     * @param array $arguments Arguments
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setLabelArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get message arguments 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLabelArguments()
    {
        return $this->arguments;
    }
}
