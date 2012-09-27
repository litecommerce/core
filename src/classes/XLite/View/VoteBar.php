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

namespace XLite\View;

/**
 * Bread crumbs widget
 *
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
     * Get a list of CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'vote_bar/vote_bar.css';

        return $list;
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'vote_bar/vote_bar.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
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
     */
    protected function getStars()
    {
        $stars  = array();
        $filled = false;
        $cost   = $this->getParam(self::PARAM_MAX) / $this->getParam(self::PARAM_LENGTH);

        $rest   = $this->getParam(self::PARAM_RATE);

        for ($i = 0; $i < $this->getParam(self::PARAM_LENGTH); $i++) {

            $isFull    = $cost <= $rest;
            $isPercent = 0 < $rest && $cost > $rest;

            $stars[$i] = array(
                'full'    => $isFull,
                'percent' => $isPercent ? round($rest * 100 / $cost) : false
            );

            if ($isPercent && !$filled) {
                $filled = true;
            }

            $rest -= $cost;
        }

        return $stars;
    }
}
