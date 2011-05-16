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
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Benchmark summary block
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class BenchmarkSummary extends \XLite\View\AView
{
    /**
     * Measure (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $measure;

    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'main';

        return $list;
    }

    /**
     * Get a list of JS files required to display the widget properly
     * FIXME - decompose these files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'benchmark_summary/controller.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'benchmark_summary/body.tpl';
    }

    /**
     * Check - benchmark did run in past or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isAlreadyMeasure()
    {
        $measure = $this->getMeasure();

        return 0 < $measure['total'];
    }

    /**
     * Get measure 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMeasure()
    {
        if (!isset($this->measure)) {
            $repo = \XLite\Core\Database::getRepo('XLite\Model\Measure');

            $this->measure = array(
                'total' => $repo->getScore(),
                'fs'    => $repo->getFilesystemScore(),
                'db'    => $repo->getDatabaseScore(),
                'cpu'   => $repo->getComputationScore(),
            );
        }

        return $this->measure;
    }

    /**
     * Get last measure date 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLastDate()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Measure')->getLastDate();
    }

    /**
     * Get hosting score 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHostingScore()
    {
        return \XLite\Core\Marketplace::getInstance()->getHostingScore();
    }
}
