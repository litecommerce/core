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
 * Hash array validator 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class HashArray extends \XLite\Core\Validator\AValidator
{
    /**
     * Pairs 
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pairs;

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->pairs = new \Doctrine\Common\Collections\ArrayCollection;
    }

    /**
     * Add pair validator
     * 
     * @param mixed                            $name      Cell name or pair validator
     * @param \XLite\Core\Validator\AValidator $validator Cell validator
     * @param string                           $mode      Pair validation mode
     *
     * @return \XLite\Core\Validator\AValidator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addPair(
        $name,
        \XLite\Core\Validator\AValidator $validator = null,
        $mode = \XLite\Core\Validator\Pair\APair::STRICT
    ) {
        $result = null;

        if (is_object($name) && $name instanceof \XLite\Core\Validator\Pair\APair) {

            // Add pair
            $result = $name;

        } elseif ($name && $validator) {

            // Create and add pair
            $result = new \XLite\Core\Validator\Pair\Simple($mode);
            $result->setName($name);
            $result->setValidator($validator);
        }

        if ($result) {
            $this->pairs[] = $result;
            if (method_exists($result, 'getValidator')) {
                $result = $result->getValidator();
            }
        }

        return $result ? $result : null;
    }

    /**
     * Get pair validators
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPairs()
    {
        return $this->pairs;
    }

    /**
     * Get child cell validator
     * 
     * @param mixed $name Name
     *  
     * @return \XLite\Core\Validator\AValidator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getChild($name)
    {
        $result = null;

        foreach ($thi->getPairs() as $pair) {
            if ($pair->getName() == $name) {
                $result = $pair->getValidator();
                break;
            }
        }

        return $result;
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
        if (!is_array($data)) {
            throw $this->throwError('Not an array');
        }

        foreach ($this->getPairs() as $pair) {
            $pair->validate($data);
        }
    }

    /**
     * Sanitaize
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
        $sanitizedData = array();

        foreach ($this->getPairs() as $pair) {
            $sanitizedData += $pair->sanitize($data);
        }

        return $sanitizedData;
    }
}
