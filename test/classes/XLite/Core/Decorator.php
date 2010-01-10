<?php

class XLite_Core_Decorator extends XLite_Base implements XLite_Base_ISingleton
{
	protected $classDecorators = array();

	protected function isCacheDirExists()
	{
		return false;
		// return (file_exists(LC_CLASSES_CACHE_DIR) && is_dir(LC_CLASSES_CACHE_DIR) && is_readable(LC_CLASSES_CACHE_DIR));
	}

	protected function isNeedRebuild()
	{
		return (!$this->isCacheDirExists() || $this->config->get('General.developer_mode'));
	}

	protected function checkFile($filePath)
	{
		$pathInfo = pathinfo($filePath);

		return !empty($pathInfo['extension']) && 'php' === strtolower($pathInfo['extension']); 
	}

	protected function walkThroughPHPFiles()
	{
		$pattern = '/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i';

		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_CLASSES_DIR)) as $fileInfo) {

			if ($fileInfo->isFile()) {

				$filePath = $fileInfo->getPathname();

				if ($this->checkFile($filePath) && preg_match('/class\s+([\w\d]+)[\s\n\r]+/', file_get_contents($filePath), $matches)) {
					$className = str_replace(LC_DS, '_', preg_replace($pattern, '$1', $filePath));
					if (in_array('XLite_Base_IDecorator', class_implements($className))) {
						$parentClass = get_parent_class($className);
						if (!isset($this->classDecorators[$parentClass])) {
							$this->classDecorators[$parentClass] = array();
						}
						$this->classDecorators[$parentClass][$className] = 1;
					}
				}
			}
		}
	}

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function rebuildCache()
    {
		if ($this->isNeedRebuild()) {

			// Trying to create folder if not exists
			/*if (!$this->isCacheDirExists() && !@mkdir(LC_CLASSES_CACHE_DIR, 0755)) {
				$this->_die('Unable to create classes cache directory');
			}*/

			// $this->walkThroughPHPFiles();

			// print_r($this->classDecorators);die;
		}
    }
}
