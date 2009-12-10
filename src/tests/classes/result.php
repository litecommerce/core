<?php

echo "\nFinished\n";

if (count($result->failures()) || count($result->errors())) {
    ob_start();
    echo "\n\n". $suite->getName() . "\n";
    hr();
}

if (count($result->failures())) {
    echo "FAILURES:\n";
    dumpMessages($result->failures());
}

if (count($result->errors())) {
    echo "ERRORS:\n";
    dumpMessages($result->errors());
}

if (count($result->failures()) || count($result->errors())) {
    hr();
	$stderr = fopen("php://stderr", "w");
    fwrite($stderr, ob_get_contents());
	fclose($stderr);
}

function dumpMessages($errors) {
    foreach ($errors as $error) {
        echo $error->toString();
    }
}

function hr() {
    echo "-------------------------------\n";
}

?>
