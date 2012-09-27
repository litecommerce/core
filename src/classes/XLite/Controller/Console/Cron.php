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

namespace XLite\Controller\Console;

/**
 * Cron controller
 *
 */
class Cron extends \XLite\Controller\Console\AConsole
{
    /**
     * Time limit (seconds)
     *
     * @var integer
     */
    protected $timeLimit = 600;

    /**
     * Memory limit (bytes)
     *
     * @var integer
     */
    protected $memoryLimit = 4000000;

    /**
     * Memory limit from memory_limit PHP setting (bytes)
     *
     * @var integer
     */
    protected $memoryLimitIni;

    /**
     * Sleep time
     *
     * @var integer
     */
    protected $sleepTime = 3;

    /**
     * Start time 
     * 
     * @var integer
     */
    protected $startTime;

    /**
     * Preprocessor for no-action
     *
     * @return void
     */
    protected function doNoAction()
    {
        $this->startTime = time();
        $this->startMemory = memory_get_usage(true);
        $this->memoryLimitIni = \XLite\Core\Converter::convertShortSize(ini_get('memory_limit') ?: '16M');

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Task')->getCurrentQuery() as $task) {
            $task = $task[0];
            $runner = $task->getOwnerInstance();
            if ($runner) {
                $this->runRunner($runner);
            }

            sleep($this->sleepTime);

            if (!$this->checkThreadResource()) {
                $time = gmdate('H:i:s', time() - $this->startTime);
                $memory = \XLite\Core\Converter::formatFileSize(memory_get_usage(true));
                $this->printContent('Step is interrupted (time: ' . $time . '; memory usage: ' . $memory. ')');

                break;
            }
        }
    }

    /**
     * Check thread resource 
     * 
     * @return boolean
     */
    protected function checkThreadResource()
    {
        return time() - $this->startTime < $this->timeLimit
            && $this->memoryLimitIni - memory_get_usage(true) > $this->memoryLimit;
    }

    /**
     * Run runner 
     * 
     * @param \XLite\Core\Task\ATask $runner Runner
     *  
     * @return void
     */
    protected function runRunner(\XLite\Core\Task\ATask $runner)
    {
        $silence = !$runner->getTitle();
        if ($runner && $runner->isReady()) {
            if (!$silence) {
                $this->printContent($runner->getTitle() . ' ... ');
            }

            $runner->run();

            if (!$silence) {
                $this->printContent($runner->getMessage() ?: 'done');
            }
        }

        if (!$silence) {
            $this->printContent(PHP_EOL);
        }

        \XLite\Core\Database::getEM()->flush();
    }
}
