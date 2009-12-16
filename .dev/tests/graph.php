<?php

// Input arguments
array_shift($_SERVER['argv']);
$csv = array_shift($_SERVER['argv']);
$dir = array_shift($_SERVER['argv']);
$prevDir = count($_SERVER['argv']) > 0 ? array_shift($_SERVER['argv']) : false;

if (!file_exists($prevDir) || !is_dir($prevDir) || count(glob($prevDir . '/*/logs/phpunit.xml.speed')) == 0)
	$prevDir = false;

// Constants
define( 'PHPUC_INSTALL_DIR', '/usr/local/share/pear/phpUnderControl' );
define( 'PHPUC_DATA_DIR', realpath( PHPUC_INSTALL_DIR . '/../data' ) );
define( 'PHPUC_BIN_DIR', PHPUC_INSTALL_DIR . '/../bin' );
define( 'PHPUC_EZC_BASE', PHPUC_INSTALL_DIR . '/../ezc/Base/base.php' );

define('polynom', 10);

require_once PHPUC_INSTALL_DIR . '/Util/Autoloader.php';
$autoloader = new phpucAutoloader();
spl_autoload_register( array( $autoloader, 'autoload' ) );
if ( file_exists( PHPUC_EZC_BASE ) ) {
	include_once PHPUC_EZC_BASE;
	spl_autoload_register( array( 'ezcBase', 'autoload' ) );
}

class phpucJTLInput extends phpucAbstractInput
{
    /**
     * Constructs a new unit test input object.
     */
    public function __construct()
    {
        parent::__construct(
            'Test',
            'Test',
            phpucChartI::TYPE_LINE
        );
        $this->yAxisLabel = '';
        $this->xAxisLabel = '';

        $this->addRule(
            new phpucInputRule(
                'Time',
                '//testResults/sampleResult/@time',
                self::MODE_COUNT
            )
        );
    }
	
}

class CustomSVGDrive extends ezcGraphSvgDriver {
	public $percent = false;

	protected function createDocument() {
		parent::createDocument();

		$svg = $this->dom->getElementsByTagName( 'svg' )->item( 0 );

		$svg->setAttribute( 'viewBox', '0 0 ' . $this->options->width . ' ' . $this->options->height );
		if (!$this->percent) {
			$svg->removeAttribute( 'width' );
			$svg->removeAttribute( 'height' );

		} else {
			$svg->setAttribute( 'width', $this->percent );
			$svg->setAttribute( 'height', $this->percent );
		}
	}
}

class AbstractBarChart extends ezcGraphBarChart {

	protected $showSymbol = false;

	public $_rawData = null;

    public function __construct()
    {
        parent::__construct();

        $this->init();
    }

    protected function init()
    {

		$this->driver = new CustomSVGDrive();

        $this->palette = new phpucGraphPalette();

        $this->renderer->options->legendSymbolGleam = .3;

        $this->options->symbolSize    = 1;
        $this->options->lineThickness = 1;
        $this->options->fillLines = false;

        $this->initAxis();
        $this->initTitle();
        $this->initLegend();

		$this->data = new ezcGraphChartDataContainer($this);
    }

    protected function initTitle()
    {
        $this->title->background  = '#d3d7cf';
        $this->title->padding     = 1;
        $this->title->margin      = 1;
        $this->title->border      = '#555753';
        $this->title->borderWidth = 1;
    }

    protected function initLegend()
    {
		$this->legend = false;
/*
        $this->legend->position    = ezcGraph::RIGHT;
        $this->legend->padding     = 2;
        $this->legend->margin      = 1;
        $this->legend->border      = '#555753';
        $this->legend->borderWidth = 1;
*/
    }

    protected function initAxis()
    {
        $this->yAxis->axisLabelRenderer = new ezcGraphAxisCenteredLabelRenderer();
        $this->yAxis->font->maxFontSize = 10;

		$this->xAxis = new ezcGraphChartElementLabeledAxis();
		$this->xAxis->axisLabelRenderer = new ezcGraphAxisRotatedLabelRenderer();
		$this->xAxis->axisLabelRenderer->angle = 45;
		$this->xAxis->font->minFontSize = 20;
		$this->xAxis->axisSpace = 0.2;
		$this->xAxis->label = 'Tests ';
    }
}

class TimeBarChart extends AbstractBarChart {

	public function setInput($data)
	{
        $this->title        = 'PHPUnit tests : Time';
        $this->yAxis->label = 'Tests duration, microsec';

		$subdata = array();
        foreach ($data as $row) {
			if (!isset($subdata[$row['class']])) {
				$subdata[$row['class']] = array(
					'sum' => 0,
					'cnt' => 0
				);
			}

			$subdata[$row['class']]['cnt']++;
			$subdata[$row['class']]['sum'] += $row['time'];
		}

		foreach ($subdata as $class => $t) {
			$subdata[$class] = $t['sum'] / $t['cnt'];
		}

		$this->data['Time'] = new ezcGraphArrayDataSet($subdata);

		foreach ($this->data as $k => $v) {
	        $this->data[$k]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$k] as $key => $v2) {
                $this->data[$k]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }

	}
}

class MemoryBarChart extends AbstractBarChart {

	public function setInput($data)
	{
        $this->title        = 'PHPUnit tests : Memory';
        $this->yAxis->label = 'Tests memory, bytes';

		$subdata = array();
        foreach ($data as $row) {
			if (!isset($subdata[$row['class']])) {
				$subdata[$row['class']] = array(
					'sum' => 0,
					'cnt' => 0
				);
			}

			$subdata[$row['class']]['cnt']++;
			$subdata[$row['class']]['sum'] += $row['memory'];
		}

		foreach ($subdata as $class => $t) {
			$subdata[$class] = $t['sum'] / $t['cnt'];
		}

		$this->data['Memory'] = new ezcGraphArrayDataSet($subdata);

		foreach ($this->data as $k => $v) {
	        $this->data[$k]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$k] as $key => $v2) {
                $this->data[$k]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }

	}
}

