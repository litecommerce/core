<?php
/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/*
* $Id$
*/

define('DECORATION_POSTFIX', '__');

function func_is_php5() {
    global $xlite_php5;

    if (!isset($xlite_php5)) {
    	$xlite_php5 = version_compare(phpversion(),"5.0.0") >= 0;
    }
    return $xlite_php5;
}

function func_is_php53() {
    global $xlite_php53;

    if (!isset($xlite_php53)) {
        $xlite_php53 = version_compare(phpversion(),"5.3.0", ">=");
    }
    return $xlite_php53;
}

function func_is_clone_deprecated() {
    return true;
}

function &func_new($class) { // {{{

    if (class_exists($class)) {
        $result =& new $class;
    } else {
        $class = func_define_class(strtolower($class));
        if (!class_exists($class)) {
            $class = func_define_class($class);
            if (!class_exists($class)) {
                func_die("Class $class not found");
            }
        }
        $result =& new $class;
    }
    if (method_exists($result, 'constructor')) {
        $args = func_get_args();
        array_shift($args);
        call_user_func_array(array(&$result, 'constructor'), $args);
    }
    if (method_exists($result,'getInstanceByClass')) {
        $value = $result->get('class');
        $className = (!empty($value) ? substr(get_class($result),0,-1).$value : substr(get_class($result),0,-2));
        func_define_class(strtolower($className));
        $className .= DECORATION_POSTFIX;
        $result =& new $className;
        if (method_exists($result, 'constructor')) {
            $args = func_get_args();
            array_shift($args);
            call_user_func_array(array(&$result, 'constructor'), $args);
        }
    }

	global $xlite;
    if (!isset($xlite)) {
		$xlite = true;
	}
    return $result;
} // }}}

function func_class_exists($class) { // {{{
    global $xlite_class_files;
    return class_exists($class) || isset($xlite_class_files[strtolower($class)]);
} // }}}

function func_is_a($child, $parent) { // {{{
    $child = strtolower($child);
    $parent = strtolower($parent) . DECORATION_POSTFIX;
    func_define_class($child);
    $child .= DECORATION_POSTFIX;
    if (func_is_php5()) {
        $obj = (class_exists($child) ? new $child : new StdClass); 
        return is_a($obj, $parent);
    } else {   
        while ($child != $parent) {
            $child = get_parent_class($child);
            if(!$child) return false;
        }
        return true;
    }
} // }}}

function &func_get_instance($class, $param = null) { // {{{
    $class = strtolower($class);
    static $instances;
    if (!isset($instances)) {
        $instances = array();
    }
    if (!isset($instances[$class.':'.$param])) {
        $dclass = func_define_class($class);
        if (!class_exists($dclass)) {
            $dclass = func_define_class($class);
            if (!class_exists($dclass)) {
                func_die("Class $class not found");
            }
        }
        $instances[$class.':'.$param] =& new $dclass;
        if (method_exists($instances[$class.':'.$param], 'constructor')) {
            $instances[$class.':'.$param]->constructor($param);
        }
    }
    return $instances[$class.':'.$param];
} // }}}

