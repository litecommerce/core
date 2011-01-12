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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Bread crumbs widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class VoteBar extends \XLite\View\AView
{
    /**
     * Widget param names
     */

    const PARAM_LENGTH = 'length';
    const PARAM_RATE   = 'rate';
    const PARAM_MAX    = 'max';

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'vote_bar/vote_bar.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_LENGTH => new \XLite\Model\WidgetParam\Int('', 5),
            self::PARAM_RATE   => new \XLite\Model\WidgetParam\Int('', 0),
            self::PARAM_MAX    => new \XLite\Model\WidgetParam\Int('', 100),
        );
    }

    /**
     * Get stars data array
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getStars()
    {
        $stars  = array();
        $filled = false;
        $cost   = $this->getParam(self::PARAM_MAX) / $this->getParam(self::PARAM_LENGTH);

        for ($i = 0; $i < $this->getParam(self::PARAM_LENGTH); $i++) {

            $isFull    = ($i+1)*$cost <= $this->getParam(self::PARAM_RATE);
            $isPercent = ($i+1)*$cost < $this->getParam(self::PARAM_RATE) || $filled;

            $stars[$i] = array(
                'full'    => $isFull,
                'percent' => $isPercent ? false : round(($this->getParam(self::PARAM_RATE) % $cost) / $cost * 100)
            );

            if ($isPercent && !$filled) {
                $filled = true;
            }
        }

        return $stars;
    }

    /**
     * Get a list of CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'vote_bar/vote_bar.css';

        return $list;
    }
}
