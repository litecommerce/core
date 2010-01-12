<?php

class Decorator
{
	const INFO_FILE         = 'file';
	const INFO_CLASS        = 'class';
	const INFO_EXTENDS      = 'extends';
	const INFO_IS_DECORATOR = 'is_decorator';

	const CLASS_PATTERN = '/^\s*(?:abstract\s+)?(?:class|interface)\s+([\w\d]+)(\s+extends\s+([\w\d]+)(\s+implements\s+([\w\d(?:\s\w),]+))?)?/mi';

	protected $configOptions = null;

	protected $dbHandler = null;

	protected $classesInfo = array();

	protected $classDecorators = array();

	protected $activeModules = null;

	protected $moduleDependencies = null;

	protected $modulePriorities = null;


	protected function getClassByPath($path)
	{
		return str_replace(LC_DS, '_', $path);
	}

	protected function getFileByClass($class)
	{
		return str_replace('_', LC_DS, $class) . '.php';
	}

	protected function getDependenciesErrorText(array $dependencies)
	{
		$text = 'Class decorator is unable to resolve the following dependencies:<br /><br />' . "\n\n";
		foreach ($dependencies as $module => $dependedModules) {
			$text .= '<strong>' . $module . '</strong>: ' . implode (', ', $dependedModules) . '<br />' . "\n";
		}

		return $text;
	}

	protected function parseClassFile($class, array $info)
	{
		$content = file_get_contents(LC_CLASSES_DIR . $info[self::INFO_FILE]);

		return $content;
	}

	protected function prepareFileContent($class, array $info)
	{
		return $this->parseClassFile(empty($info[self::INFO_CLASS]) ? $class : $info[self::INFO_CLASS], $info);
	}

	protected function isModuleController($class)
	{
		return preg_match('/XLite_Module_[\w\d]+_Controller_?[\w\d_]*/', $class);
	}

	protected function prepareModuleController($class)
	{
		return preg_replace('/XLite_(Module_[\w\d]+_)Controller(_?[\w\d_]*)/', 'XLite_Controller$2', $class);
	}

	protected function isDecorator($implements)
	{
		return in_array('XLite_Base_IDecorator', explode(',', str_replace(' ', '', trim($implements))));
	}

	protected function getConfigOptions($section = '')
	{
		if (is_null($this->configOptions)) {
			$this->configOptions = funcParseConfgFile($section);
		}

		return $this->configOptions;
	}

