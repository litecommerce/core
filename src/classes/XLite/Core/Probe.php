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

namespace XLite\Core;

/**
 * Probe
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Probe extends \XLite\Base\Singleton
{
    /**
     * Measure enviroment
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function measure()
    {
        if ($this->checkAccess()) {

            set_time_limit(0);

            $measure = new \XLite\Model\Measure;
            $measure->setDate(time());

            $measure->setFsTime(intval($this->measureFilesystem() * 1000));
            $measure->setDbTime(intval($this->measureDatabase() * 1000));
            $measure->setCpuTime(intval($this->measureComputation() * 1000));

            \XLite\Core\Database::getEM()->persist($measure);
            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Measure filesystem
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function measureFilesystem()
    {
        $fname = tempnam(LC_DIR_TMP, 'probe');

        $length = 1024 * 1024 * 3;

        $data = '';
        $row = file_get_contents(__FILE__);
        while (strlen($data) < $length) {
            $data .= $row;
        }
        $data = substr($data, 0, $length);

        $time = microtime(true);

        file_put_contents($fname, '');
        for ($i = 0; 20 > $i; $i++) {
            file_put_contents($fname, $data, FILE_APPEND);
        }

        $fp = fopen($fname, 'rb');
        while (!feof($fp)) {
            fread($fp, $length);
        }
        fclose($fp);

        return microtime(true) - $time;
    }

    /**
     * Measure database
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function measureDatabase()
    {
        $data = '';
        for ($i = 0; 10 > $i; $i++) {
            $data .= md5(microtime(true) * 1000000);
        }

        $key = md5(microtime(true) * 1000000);

        $connection = \XLite\Core\Database::getEM()->getConnection();

        $emptyTime = microtime(true);
        $connection->executeQuery('SELECT 1');
        $emptyTime = microtime(true) - $emptyTime;

        $table = \XLite\Core\Database::getEM()->getClassMetadata('XLite\Model\MeasureDump')->getTableName();

        $row = file_get_contents(__FILE__);
        $miniRow = substr($row, 0, 255);

        $time = microtime(true);

        $connection->executeQuery('SELECT BENCHMARK(5000, COMPRESS(AES_ENCRYPT(\'' . $data . '\', \'' . $key . '\')))');

        $connection->executeQuery('TRUNCATE `' . $table . '`');

        for ($i = 0; 1000 > $i; $i++) {
            $connection->executeQuery(
                'INSERT INTO `' . $table . '` (`data`, `text`) VALUES (?, ?)', array($miniRow, $row)
            );
        }

        $connection->executeQuery('SELECT BENCHMARK(1000, (SELECT AVG(id) FROM `' . $table . '`))' . $table);
        $connection->executeQuery(
            'SELECT BENCHMARK(1000, (SELECT MAX(id) FROM `' . $table . '` WHERE data LIKE \'%executeQuery%\'))' . $table
        );

        return microtime(true) - $time - $emptyTime;
    }

    /**
     * Measure computation
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function measureComputation()
    {
        $time = microtime(true);

        for ($i = 0; 20 > $i; $i++) {
            $data = array();
            mt_srand(microtime(true) * 1000);
            for ($n = 0; 10000 > $n; $n++) {
                $data[] = mt_rand(0, 1000);
            }

            $string = array_sum($data);
            $string = array_flip($data);
            $string = array_map('addslashes', $data);
            $string = array_map('md5', $data);
            $string = array_map('urlencode', $data);
            $string = array_map('strlen', $data);
        }

        return microtime(true) - $time;
    }

    /**
     * Check access
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAccess()
    {
        return $this->checkWebAccess() || $this->checkCLIAccess() || $this->checkCronAccess();
    }

    /**
     * Check web access
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkWebAccess()
    {
        return isset($_SERVER['REQUEST_METHOD'])
            && 'GET' == $_SERVER['REQUEST_METHOD']
            && !empty($_GET['key'])
            && $_GET['key'] == \XLite\Core\Config::getInstance()->General->probe_key;
    }

    /**
     * Check CLI access
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkCLIAccess()
    {
        return 'cli' == PHP_SAPI
            && !empty($_SERVER['argv'])
            && !empty($_SERVER['argv'][1])
            && $_SERVER['argv'][1] == \XLite\Core\Config::getInstance()->General->probe_key;
    }

    /**
     * Check CLI access
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkCronAccess()
    {
        return \XLite::getController() instanceof \XLite\Controller\Console\Cron;
    }

}
