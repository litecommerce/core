<?php
/**
 * XDebug profiler log visualizer
 *
 * @version SVN: $Id$
 */

// Constants
define ('PHPUC_INSTALL_DIR', '/usr/local/share/pear/phpUnderControl');
define ('PHPUC_DATA_DIR', realpath( PHPUC_INSTALL_DIR . '/../data' ));
define ('PHPUC_BIN_DIR', PHPUC_INSTALL_DIR . '/../bin');
define ('PHPUC_EZC_BASE', PHPUC_INSTALL_DIR . '/../ezc/Base/base.php');

// Load ezc Graph
require_once PHPUC_INSTALL_DIR . '/Util/Autoloader.php';
$autoloader = new phpucAutoloader();
spl_autoload_register( array( $autoloader, 'autoload' ) );
if ( file_exists( PHPUC_EZC_BASE ) ) {
    include_once PHPUC_EZC_BASE;
    spl_autoload_register( array( 'ezcBase', 'autoload' ) );
}

// Get arguments
array_shift($_SERVER['argv']);
$path = array_shift($_SERVER['argv']);

if (!file_exists($path)) {
	echo ('Log file path is not correct' . PHP_EOL);
	die(1);
}

// Configure graph
$graph = new ezcGraphLineChart();
$graph->title = 'Xdebug trace';
$graph->options->font->maxFontSize = 18;

$graph->legend = false;

$graph->yAxis = new ezcGraphChartElementNumericAxis();
$graph->yAxis->label = 'Memory, Mbytes';
$graph->yAxis->majorStep = 5;
$graph->yAxis->minorStep = 1;

// Collect data
$fp = fopen($path, 'r');
fgets($fp);
fgets($fp);

echo 'Collect data ... ';

$data = array();
$lastMemory = 0;
$i = 0;
while (!feof($fp)) {
	$row = fgetcsv($fp, 1024, "\t");
	if (8 < count($row) && '0' == $row[2]) {
		if ($i % 100 == 0) {
			$row[4] = round($row[4] / 1024 / 1024, 3);
			$diff = $row[4] - $lastMemory;
			$data[$i] = $row[4];
			$lastMemory = $row[4];
		}
		$i++;
	}
}
fclose($fp);

echo 'done' . PHP_EOL;

// Build graph
echo 'Preprocess data ... ';

$graph->data['Memory'] = new ezcGraphArrayDataSet($data);

echo 'done' . PHP_EOL;

echo 'Build graph ... ';

$graph->render(1024, 768, $path . '.svg');

echo 'done' . PHP_EOL;
