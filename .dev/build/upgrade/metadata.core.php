<?php

$options = getopt("v:");

if (!isset($options['v'])) {
	echo '-v argument is not defined' . PHP_EOL;
	exit (1);

} elseif (!preg_match('/^(\d+\.\d+)\.(\d+)$/Ss', $options['v'], $match)) {
    echo '-v argument has wrong format' . PHP_EOL;
    exit (1);
}

$data = array(
	'RevisionDate' => time(),
	'VersionMajor' => $match[1],
	'VersionMinor' => $match[2],
);

echo serialize($data);
exit (0);
