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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Image operator 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ImageOperator extends \Xlite\Base\SuperClass
{
    /**
     * Engine 
     * 
     * @var    \XLite\Core\ImageOperator\AImageOperator
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $engine;

    /**
     * Model 
     * 
     * @var    \XLite\Model\Base\Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $model;

    /**
     * Prepared flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $prepared = false;

    /**
     * Get engine 
     * 
     * @return \XLite\Core\ImageOperator\AImageOperator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getEngine()
    {
        // Binary ImageMagic
        if (!isset(static::$engine)) {
            if (\XLite\Core\ImageOperator\ImageMagic::isEnabled()) {
                static::$engine = new \XLite\Core\ImageOperator\ImageMagic;

            } elseif (\XLite\Core\ImageOperator\GD::isEnabled()) {
                static::$engine = new \XLite\Core\ImageOperator\GD;
            }
        }

        return static::$engine;
    }

    /**
     * Constructor
     * 
     * @param \XLite\Model\Base\Image $image Image
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(\XLite\Model\Base\Image $image)
    {
        $this->model = $image;
    }

    /**
     * Prepare image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepare()
    {
        if (!$this->prepared) {
            static::getEngine()->setImage($this->model);
            $this->prepared = true;
        }
    }

    /**
     * Call engine
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
        $this->prepare();

        return call_user_func_array(array(static::getEngine(), $method), $args);
    }

    /**
     * Call engine (static)
     *
     * @param string $method Method name
     * @param array  $args   Arguments
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __callStatic($method, array $args = array())
    {
        return call_user_func_array(array(get_class(static::getEngine()), $method), $args);
    }

}
