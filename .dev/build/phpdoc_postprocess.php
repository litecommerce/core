<?php
// SVN $Id$

if (php_sapi_name() != 'cli')
	exit(1);

array_shift($_SERVER['argv']);

if (count($_SERVER['argv']) == 0)
	exit(2);

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/
$url = trim(array_shift($_SERVER['argv']));

if (preg_match('/^https?:\/\/.+\/$/', $url))
	exit(3);

$prefix = 'XPay';

$files = getPHPs('./');

echo "API documentation post-processing...\n";

foreach ($files as $f) {
	$f = substr($f, 2);
	echo $f . '... ';

	$data = file($f);

	$class_see = false;
	foreach ($data as $k => $v) {
		if (preg_match('/____file_see____/S', $v)) {
			$see = getFileSee($data, $k, $f);
			if ($see) {
				$data[$k] = str_replace('____file_see____', $see, $data[$k]);
			}

		} elseif (preg_match('/____class_see____/S', $v)) {
            $see = getClassSee($data, $k, $f);
            if ($see) {
				$class_see = $see;
                $data[$k] = str_replace('____class_see____', $see, $data[$k]);
            }

        } elseif (preg_match('/____const_see____/S', $v) && $class_see) {
            $see = getConstSee($data, $k, $f, $class_see);
            if ($see) {
                $data[$k] = str_replace('____const_see____', $see, $data[$k]);
            }

        } elseif (preg_match('/____var_see____/S', $v) && $class_see) {
            $see = getVarSee($data, $k, $f, $class_see);
            if ($see) {
                $data[$k] = str_replace('____var_see____', $see, $data[$k]);
            }

        } elseif (preg_match('/____func_see____/S', $v) && $class_see) {
            $see = getFuncSee($data, $k, $f, $class_see);
            if ($see) {
                $data[$k] = str_replace('____func_see____', $see, $data[$k]);
            }
		}
	}

	file_put_contents($f, implode("", $data));
	echo "done\n";
}

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

function getPrevField(&$data, $k, $name) {
	for ($i = $k - 1; $i > 0; $i--) {
		if (preg_match('/@' . $name . '\s+(\S+)\s*$/S', $data[$i], $m))
			return $m[1];
	}

	return false;
}

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/X-Payments/Model/_lib---XPay---Model---SessionData.php.html
function getFileSee(&$data, $k, $fn) {
	global $url;

    $package = getPrevField($data, $k, 'package');
    $subpackage = getPrevField($data, $k, 'subpackage');

	if (!$package || !$subpackage)
		return false;

	return $url . '/' . $package . '/' . $subpackage . '/_' . str_replace('/', '---', $fn) . '.html';
}

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/X-Payments/Model/XPay_Model_SessionData.html
function getClassSee(&$data, $k, $fn) {
    global $url, $prefix;

    $package = getPrevField($data, $k, 'package');
    $subpackage = getPrevField($data, $k, 'subpackage');

    if (!$package || !$subpackage)
        return false;

    return $url . '/' . $package . '/' . $subpackage . '/' . $prefix . '_' . $subpackage . '_' . str_replace('.php', '.html', basename($fn));
}

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/X-Payments/Model/XPay_Model_SessionData.html#$isIterator
function getVarSee(&$data, $k, $fn, $class_see) {
	for ($i = $k + 2; $i < $k + 6; $i++) {
		if (preg_match('/^\s+(?:public|protected|private)\s+\$(\S+)\s+=\s+/S', $data[$i], $m)) {
			return $class_see . '#' . $m[1];
		}
	}

	return false;
}

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/X-Payments/Model/XPay_Model_SessionData.html#$isIterator
function getFuncSee(&$data, $k, $fn, $class_see) {
    for ($i = $k + 2; $i < $k + 6; $i++) {
        if (preg_match('/^\s+(?:public|protected|private)\s+function\s+(\S+)\(/S', $data[$i], $m)) {
            return $class_see . '#' . $m[1];
        }
    }

    return false;
}

// http://xcart2-530.crtdev.local/~max/xpayments/build/api/X-Payments/Model/XPay_Model_SessionData.html#$isIterator
function getConstSee(&$data, $k, $fn, $class_see) {
    for ($i = $k + 2; $i < $k + 6; $i++) {
        if (preg_match('/^\s+const\s+(\S+)\s+=\s+/S', $data[$i], $m)) {
            return $class_see . '#' . $m[1];
        }
    }

    return false;
}

?>