	/**
     * Prepare MySQL connection string
     *
     * @param array $options MySQL credentials
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getConnectionString(array $options)
    {
		$dsnFields = array(
			'server'      => 'hostspec',
			'port'        => 'port',
			'unix_socket' => 'socket',
			'dbname'      => 'database',
		);
		$dsnString = array();

		foreach ($dsnFields as $pdoOption => $lcOption) {
			if (!empty($options[$lcOption])) {
				$dsnString[] = $pdoOption . '=' . $options[$lcOption];
			}
		}

		return 'mysql:' . implode(';', $dsnString);
	}

	protected function connectToDb()
	{
		$options = $this->getConfigOptions('database_details');

		$user     = isset($options['username']) ? $options['username'] : '';
        $password = isset($options['password']) ? $options['password'] : '';

		// PDO flags using for connection
		$connectionParams = array(
			PDO::ATTR_AUTOCOMMIT               => true,
			PDO::ATTR_ERRMODE                  => PDO::ERRMODE_SILENT,
			PDO::ATTR_PERSISTENT               => false,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		);

		return new PDO($this->getConnectionString($options), $user, $password, $connectionParams);
	}

	protected function getDbHandler()
	{
		if (is_null($this->dbHandler)) {
			$this->dbHandler = $this->connectToDb();
		}

		return $this->dbHandler;
	}

	protected function query($sql, $options = PDO::FETCH_ASSOC)
	{
		return $this->getDbHandler()->query($sql)->fetchAll($options);
	}

	protected function isCacheDirExists()
	{
		return (file_exists(LC_CLASSES_CACHE_DIR) && is_dir(LC_CLASSES_CACHE_DIR) && is_readable(LC_CLASSES_CACHE_DIR));
	}

	protected function isDeveloperMode()
	{
		$name = 'developer_mode';

		$result = $this->query(
			'SELECT name, value FROM xlite_config WHERE category = \'General\' AND name = \'' . $name . '\'',
			PDO::FETCH_COLUMN | PDO::FETCH_UNIQUE
		);

		return isset($result[$name]) ? $result[$name] : false;
	}

	protected function isNeedRebuild()
	{
		// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!11
		return true;

		return (!$this->isCacheDirExists() || $this->isDeveloperMode());
	}

	protected function checkFile($filePath)
	{
		$pathInfo = pathinfo($filePath);

		return !empty($pathInfo['extension']) && 'php' === strtolower($pathInfo['extension']); 
	}

	protected function getModuleNameByClassName($className)
	{
		return preg_match('/XLite_Module_(\w+)(_|$)/U', $className, $matches) ?
			(('Abstract' === $matches[1]) ? null : $matches[1]) : null;
	}

	protected function getClassInfo($filePath)
	{
		$result = array('', '', '');

		if (preg_match(self::CLASS_PATTERN, file_get_contents($filePath), $matches)) {
			foreach (array(1, 3, 5) as $index => $key) {
				$result[$index] = isset($matches[$key]) ? $matches[$key] : '';
			}
		}

		return $result;
	}

	protected function getActiveModules()
	{
		if (is_null($this->activeModules)) {
			$this->activeModules = $this->query('SELECT name FROM xlite_modules WHERE enabled = \'1\'', PDO::FETCH_COLUMN);
		}

		return $this->activeModules;
	}

	protected function isActiveModule($moduleName)
	{
		return is_null($moduleName) || in_array($moduleName, $this->getActiveModules());
	}

	protected function getModuleDependencies()
	{
		if (is_null($this->moduleDependencies)) {

			$this->moduleDependencies = array();

			require_once LC_LIB_DIR . 'Base.php';
			require_once LC_LIB_DIR . 'Model' . LC_DS . 'Abstract.php';
			require_once LC_LIB_DIR . 'Model' . LC_DS . 'Module.php';
			require_once LC_MODULES_DIR . 'Abstract.php';

			foreach ($this->getActiveModules() as $module) {
				require_once LC_MODULES_DIR . $module . LC_DS . 'Main.php';
				$this->moduleDependencies[$module] = call_user_func(array('XLite_Module_' . $module . '_Main', 'getDependencies'));
			}
		}

		return $this->moduleDependencies;
	}

	protected function calculateModulePriorities(array $dependencies, array $levelDependencies = array(), $level = 0)
	{
		$priorities = array();
		$subLevelDependencies = $levelDependencies;

		$isChanged = empty($dependencies);

		foreach ($dependencies as $module => $dependendModules) {

			if (array() === array_diff($dependendModules, $levelDependencies)) {
				$priorities[$module] = $level;
				unset($dependencies[$module]);
				$subLevelDependencies[] = $module;
				$isChanged = true;
			}
		}

		if (!$isChanged) {
			die ($this->getDependenciesErrorText($dependencies));
		}

		return array_merge(
			$priorities,
			empty($dependencies) ? array() : $this->calculateModulePriorities($dependencies, $subLevelDependencies, ++$level)
		);
	}

	protected function getModulePriority($moduleName)
	{
		if (is_null($this->modulePriorities)) {
			$this->modulePriorities = $this->calculateModulePriorities($this->getModuleDependencies());
		}

		return isset($this->modulePriorities[$moduleName]) ? $this->modulePriorities[$moduleName] : 0;
	}

	protected function createClassTree()
	{
		$fileNamePattern = '/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i';

		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_CLASSES_DIR)) as $fileInfo) {

			if ($fileInfo->isFile()) {
				$filePath = $fileInfo->getPathname();

				if ($this->checkFile($filePath)) {

					list($class, $extends, $implements) = $this->getClassInfo($filePath);

					if (!empty($class) && $this->isActiveModule($this->getModuleNameByClassName($class))) {

						$relativePath = preg_replace($fileNamePattern, '$1.php', $filePath);
						if (isset($this->classesInfo[$class])) {
							die ('Class "' . $class . '" is already defined in file "' . $relativePath . '"');
						}

						if (empty($extends) || $this->isActiveModule($this->getModuleNameByClassName($extends))) {

							/*if (!empty($class) && $this->isModuleController($class) && !$this->isDecorator($implements)) {
	                            $class = $this->prepareModuleController($class);
    	                    }*/

