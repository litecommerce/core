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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Profiler
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Profiler extends XLite_Base implements XLite_Base_ISingleton
{
    const QUERY_LIMIT_TIMES = 2;
    const QUERY_LIMIT_DURATION = 0.05;

    const TRACE_BEGIN = 3;
    const TRACE_LENGTH = 8;

    /**
     * List of executed queries 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected static $queries = array();

    /**
     * List of memory measuring points 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $memoryPoints = array();

    /**
     * Enabled flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $enabled = false;

    /**
     * Start time
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $start_time = null;

    /**
     * Stop time 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $stop_time = null;

    /**
     * Included files list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $includedFiles = array();

    /**
     * Included files total size
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $includedFilesTotal = 0;

    /**
     * Included files count 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $includedFilesCount = 0;

    /**
     * Last time 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lastTime = 0;

    /**
     * Time points 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $points = array();

    /**
     * Profiler should not start on these targets 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $disallowedTargets = array(
        'image',
    );


    /**
     * There are some targets which are not require profiler
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isTargetAllowed()
    {
        return !in_array(XLite_Core_Request::getInstance()->target, $this->disallowedTargets);
    }

    /**
     * Getter
     * 
     * @param string $name Peroperty name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        if ('enabled' == $name) {
            $result = $this->enabled;

        } elseif (isset($this->points[$name])) {

            $result = isset($this->points[$name]['end'])
                ? ($this->points[$name]['end'] - $this->points[$name]['start'])
                : 0;

        } else {
            $result = parent::__get($name);
        }

        return $result;
    }

    /**
     * getStartupFlag 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function getStartupFlag()
    {
        return XLite::getInstance()->getOptions(array('profiler_details', 'enabled'))
            && $this->isTargetAllowed()
            && !XLite_Core_Request::getInstance()->isPost()
            && !XLite_Core_Request::getInstance()->isCLI()
            && !XLite_Core_Request::getInstance()->isAJAX();
    }

    /**
     * Use this function to get a reference to this class object 
     * 
     * @return XLite_Model_Profiler
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->start($this->getStartupFlag());
    }

    /**
     * Destructor
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __destruct()
    {
        $this->stop();
    }

    /**
     * Log same time range
     * 
     * @param string  $timePoint  Time range name
     * @param boolean $additional Additional metric flag
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function log($timePoint, $additional = false)
    {
        if (!isset($this->points[$timePoint])) {
            $this->points[$timePoint] = array(
                'start' => microtime(true),
                'open'  => true,
                'time'  => 0,
            );

        } elseif ($this->points[$timePoint]['open']) {

            $range = microtime(true) - $this->points[$timePoint]['start'];
            if ($additional) {
                $this->points[$timePoint]['time'] += $range;
            } else {
                $this->points[$timePoint]['time'] = $range;
            }
            $this->points[$timePoint]['open'] = false;

        } else {

            $this->points[$timePoint]['start'] = microtime(true);
            $this->points[$timePoint]['open'] = true;

        }
    }
    
    /**
     * Start profiler
     * 
     * @param boolean $start Enable flag
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function start($start)
    {
        $this->enabled = !empty($start);
        $this->start_time = $_SERVER['REQUEST_TIME'];
    }
    
    /**
     * Stop profiler
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stop()
    {
        if ($this->enabled && !XLite_Core_Request::getInstance()->isPopup) {

            $this->stop_time = microtime(true);

            $this->includedFiles = array();
            $this->includedFilesTotal = 0;

            foreach (get_included_files() as $file) {
                $size = intval(@filesize($file));
                $this->includedFiles[] = array(
                    'name' => $file,
                    'size' => $size
                );
                $this->includedFilesTotal += $size;
            }
            $this->includedFilesCount = count($this->includedFiles);

            usort($this->includedFiles, array($this, 'sortCallback'));

            $this->display();
        }
    }
    
    /**
     * Display profiler report
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function display()
    {
        $totalQueriesTime = 0;
        foreach (self::$queries as $q => $d) {
            $cnt = count($d['time']);
            $sum = array_sum($d['time']);
            self::$queries[$q] = array(
                'count' => $cnt,
                'max'   => max($d['time']),
                'min'   => min($d['time']),
                'avg'   => $sum / $cnt,
                'trace' => $d['trace'],
            );
            $totalQueriesTime += $sum;
        }

        $execTime = number_format($this->stop_time - $this->start_time, 4);
        $memoryPeak = round(memory_get_peak_usage() / 1024 / 1024, 3);
        $totalQueries = count(self::$queries);
        $totalQueriesTime = number_format($totalQueriesTime, 4);
        $dbConnectTime = number_format($this->dbConnectTime, 4);

        $this->includedFilesTotal = round($this->includedFilesTotal / 1024, 3);

        $html = <<<HTML
<table cellspacing="0" cellpadding="3" style="width: auto;">
    <tr>
        <td><strong>EXECUTION TIME</strong></td>
        <td>$execTime</td>
    </tr>
    <tr>
        <td><strong>MEMORY PEAK USAGE</strong></td>
        <td>$memoryPeak Mb</td>
    </tr>
    <tr>
        <td><strong>TOTAL QUERIES</strong></td>
        <td>$totalQueries</td>
    </tr>
    <tr>
        <td><strong>TOTAL QUERIES TIME</strong></td>
        <td>$totalQueriesTime</td>
    </tr>

    <tr>
        <td><strong>Included files</strong></td>
        <td>$this->includedFilesCount</td>
    </tr>

    <tr> 
        <td><strong>Included files total size</strong></td>
        <td>$this->includedFilesTotal Kb.</td>
    </tr>

    <tr>
        <td><strong>Database connect time</strong></td>
        <td>$dbConnectTime sec.</td>
    </tr>

</table>

<table cellspacing="0" cellpadding="3" border="1" style="width: auto;">
    <caption>Queries log</caption>
    <tr>
        <th>Times</th>
        <th>Max. duration, sec.</th>
        <th>Query</th>
    </tr>
HTML;

        echo ($html);

        $warnStyle = ' background-color: red; font-weight: bold;';

        foreach (self::$queries as $query => $d) {
            $timesLimit = (self::QUERY_LIMIT_TIMES < $d['count'] ? $warnStyle : '');
            $durationLimit = (self::QUERY_LIMIT_DURATION < $d['max'] ? $warnStyle : '');

            echo (
                '<tr>' . "\n"
                . '<td style="vertical-align: top;' . $timesLimit . '">'
                . $d['count']
                . '</td>'
                . '<td style="vertical-align: top;' . $durationLimit . '">'
                . number_format($d['max'], 4)
                . '</td><td style="white-space: nowrap;">'
                . $query . '<br />'
                . implode(' << ', $d['trace'])
                . '</td></tr>' . "\n"
            );
        }

        echo ('</table>');

        if (self::$memoryPoints) {
            $html = <<<HTML
<table cellspacing="0" cellpadding="3" border="1" style="width: auto;">
    <caption>Memory points</caption>
    <tr>
        <th nowrap="nowrap">Memory, Mbytes</th>
        <th nowrap="nowrap">Changes, Mbytes</th>
        <th>Back trace</th>
    </tr>
HTML;
            echo ($html);

            $lastMem = 0;
            foreach (self::$memoryPoints as $d) {
                $diff = $d['memory'] - $lastMem;
                echo (
                    '<tr>'
                    . '<td>' . number_format(round($d['memory'] / 1024 / 1024, 3), 3) . '</td>'
                    . '<td>' . number_format(round($diff / 1024 / 1024, 3), 3) . '</td>'
                    . '<td>' . implode(' << ', $d['trace']) . '</td>'
                    . '</tr>'
                );
                $lastMem = $d['memory'];
            }

            echo ('</table>');
        }

        if ($this->points) {
            $html = <<<HTML
<table cellspacing="0" cellpadding="3" border="1" style="width: auto;">
    <caption>Log points</caption>
    <tr>
        <th>Duration, sec.</th>
        <th>Point name</th>
    </tr>
HTML;
            echo ($html);

            foreach ($this->points as $name => $d) {
                echo (
                    '<tr><td>'
                    . number_format($d['time'], 4)
                    . '</td><td>'
                    . $name
                    . '</td></tr>'
                );
            }

            echo ('</table>');
        }
    }

    /**
     * Included files statistics sorting callback
     * 
     * @param array $a File info 1
     * @param array $b File info 2
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sortCallback($a, $b)
    {
        $result = 0;

        if ($a['size'] != $b['size']) {
            $result = $a['size'] < $b['size'] ? 1 : -1;
        }

        return $result;
    }

    /**
     * Add query to log
     * 
     * @param string $query Query
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addQuery($query)
    {
        $this->lastTime = microtime(true);

        // Uncomment if you want to truncate queries
        /*
        if (strlen($query)>300) {
            $query = substr($query, 0, 300) . ' ...';
            
        }
        */

        if (!isset(self::$queries[$query])) {
            self::$queries[$query] = array(
                'time' => array(),
                'trace' => $this->getBackTrace(),
            );
            $this->addMemoryPoint();
        }
    }

    /**
     * Set query time 
     * 
     * @param string $query Query
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setQueryTime($query)
    {
        if (isset(self::$queries[$query])) {
            self::$queries[$query]['time'][] = microtime(true) - $this->lastTime;
        }
    }

    /**
     * Add memory measure point 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addMemoryPoint()
    {
        self::$memoryPoints[] = array(
            'memory' => memory_get_usage(),
            'trace' => $this->getBackTrace(),
        );
    }

    /**
     * Get back trace 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBackTrace()
    {
        $trace = array();

        foreach (debug_backtrace(false) as $l) {
            if (isset($l['file']) && isset($l['line'])) {
                $trace[] = str_replace(
                    array(LC_COMPILE_DIR, LC_ROOT_DIR),
                    array('', ''),
                    $l['file']
                ) . ':' . $l['line'];

            } elseif (isset($l['function']) && isset($l['line'])) {
                $trace[] = $l['function'] . '():' . $l['line'];
            }
        }

        return array_slice($trace, self::TRACE_BEGIN, self::TRACE_LENGTH);
    }
}
