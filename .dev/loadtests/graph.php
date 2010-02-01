<?php

// Input arguments
array_shift($_SERVER['argv']);
$jtl = array_shift($_SERVER['argv']);
$dir = array_shift($_SERVER['argv']);
$prevDir = count($_SERVER['argv']) > 0 ? array_shift($_SERVER['argv']) : false;

if (!file_exists($prevDir) || !is_dir($prevDir))
	$prevDir = false;

if ($prevDir) {
	$fileNamePrev = $dir . '/jmeter.metric.svg';
	$fileNamePrevBig = $dir . '/jmeter.history.svg';
}

$fileName = $dir . '/jmeter.svg';

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
            'JMeter',
            'jmeter',
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

class JTLSVGDriver extends ezcGraphSvgDriver {
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

class JTLLineChart extends phpucLineChart {
	public $xpath = null;
	public $threadName = null;

	public function setInput(phpucAbstractInput $input)
	{
		$this->driver = new JTLSVGDriver();

        $this->title        = 'JMeter : ' . $this->threadName;
        $this->yAxis->label = 'Request duration, msec';
        $this->xAxis->label = 'Requests ';

		$this->options->fillLines = false;

        $this->data = new ezcGraphChartDataContainer($this);

        $data = $this->getTimeData($this->xpath);

        foreach ($data as $label => $data) {
            $this->data[$label]         = new ezcGraphArrayDataSet($data);
            $this->data[$label]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$label] as $key => $v) {
                $this->data[$label]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }

			$this->data[$label] = new ezcGraphDataSetAveragePolynom($this->data[$label], polynom);
        }

		$this->legend->position = ezcGraph::RIGHT;
	}

	public function getTimeData($xpath) {
		$nodes = $xpath->query('//testResults/sampleResult');
		$data = array();
		foreach ($nodes as $node) {
			$threadName = preg_replace('/ \d+-\d+$/S', '', $node->attributes->getNamedItem('threadName')->nodeValue);
			if ($threadName != $this->threadName)
				continue;

			$label = $node->attributes->getNamedItem('label')->nodeValue;
			$len = intval($node->attributes->getNamedItem('time')->nodeValue);
			$time = $node->attributes->getNamedItem('timeStamp')->nodeValue;

			if (!isset($data[$label]))
				$data[$label] = array();

			$data[$label][$time] = $len;
		}

		foreach ($data as $label => $d) {
			asort($data[$label]);
		}

		foreach ($data as $label => $d) {
			$data[$label] = array_values($d);
		}

		return $data;
	}

}

class PrevJTLLineChart extends phpucLineChart {
	public $xpath = null;
	public $prevDir = null;

	public function setInput(phpucAbstractInput $input)
	{
		$this->driver = new JTLSVGDriver();

        $this->title        = $input->title;
        $this->yAxis->label = 'Avg. request duration, msec.';
        $this->xAxis->label = 'Build ';

        $this->data = new ezcGraphChartDataContainer($this);

        $data = $this->getTimeData();

        foreach ($data as $label => $data) {
            $this->data[$label]         = new ezcGraphArrayDataSet($data);
            $this->data[$label]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$label] as $key => $v) {
                $this->data[$label]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }

		$this->legend = false;
	}

	public function getTimeData() {
        $data = array();

		foreach (glob($this->prevDir . '/*/logs/JMeterResults.jtl') as $f) {
			$dom = new DOMDocument();
			if ($dom->load($f, LIBXML_NOERROR)) {
				$data[] = $this->calculateAvgTime(new DOMXPath($dom));
			}
		}

		$data[] = $this->calculateAvgTime($this->xpath);

		return array('Sum requests' => $data);
	}

	protected function calculateAvgTime(DOMXPath $xpath)
	{
    	$nodes = $xpath->query('//testResults/sampleResult');
        $sum = 0;
        $cnt = 0;
        foreach ($nodes as $node) {
            $sum += intval($node->attributes->getNamedItem('time')->nodeValue);
            $cnt++;
        }

        return round($sum / $cnt, 0);
	}
}