							$this->classesInfo[$class] = array(
								self::INFO_FILE         => $relativePath,
								self::INFO_EXTENDS      => $extends,
								self::INFO_IS_DECORATOR => $this->isDecorator($implements),
							);

							/*if (!empty($extends) && !empty($implements) && $this->isDecorator($implements)) {

								if (!empty($extends) && $this->isModuleController($extends) && !$this->isDecorator($implements)) {
		                            $extends = $this->prepareModuleController($extends);
        		                }

								if (!isset($this->classDecorators[$extends])) {
		                            $this->classDecorators[$extends] = array();
        			            }
                    		    $this->classDecorators[$extends][$class] = $this->getModulePriority($module);
							}*/
						}
					}
				}
			}
		}

		// print_r($this->classesInfo);die;
//        print_r($this->classDecorators);die;
						

















					// $relativePath = preg_replace($pattern, '$1', $filePath);
					// $className    = $this->getClassByPath($relativePath);

/*					$moduleName     = $this->getModuleNameByClassName($class);
					$isModuleActive = is_null($moduleName) ? false : $this->isActiveModule($moduleName);

					if (is_null($moduleName) || $isModuleActive) {

						/*$classesForCheck = array(&$class, &$extends);

						foreach ($classesForCheck as &$className) {
							if (!empty($className) && $this->isModuleController($className) && !$this->isDecorator($implements)) {
								if ('XLite_Module_GreetVisitor_Controller_Customer_Main' === $className) {
									var_dump($implements);die;
									var_dump($this->isDecorator($implements));die;
								}
								$className = $this->prepareModuleController($className);
							}
						}*/

