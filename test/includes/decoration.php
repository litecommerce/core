<?php

class Decorator
{
	const INFO_FILE    = 'file';
	const INFO_CLASS   = 'class';
	const INFO_EXTENDS = 'extends';

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
		$content = file_get_contents(LC_CLASSES_DIR . $this->getFileByClass($class));

		return $content;
	}

	protected function prepareFileContent($class, array $info)
	{
		return $this->parseClassFile(empty($info[self::INFO_CLASS]) ? $class : $info[self::INFO_CLASS], $info);
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
		return (!$this->isCacheDirExists() || $this->isDeveloperMode());
	}

	protected function checkFile($filePath)
	{
		$pathInfo = pathinfo($filePath);

		return !empty($pathInfo['extension']) && 'php' === strtolower($pathInfo['extension']); 
	}

	protected function getModuleNameByClassName($className)
	{
		return preg_match('/XLite_Module_(\w+)(_|$)/U', $className, $matches) ? $matches[1] : null;
	}

	protected function isContainsClass($filePath)
	{
		return preg_match('/class\s+([\w\d]+)[\s\n\r]+/', file_get_contents($filePath));
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
		return in_array($moduleName, $this->getActiveModules());
	}

	protected function getModuleDependencies()
	{
		if (is_null($this->moduleDependencies)) {
			$this->moduleDependencies = array();

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
		$pattern = '/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i';

		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_CLASSES_DIR)) as $fileInfo) {

			if ($fileInfo->isFile()) {
				$filePath = $fileInfo->getPathname();

				if ($this->checkFile($filePath) && $this->isContainsClass($filePath)) {

					$relativePath = preg_replace($pattern, '$1', $filePath);
					$className    = $this->getClassByPath($relativePath);

					$moduleName     = $this->getModuleNameByClassName($className);
					$isModuleActive = is_null($moduleName) ? false : $this->isActiveModule($moduleName);

					if (is_null($moduleName) || $isModuleActive) {
						$this->classesInfo[$className] = array();
					}

					if ($isModuleActive && in_array('XLite_Base_IDecorator', class_implements($className))) {

						$parentClass = get_parent_class($className);
						if (!isset($this->classDecorators[$parentClass])) {
							$this->classDecorators[$parentClass] = array();
						}
						$this->classDecorators[$parentClass][$className] = $this->getModulePriority($moduleName);
					}
				}
			}
		}

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
		foreach ($this->classesInfo as $class => $info) {

			$fileName = LC_CLASSES_CACHE_DIR . $this->getFileByClass($class);
			$dirName  = dirname($fileName);

			if (!file_exists($dirName) || !is_dir($dirName)) {
				mkdirRecursive($dirName, 0755);
			}

			file_put_contents($fileName, $this->prepareFileContent($class, $info));
			chmod($fileName, 0644);
		}
	}
	

	public function rebuildCache()
    {
		if ($this->isNeedRebuild()) {

			// Trying to create folder if not exists
			if (!$this->isCacheDirExists() && !@mkdir(LC_CLASSES_CACHE_DIR, 0755)) {
				die ('Unable to create classes cache directory');
			}

			// var_dump($this->getModulePriorities($this->getModuleDependencies()));die;

			// print_r($this->getModuleDependencies());die;

			$this->createClassTree();
			$this->writeFiles();

			// print_r($this->classesInfo);die;
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


echo 2;die;