function func_define_class($originalClass, $classesDir = 'classes/' /* debug */) { // {{{

    global $xlite_defined_classes, $xlite_class_deps, $xlite_class_files, $options, $xlite_class_decorators, $xlite_class_files_state;

    //file and directory permissions
    static $file_permission, $dir_permission;
    if(!isset($file_permission) || !isset($dir_permission)) {
        $su_mode = get_php_execution_mode();
        if($su_mode == 0) {
            $file_permission = isset($options['filesystem_permissions']['nonprivileged_permission_file']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_file'], 8, 10):0644;
            $dir_permission = isset($options['filesystem_permissions']['nonprivileged_permission_dir']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_dir'], 8, 10) : 0755;
        } else {
            $file_permission = isset($options['filesystem_permissions']['privileged_permission_file']) ? base_convert($options['filesystem_permissions']['privileged_permission_file'], 8, 10) : 0600;
            $dir_permission = isset($options['filesystem_permissions']['privileged_permission_dir']) ? base_convert($options['filesystem_permissions']['privileged_permission_dir'], 8, 10) : 0711;
        }
    }

    if (!isset($xlite_defined_classes[$originalClass])) {

        if (!isset($xlite_class_files[$originalClass])) {
            return $originalClass; // do not define
        }
        if (isset($xlite_class_deps[$originalClass]) && (!empty($xlite_class_deps[$originalClass]))) {
            foreach (explode(',', $xlite_class_deps[$originalClass]) as $depClass) {
                // define dependent classes
                $dependency_class = func_define_class($depClass, $classesDir);
                if (!class_exists($dependency_class)) {
                    $_error = "for class '$originalClass' unable to read dependency class: $depClass";
                    if ($GLOBALS['XLITE_SELF'] == "cart.php") {
                        func_shop_closed("Error: $_error");
                    } else {
                        func_die($_error);
                    }
                }
            }
        }
        if (isset($xlite_class_files_state[$originalClass])) {
            // the file has already been included and we get here
            // after subsequent func_add_decorator - we need to recompile
            // the class file.
            $xlite_class_files_state[$originalClass]++;
            $source = func_compile($xlite_class_files[$originalClass], $xlite_class_files_state[$originalClass], $classesDir);
            $source = preg_replace('/^<\\?(php)?/', "", $source);
            $source = preg_replace('/\\?>\$/', "", $source);
            eval($source);
        } else {
            // find the source file
            $file = $classesDir . $xlite_class_files[$originalClass];
            $compiledFile = $options["decorator_details"]["compileDir"] . $xlite_class_files[$originalClass];

            do {
                $retry = false;
                if (!file_exists($compiledFile) || filemtime($file) != filemtime($compiledFile)) {
                    $source = func_compile($xlite_class_files[$originalClass], 0, $classesDir);
                    mkdirRecursive(dirname($compiledFile), $dir_permission);
                    if($cfp = @fopen($compiledFile, 'wb')) {
                        fwrite($cfp, $source);
                        fclose($cfp);
                        @chmod($compiledFile, $file_permission);
                        @touch($compiledFile, filemtime($file));
                    }
                }
                $result = include_once $compiledFile;
                if (!$result) {
                    $_error = "unable to read class file: $compiledFile";
                    if ($GLOBALS['XLITE_SELF'] == "cart.php") {
                        func_shop_closed("Error: $_error");
                    } else {
                        if (func_is_locked("cache")) {
                            func_die("Error: $_error");
                        } else {
                            // retry generating the file:
                            $retry = true;
                        }
                    }
                }
            } while ($retry);
            $xlite_class_files_state[$originalClass] = 0;
        }
        $xlite_defined_classes[$originalClass] = $originalClass . DECORATION_POSTFIX . str_repeat('_', $xlite_class_files_state[$originalClass]);
        if (isset($xlite_class_decorators[$originalClass])) {
            foreach($xlite_class_decorators[$originalClass] as $decorator) {
                $xlite_defined_classes[$originalClass] = func_define_class($decorator, $classesDir);
            }
        }
    }
    return $xlite_defined_classes[$originalClass];
} // }}}

function func_compile($file, $num, $classesDir = 'classes/') { // {{{
    $dashes = DECORATION_POSTFIX . str_repeat('_', $num);
    // compile the class
    $source = file_get_contents($classesDir.$file);
    if (func_is_php5()) {
    	$source = str_replace("var $","public $", $source);
    }

    // Replace old style function clone() by &cloneObject() in LC for PHP 5.x version
    $source = str_replace('function clone()', "function &cloneObject()", $source);
    $source = str_replace('parent::clone()', "parent::_clone_deprecated()", $source);

    // 'Object' class extends nothing
    $patterns = $replacements = array();
    if (preg_match_all("/^class +(\w+) +extends +(\w+)/m", $source, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            list ($all, $cl, $ex) = $match;
            $exReplace = func_define_class(strtolower($ex), $classesDir);
            $patterns[] = "/^class +$cl +extends +$ex/m";
            $replacements[] = 'class ' . $cl . $dashes . ' extends ' . $exReplace;
        }
    }
    if (preg_match_all('/^class +(\w+) *$/m', $source, $matches, PREG_SET_ORDER)) { // classes that extend nothing
        foreach ($matches as $match) {
            list ($all, $cl) = $match;
            $patterns[] = "/^class +$cl *\$/m";
            $replacements[] = 'class ' . $cl . $dashes;
        }
    }
    if (func_is_php5()) {
        // Replace array_merge with func_array_merge to avoid the
        // warning message about the incorrect parameners of this function
        // in PHP 5.X
        $patterns[] = '/([^a-z0-9_]+)array_merge(\s*)\(/i';
        $replacements[] = '${1}func_array_merge${2}(';
    }
    return preg_replace($patterns, $replacements, $source);
} // }}}

function func_add_decorator($decorated, $decorator) { // {{{
    global $xlite_class_decorators, $xlite_class_files, $xlite_class_deps, $xlite_defined_classes;
    $decorated = strtolower($decorated);
    $decorator = strtolower($decorator);
    if (!isset($xlite_class_decorators[$decorated])) {
        $xlite_class_decorators[$decorated] = array($decorator);
    } else {
        $xlite_class_decorators[$decorated][] = $decorator;
    }
    // reset all classes declared in the same file as a class 
    // inherited from $decorated class
    $rebuildClasses[$decorated] = true;
    if (isset($xlite_defined_classes[$decorated])) {
    	unset($xlite_defined_classes[$decorated]);
    }
    $definedClasses = array_keys($xlite_defined_classes);
    foreach ($definedClasses as $defined) {
        foreach (explode(',', $xlite_class_deps[$defined]) as $dep) {
           if (isset($rebuildClasses[$dep])) {
				if (isset($xlite_defined_classes[$defined])) {
               		unset($xlite_defined_classes[$defined]);
               	}
               $rebuildClasses[$defined] = true;
           }
        }
    }
} // }}}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