class AbstractPrevLineChart extends ezcGraphLineChart {
	public $prevDir = null;

	protected $showSymbol = false;

    public function __construct()
    {
        parent::__construct();

        $this->init();
    }

    protected function init()
    {

		$this->driver = new CustomSVGDrive();

        $this->palette = new phpucGraphPalette();

        $this->renderer->options->legendSymbolGleam = .3;

        $this->options->symbolSize    = 1;
        $this->options->lineThickness = 1;
        $this->options->fillLines = false;

        $this->initAxis();
        $this->initTitle();
        $this->initLegend();

		$this->data = new ezcGraphChartDataContainer($this);
    }

    protected function initTitle()
    {
        $this->title->background  = '#d3d7cf';
        $this->title->padding     = 1;
        $this->title->margin      = 1;
        $this->title->border      = '#555753';
        $this->title->borderWidth = 1;
    }

    protected function initLegend()
    {
		$this->legend = false;
    }

    protected function initAxis()
    {
        $this->yAxis->axisLabelRenderer = new ezcGraphAxisCenteredLabelRenderer();
        $this->yAxis->font->maxFontSize = 10;

		$this->xAxis = new ezcGraphChartElementLabeledAxis();
		$this->xAxis->axisLabelRenderer = new ezcGraphAxisNoLabelRenderer();
		$this->xAxis->label = 'Builds ';
    }

}

class TimePrevLineChart extends AbstractPrevLineChart {

	public function setInput($data)
	{
        $this->title        = 'PHPUnit tests : Time';
        $this->yAxis->label = 'Test duration, microsec.';

		$subdata = array();
		foreach (glob($this->prevDir . '/*/logs/phpunit.xml.speed') as $f) {
			$d = parse_csv_data($f);
			if (count($d) == 0)
				continue;

			$sum = 0;
	        foreach ($d as $row) {
				$sum += $row['time'];
			}
			$subdata[] = $sum / count($d);
		}

		$sum = 0;
        foreach ($data as $row) {
			$sum += $row['time'];
		}
		$subdata[] = $sum / count($data);

        $this->data['Time'] = new ezcGraphArrayDataSet($subdata);

        foreach ($this->data as $k => $v) {
            $this->data[$k]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$k] as $key => $v2) {
                $this->data[$k]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }
	}
}

class MemoryPrevLineChart extends AbstractPrevLineChart {

	public function setInput($data)
	{
        $this->title        = 'PHPUnit tests : Memory';
        $this->yAxis->label = 'Test memory, bytes';

		$subdata = array();
		foreach (glob($this->prevDir . '/*/logs/phpunit.xml.speed') as $f) {
			$d = parse_csv_data($f);
			if (count($d) == 0)
				continue;

			$sum = 0;
	        foreach ($d as $row) {
				$sum += $row['memory'];
			}
			$subdata[] = $sum / count($d);
		}

		$sum = 0;
        foreach ($data as $row) {
			$sum += $row['memory'];
		}
		$subdata[] = $sum / count($data);

        $this->data['Memory'] = new ezcGraphArrayDataSet($subdata);

        foreach ($this->data as $k => $v) {
            $this->data[$k]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$k] as $key => $v2) {
                $this->data[$k]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }
	}
}

function parse_csv_data($fn) {
    $data = explode("\n", file_get_contents($fn));
    $subdata = array();
    foreach ($data as $row) {
	    $row = explode("\t", $row);
        if (count($row) !== 3)
    	    continue;

        list($class, $method) = explode(":", $row[0], 2);
		if (empty($class))
			continue;

		$subdata[] = array(
			'class' => $class,
			'method' => $method,
			'time' => $row[1],
			'memory' => $row[2]
		);
	}

	return $subdata;
}

if (file_exists($csv) && filesize($csv) > 0) {

	$data = parse_csv_data($csv);
	if (count($data) > 0) {

		echo "Create PHPUnit resources graphs... \n";

		echo "+ PHPUnit time graph... ";
		$chart = new TimeBarChart();
		$chart->setInput($data);
		$fileName = $dir . '/phpunit.time.svg'; 
		echo $fileName . '... ';
		try {
			$chart->render(1024, 768, $fileName);
			echo "done\n";
		} catch (Exception $exception) {
			echo "failed\n";
		}

		echo "+ PHPUnit memory graph... ";
		$chart = new MemoryBarChart();
		$chart->setInput($data);
		$fileName = $dir . '/phpunit.memory.svg';
		echo $fileName . '... ';
		try {
			$chart->render(1024, 768, $fileName);
			echo "done\n";
        } catch (Exception $exception) {
            echo "failed\n";
        }

		if ($prevDir) {

			echo "+ PHPUnit time history graph... ";
		    $chart = new TimePrevLineChart();
    		$chart->prevDir = $prevDir;
	    	$chart->setInput($data);
			$fileName = $dir . '/phpunit.history.time.svg';
		    echo $fileName . '... ';
    		$chart->render(390, 250, $fileName);
		    echo "done\n";

    		echo "+ PHPUnit memory history graph... ";
	    	$chart = new MemoryPrevLineChart();
	    	$chart->prevDir = $prevDir;
		    $chart->setInput($data);
    		$fileName = $dir . '/phpunit.history.memory.svg';
		    echo $fileName . '... ';
    		$chart->render(390, 250, $fileName);
	    	echo "done\n";

		}
	}
}
?>