class HistoryJTLLineChart extends phpucLineChart {
	public $xpath = null;
	public $prevDir = null;

	protected $predata = array();
	protected $labels = array();

	public function setInput(phpucAbstractInput $input)
	{

		$this->driver = new JTLSVGDriver();

        $this->title        = 'JMeter history';
        $this->yAxis->label = 'Avg. request duration, msec.';
        $this->xAxis->label = 'Build ';

		$this->legend->position = ezcGraph::RIGHT;
		$this->options->fillLines = false;

        $this->data = new ezcGraphChartDataContainer($this);

        foreach ($this->getTimeData() as $label => $data) {
            $this->data[$label]         = new ezcGraphArrayDataSet($data);
            $this->data[$label]->symbol = ezcGraph::BULLET;

            foreach ($this->data[$label] as $key => $v) {
                $this->data[$label]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }
	}

	public function getTimeData() {
		$xpaths = array();
		$data = array();

		foreach (glob($this->prevDir . '/*/logs/JMeterResults.jtl') as $f) {
			$dom = new DOMDocument();
			if ($dom->load($f, LIBXML_NOERROR)) {
				$this->processXPath(new DOMXPath($dom));
			}
		}

		$this->processXPath($this->xpath);

		foreach ($this->labels as $label) {
			$data[$label] = array();
		}

		foreach ($this->predata as $d) {
			foreach ($this->labels as $label) {
				$data[$label][] = isset($d[$label]) ? $d[$label]['avg'] : false;
			}
		}

		return $data;
	}

	public function processXPath(DOMXPath $xpath) {
		$data = array();

		foreach ($xpath->query('//testResults/sampleResult') as $node) {
			$label = $node->attributes->getNamedItem('label')->nodeValue;
			$len = intval($node->attributes->getNamedItem('time')->nodeValue);

			if (!isset($data[$label])) {
				$data[$label] = array(
					'sum' => 0,
					'cnt' => 0,
				);
			}

			$data[$label]['sum'] += $len;
			$data[$label]['cnt']++;
		}

		foreach ($data as $label => $d) {
			$data[$label]['avg'] = round($d['sum'] / $d['cnt'], 0);

            if (!in_array($label, $this->labels)) {
                $this->labels[] = $label;
            }
		}

        $this->predata[] = $data;

		return $data;
	}

}


$input = new phpucJTLInput();

$dom = new DOMDOcument();
$dom->load($jtl);

$xpath = new DOMXPath($dom);

$input->processLog($xpath);

$nodes = $xpath->query('//testResults/sampleResult/@threadName');
$threadNames = array();
foreach ($nodes as $node) {
	$name = preg_replace('/ \d+-\d+$/S', '', $node->nodeValue);
	if (!in_array($name, $threadNames))
		$threadNames[] = $name;
}

echo "Create JMeter result graphs... \n";

foreach ($threadNames as $threadName) {
	echo ' + ' . $threadName . '... ';

	$chart = new JTLLineChart();
	$chart->xpath = $xpath;
	$chart->threadName = $threadName;
	$chart->setInput($input);

	$fn = str_replace(
		array(' '),
		array('-'),
		strtolower($threadName)
	);
	$fileName = $dir . '/jmeter-' . $fn . '.svg';

	echo $fileName . '... ';
	$chart->render(1024, 768, $fileName);

	echo "done\n";
}

if ($prevDir) {

	echo 'Create JMeter metrics graph... ';
    $chart = new PrevJTLLineChart();
    $chart->xpath = $xpath;
    $chart->prevDir = $prevDir;
    $chart->setInput($input);

    echo $fileNamePrev . '... ';
    $chart->render(390, 250, $fileNamePrev);
    echo "done\n";

    echo 'Create JMeter history graph... ';
	$chart = new HistoryJTLLineChart();
	$chart->xpath = $xpath;
	$chart->prevDir = $prevDir;
	$chart->setInput($input);

	echo $fileNamePrevBig . '... ';
	$chart->render(1024, 768, $fileNamePrevBig);
	echo "done\n";
}

?>
