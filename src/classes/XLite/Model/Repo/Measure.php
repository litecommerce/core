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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

/**
 * Measures repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Measure extends \XLite\Model\Repo\ARepo
{
    /**
     * Get total score
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getScore()
    {
        $result = $this->createQueryBuilder('m')
            ->select('AVG(m.fsTime) fsTime')
            ->addSelect('AVG(m.dbTime) dbTime')
            ->addSelect('AVG(m.cpuTime) cpuTime')
            ->getArrayResult();
        $result = reset($result);

        return $result
            ? round($result['fsTime'] + $result['dbTime'] + $result['cpuTime'], 0)
            : 0;
    }

    /**
     * Get file system score
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFilesystemScore()
    {
        $result = $this->createQueryBuilder('m')
            ->select('AVG(m.fsTime) time')
            ->getArrayResult();
        $result = reset($result);

        return $result ? round($result['time'], 0) : 0;
    }

    /**
     * Get database score
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDatabaseScore()
    {
        $result = $this->createQueryBuilder('m')
            ->select('AVG(m.dbTime) time')
            ->getArrayResult();
        $result = reset($result);

        return $result ? round($result['time'], 0) : 0;
    }

    /**
     * Get computation score
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getComputationScore()
    {
        $result = $this->createQueryBuilder('m')
            ->select('AVG(m.cpuTime) time')
            ->getArrayResult();
        $result = reset($result);

        return $result ? round($result['time'], 0) : 0;
    }

    /**
     * Get last date
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLastDate()
    {
        $result = $this->createQueryBuilder('m')
            ->orderBy('m.date', 'desc')
            ->setMaxResults(1)
            ->getSingleResult();

        return $result ? $result->getDate() : null;
    }
}