/*						if (!empty($class) && $this->isModuleController($class) && !$this->isDecorator($implements)) {
							$class = $this->prepareModuleController($class);
						}

						$this->classesInfo[$class] = array(
							self::INFO_FILE => preg_replace($fileNamePattern, '$1.php', $filePath),
						);
					}

					$moduleName     = $this->getModuleNameByClassName($extends);
                    $isModuleActive = $isModuleActive && (is_null($moduleName) ? false : $this->isActiveModule($moduleName));

					if ($isModuleActive && !empty($extends) && !empty($implements) && $this->isDecorator($implements)) { 
						if (!isset($this->classDecorators[$extends])) {
							$this->classDecorators[$extends] = array();
						}
						$this->classDecorators[$extends][$class] = $this->getModulePriority($moduleName);
					}
					}
				}
			}
		}*/

		/*foreach ($this->classDecorators as $class => $decorators) {

			arsort($decorators, SORT_NUMERIC);
			$decorators = array_keys($decorators);

			$decorated    = array_shift($decorators);
			$currentClass = $class;

			foreach ($decorators as $decorator) {
				$this->classesInfo[$currentClass][self::INFO_CLASS]   = $decorated;
				$this->classesInfo[$currentClass][self::INFO_EXTENDS] = $decorator;

				$currentClass = $decorated;
				$decorated    = $decorator;
			}

			$this->classesInfo[$currentClass][self::INFO_CLASS] = $class;
		}*/
	}

	protected function normalizeModuleControllerNames()
	{
		$normalized = array();

		foreach ($this->classesInfo as $class => $info) {

			if (!empty($class) && $this->isModuleController($class) && !$info[self::INFO_IS_DECORATOR]) {

				$newClass = $this->prepareModuleController($class);

				if (isset($this->classesInfo[$newClass])) {
					die (
						'Module "' . $this->getModuleNameByClassName($class) 
						. '" has defined controller class "' . $class 
						. '" which does not decorate any other one and has an ambigous name'
					);
				}

				$this->classesInfo[$newClass] = array_merge($info, array(self::INFO_CLASS => $newClass));
				unset($this->classesInfo[$class]);
				$normalized[$class] = $newClass;
			}
		}

		foreach ($this->classesInfo as $class => $info) {

			if (isset($normalized[$info[self::INFO_EXTENDS]])) {
				$this->classesInfo[$class][self::INFO_EXTENDS] = $normalized[$info[self::INFO_EXTENDS]];
			}
		}
	}

	protected function createDecoratorTree()
	{
		foreach ($this->classesInfo as $class => $info) {

			if ($info[self::INFO_IS_DECORATOR]) {

				if (!isset($this->classDecorators[$info[self::INFO_EXTENDS]])) {
					$this->classDecorators[$info[self::INFO_EXTENDS]] = array();
				}

				$this->classDecorators[$info[self::INFO_EXTENDS]][$class] = 
					$this->getModulePriority($this->getModuleNameByClassName($class));
			}

			unset($this->classesInfo[$class][self::INFO_EXTENDS]);
			unset($this->classesInfo[$class][self::INFO_IS_DECORATOR]);
		}
	}

	protected function updateClassTree()
	{
		foreach ($this->classDecorators as $class => $decorators) {

			arsort($decorators, SORT_NUMERIC);
            $decorators = array_keys($decorators);

			$decorated    = array_shift($decorators);
            $currentClass = $class;

			foreach ($decorators as $decorator) {
                $this->classesInfo[$currentClass][self::INFO_CLASS]   = $decorated;
                $this->classesInfo[$currentClass][self::INFO_EXTENDS] = $decorator;

                $currentClass = $decorated;
                $decorated    = $decorator;
            }

            $this->classesInfo[$currentClass][self::INFO_CLASS] = $class;
		}
	}

	protected function writeFiles()
	{
		// print_r($this->classesInfo);die;

		foreach ($this->classesInfo as $class => $info) {

			$fileName = LC_CLASSES_CACHE_DIR . $this->getFileByClass($class);
			$dirName  = dirname($fileName);

			if (!file_exists($dirName) || !is_dir($dirName)) {
				mkdirRecursive($dirName, 0755);
			}

			// echo $info[self::INFO_FILE] . "<br />";

			file_put_contents($fileName, $this->prepareFileContent($class, $info));
			chmod($fileName, 0644);
		}
	}
	

	public function rebuildCache()
    {
		if ($this->isNeedRebuild()) {

			// Trying to create folder if not exists
			/*if (!$this->isCacheDirExists() && !@mkdir(LC_CLASSES_CACHE_DIR, 0755)) {
				die ('Unable to create classes cache directory');
			}*/

			// var_dump($this->getModulePriorities($this->getModuleDependencies()));die;

			// print_r($this->getModuleDependencies());die;

			$this->createClassTree();
			$this->normalizeModuleControllerNames();

//print_r($this->classesInfo);die;

			$this->createDecoratorTree();
			$this->updateClassTree();
			$this->writeFiles();

			print_r($this->classesInfo);die;
			// print_r($this->classDecorators);die;
		}
    }

	public function __destruct()
	{
		$this->dbHandler = null;
	}
}

$decorator = new Decorator();
$decorator->rebuildCache();
$decorator = null;

//var_dump(class_exists('XLite_Model_Abstract', false));


// echo 2;die;
