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
 * Integer
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Integer extends \XLite\Core\Validator\Float
{
    /**
     * Validate
     *
     * @param mixed $data Data
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function validate($data)
    {
        parent::validate($data);

        if (
            !is_int($data)
            && (!is_string($data) || !preg_match('/^\s*[+-]?\d+\s*$/Ssi', $data))
        ) {
            throw $this->throwError('Not a integer');
        }
    }

    /**
     * Sanitaize
     *
     * @param mixed $data Data
     *
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sanitize($data)
    {
        return intval($data);
    }

}

