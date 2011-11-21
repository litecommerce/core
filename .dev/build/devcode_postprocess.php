<?php
// SVN $Id$

if (php_sapi_name() != 'cli')
	exit(1);

$silentMode = false;

if (empty($argv) && false !== array_search('silentMode', array_keys($argv))) {
	$silentMode = true;
}

$files = getPHPs('./');

echo "Trim developer blocks / files post-processing...\n";

foreach ($files as $f) {
	$f = substr($f, 2);

	$data = file_get_contents($f);

	if (preg_match('/@package\s+DEVCODE/Ss', $data)) {
		unlink($f);
		if (!$silentMode) {
			echo $f . "...removed\n";
		}
		continue;
	}

	$data = preg_replace("/\n[ ]*\/\/\s+DEVCODE.+\/\/\s+\/DEVCODE\s/USs", '', $data);

	file_put_contents($f, $data);
}

echo "    done\n";

/*
	Service functions
*/

function getPHPs($path) {
    $i = new RecursiveDirectoryIterator($path);
    $files = array();
    foreach ($i as $f) {
        if ($f->isFile() && preg_match('/\.php$/Ss', $f->getFilename())) {
            $files[] = $f->getPathname();

        } elseif ($f->isDir()) {
            $tmp = getPHPs($f->getPathname());
            if ($tmp)
                $files = array_merge($files, $tmp);
        }
    }

    return $files;
}
?>
