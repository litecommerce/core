<?php

if (!isset($argv[1]) || !file_exists($argv[1])) {
    print PHP_EOL . "You should specify phpunit logs directory!" . PHP_EOL . "Usage: php merge_xml.php <logs_directory>" . PHP_EOL;
    die(1);
}

$dirname = $argv[1];
collectXmlOutput($dirname);

function collectXmlOutput($dirname)
{
    $dirname = realpath($dirname);
    $dirname = rtrim($dirname, '/');

    $tests = $assertions = $failures = $errors = $time = 0;

    $summary = shell_exec("cat $dirname/phpunit*.xml | grep AllTests");

    if (preg_match_all('/tests="(\d+)" assertions="(\d+)" failures="(\d+)" errors="(\d+)" time="([\d\.]+)"/Sm', $summary, $matches)) {
        $tests = array_sum($matches[1]);
        $assertions = array_sum($matches[2]);
        $failures = array_sum($matches[3]);
        $errors = array_sum($matches[4]);
        $time = array_sum($matches[5]);
    }

    //Collect xml output
    $writer = new XMLWriter();
    $uri = "$dirname/phpunit.xml";
    touch($uri);
    $uri = realpath($uri);
    if ($writer->openUri($uri)) {

        $writer->startDocument();
        $writer->startElement('testsuites');

        $writer->startElement('testsuite');
        $writer->writeAttribute('name', 'LiteCommerce - AllTests');
        $writer->writeAttribute('tests', $tests);
        $writer->writeAttribute('assertions', $assertions);
        $writer->writeAttribute('failures', $failures);
        $writer->writeAttribute('errors', $errors);
        $writer->writeAttribute('time', $time);

        foreach (glob("$dirname/phpunit.*.xml") as $filename) {
            print $filename;
            mergeXml($writer, $filename);
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();

    }
}

function mergeXml(XMLWriter $xmlWriter, $fileName)
{
    if (filesize($fileName) == 0)
        return;
    try {
        $reader = new XMLReader();
        $reader->open($fileName);

        while ($reader->read()) {
            if ($reader->name == 'testsuite') {
                if (strpos($reader->getAttribute('name'), 'AllTests')) {
                    $xmlWriter->writeRaw($reader->readInnerXml());
                    break;
                }
            }
        }
        $reader->close();
    }
    catch (\PEAR2\MultiErrors\Exception $ex) {
        echo PHP_EOL . "XML Merge error: " . $ex->getMessage() . PHP_EOL;
    }
}