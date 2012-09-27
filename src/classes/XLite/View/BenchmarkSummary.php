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
 * Benchmark summary block
 *
 *
 * @ListChild (list="main", weight="300", zone="admin")
 */
class BenchmarkSummary extends \XLite\View\AView
{
    /**
     * Measure (cache)
     *
     * @var array
     */
    protected $measure;

    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'main';

        return $list;
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    protected function checkACL()
    {
        return parent::checkACL()
            && \XLite\Core\Auth::getInstance()->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS);
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'benchmark_summary/body.tpl';
    }

    /**
     * Check - benchmark did run in past or not
     *
     * @return boolean
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
     */
    protected function getLastDate()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Measure')->getLastDate();
    }

    /**
     * Get hosting score
     *
     * @return array
     */
    protected function getHostingScore()
    {
        return \XLite\Core\Marketplace::getInstance()->getHostingScore();
    }

    /**
     * Check - score is high or not
     *
     * @return boolean
     */
    protected function isHighScore()
    {
        $infelicity = 0.05;
        $measure = $this->getMeasure();

        $hostingScores = $this->getHostingScore();

        $highScore = null;
        if ($hostingScores) {
            foreach ($hostingScores as $hostingScore) {
                if (!isset($highScore) || $hostingScore['score'] > $highScore) {
                    $highScore = $hostingScore['score'];
                }
            }
        }

        return isset($highScore) && $measure && ($highScore * (1 + $infelicity)) < $measure['total'];
    }
}
