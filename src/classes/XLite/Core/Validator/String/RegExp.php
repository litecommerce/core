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

namespace XLite\Core\Validator\String;

/**
 * Regular expression
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RegExp extends \XLite\Core\Validator\String
{
    /**
     * Regular expression 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $regExp;

    /**
     * Constructor
     *
     * @param boolean $nonEmpty Non-empty flag OPTIONAL
     * @param string  $regExp   Regular expression OPTIONAL
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($nonEmpty = false, $regExp = null)
    {
        parent::__construct($nonEmpty);

        if ($regExp) {
            $this->setRegExp($regExp);
        }
    }

    /**
     * Set regular expression
     * 
     * @param string $regExp Regular expression
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setRegExp($regExp)
    {
        $this->regExp = $regExp;
    }

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
    public function validate($data)
    {
        parent::validate($data);

        if (!$this->regExp) {
            throw $this->throwInternalError('Regular expression is empty');
        }

        if (0 < strlen($data) && !preg_match($this->regExp, $data)) {
            throw $this->throwError('Regular expression does not match');
        }
    }
}

