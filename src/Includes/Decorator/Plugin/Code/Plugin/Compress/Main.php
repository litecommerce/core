<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace Includes\Decorator\Plugin\Code\Plugin\Compress;

/**
 * Compress 
 */
class Main extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Execute certain hook handler
     *
     * @return void
     */
    public function executeHookHandler()
    {
        if ($this->isCacheEnabled()) {

            // Assemble one file
            $path = $this->getPath();

            file_put_contents($path, '<' . '?php' . PHP_EOL);

            foreach ($this->getClassesQuery() as $class) {
                $f = $this->detectClassPath($class);
                if ($f) {
                    $content = @file_get_contents($f);
                    if ($content) {
                        file_put_contents(
                            $path,
                            '// ' . $f . PHP_EOL . substr($content, 5),
                            FILE_APPEND
                        );
                    }
                }
            }

            // Compress code
            $php = func_find_executable('php');
            if ($php && function_exists('exec')) {
                $return = array();
                $result = 0;
                exec($php . ' -w ' . escapeshellarg($path), $return, $result);
                if (0 == $result) {
                    file_put_contents($path, implode(PHP_EOL, $return));
                }
            }
        }
    }

    /**
     * Detect class repository path 
     * 
     * @param string $class Class name
     *  
     * @return string
     */
    protected function detectClassPath($class)
    {
        $path = null;

        if (0 === strpos($class, LC_NAMESPACE)) {
            $path = LC_DIR_CACHE_CLASSES . str_replace('\\', LC_DS, $class) . '.php';

        } elseif (0 === strpos($class, LC_NAMESPACE_INCLUDES)) {
            $path = LC_DIR_ROOT . str_replace('\\', LC_DS, $class) . '.php';

        } elseif (0 === strpos($class, 'Doctrine')) {
            $path = LC_DIR_LIB . str_replace('\\', LC_DS, $class) . '.php';
        }

        return $path;
    }

    /**
     * Get normalized classes query 
     * 
     * @return array
     */
    protected function getClassesQuery()
    {
        $result = array();
        foreach ($this->defineClassQuery() as $class) {
            $parents = array($class);
            $parent = new \ReflectionClass($class);
            do {
                $parent = $parent->getParentClass();
                if ($parent) {
                    $parents[] = $parent->getName();
                }

            } while ($parent);

            foreach (array_reverse($parents) as $class) {
                $result[$class] = true;
            }
        }

        return array_keys($result);
    }

    /**
     * Define class query 
     * 
     * @return array
     */
    protected function defineClassQuery()
    {
        return array(
            'Includes\DataStructure\Cell',
            'Includes\Utils\AUtils',
            'Includes\Utils\ConfigParser',
            'Includes\Utils\FileManager',
            'Includes\Utils\URLManager',
            'Includes\Utils\Converter',
            'Includes\ErrorHandler',
            'Includes\SafeMode',
            'Includes\Decorator\ADecorator',
            'Includes\Decorator\Utils\AUtils',
            'Includes\Decorator\Utils\CacheManager',
            'Includes\Utils\ModulesManager',
            'Includes\Utils\ArrayManager',
            'Includes\Utils\Database',
            'Includes\Utils\Operator',

            'Doctrine\DBAL\Types\Type',
            'Doctrine\DBAL\Types\StringType',
            'Doctrine\DBAL\Types\DecimalType',
            'Doctrine\DBAL\Types\IntegerType',
            'Doctrine\Common\Annotations\Annotation',
            'Doctrine\ORM\Query\AST\Node',
            'Doctrine\ORM\Query\AST\Functions\FunctionNode',
            'Doctrine\DBAL\Platforms\AbstractPlatform',
            'Doctrine\DBAL\Schema\AbstractSchemaManager',
            'Doctrine\DBAL\Connection',
            'Doctrine\DBAL\Statement',
            'Doctrine\ORM\EntityRepository',
            'Doctrine\Common\Cache\CacheProvider',
            'Doctrine\ORM\Mapping\Driver\DriverChain',
            'Doctrine\Common\Version',
            'Doctrine\Common\Annotations\AnnotationRegistry',
            'Doctrine\Common\Annotations\SimpleAnnotationReader',
            'Doctrine\Common\Annotations\DocParser',
            'Doctrine\Common\Lexer',
            'Doctrine\Common\Annotations\DocLexer',
            'Doctrine\Common\Annotations\Annotation\Target',
            'Doctrine\Common\Annotations\CachedReader',
            'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'Doctrine\ORM\EntityManager',
            'Doctrine\DBAL\DriverManager',
            'Doctrine\Common\EventManager',
            'Doctrine\DBAL\Query\Expression\ExpressionBuilder',
            'Doctrine\ORM\Mapping\ClassMetadataFactory',
            'Doctrine\ORM\UnitOfWork',
            'Doctrine\ORM\Proxy\ProxyFactory',
            'Doctrine\DBAL\Driver\PDOConnection',
            'Doctrine\DBAL\Driver\PDOStatement',
            'Doctrine\DBAL\Events',
            'Doctrine\ORM\Events',
            'Doctrine\ORM\Mapping\ClassMetadataInfo',
            'Doctrine\ORM\Mapping\ClassMetadata',
            'Doctrine\ORM\Id\AbstractIdGenerator',
            'Doctrine\ORM\Id\IdentityGenerator',
            'Doctrine\Common\Persistence\Mapping\RuntimeReflectionService',
            'Doctrine\ORM\Query\Expr\From',
            'Doctrine\ORM\Query\Expr\Base',
            'Doctrine\ORM\Query\Expr\Composite',
            'Doctrine\ORM\Query\Expr\Andx',
            'Doctrine\ORM\Query\ParameterTypeInferer',
            'Doctrine\ORM\AbstractQuery',
            'Doctrine\ORM\Query',
            'Doctrine\ORM\Query\ParserResult',
            'Doctrine\ORM\Query\Exec\AbstractSqlExecutor',
            'Doctrine\ORM\Query\Exec\SingleTableDeleteUpdateExecutor',
            'Doctrine\ORM\Query\ResultSetMapping',
            'Doctrine\DBAL\SQLParserUtils',
            'Doctrine\Common\Util\Inflector',
            'Doctrine\ORM\Persisters\BasicEntityPersister',
            'Doctrine\DBAL\LockMode',
            'Doctrine\ORM\Query\FilterCollection',
            'Doctrine\ORM\Internal\Hydration\AbstractHydrator',
            'Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator',
            'Doctrine\ORM\Internal\CommitOrderCalculator',
            'Doctrine\Common\EventArgs',
            'Doctrine\ORM\Event\LifecycleEventArgs',
            'Doctrine\DBAL\Types\TextType',
            'Doctrine\ORM\PersistentCollection',
            'Doctrine\Common\Collections\ArrayCollection',
            'Doctrine\DBAL\Types\BooleanType',
            'Doctrine\DBAL\Types\BigIntType',
            'Doctrine\DBAL\Types\ArrayType',
            'Doctrine\ORM\Internal\Hydration\ObjectHydrator',

            'XLite\Base\IDecorator',
            'XLite\Logger',
            'XLite\Core\Auth',
            'XLite\Core\ColumnType\FixedString',
            'XLite\Core\ColumnType\Money',
            'XLite\Core\ColumnType\Uinteger',
            'XLite\Core\ColumnType\VarBinary',
            'XLite\Core\ConfigCell',
            'XLite\Core\Config',
            'XLite\Core\Connection',
            'XLite\Core\Converter',
            'XLite\Core\Database',
            'XLite\Core\Doctrine\Annotation\Behavior',
            'XLite\Core\Doctrine\Annotation\Purpose',
            'XLite\Core\Doctrine\IfFunction',
            'XLite\Core\FlexyCompiler',
            'XLite\Core\Handler',
            'XLite\Core\Layout',
            'XLite\Core\MySqlPlatform',
            'XLite\Core\MySqlSchemaManager',
            'XLite\Core\Operator',
            'XLite\Core\PDOMySqlDriver',
            'XLite\Core\Request',
            'XLite\Core\Session',
            'XLite\Core\Statement',
            'XLite\Core\TmpVars',
            'XLite\Core\TopMessage',
            'XLite\Core\TranslationDriver\ATranslationDriver',
            'XLite\Core\TranslationDriver\Gettext',
            'XLite\Core\TranslationDriver\Db',
            'XLite\Core\Translation',
            'XLite\Controller\Customer\ACustomer',
            'XLite\Model\AEntity',
            'XLite\Model\Base\I18n',
            'XLite\Model\Base\Translation',
            'XLite\Model\Base\Image',
            'XLite\Model\Profile',
            'XLite\Model\Address',
            'XLite\Model\CachingFactory',
            'XLite\Model\Cart',
            'XLite\Model\Category',
            'XLite\Model\CategoryTranslation',
            'XLite\Model\Config',
            'XLite\Model\Country',
            'XLite\Model\Currency',
            'XLite\Model\CurrencyTranslation',
            'XLite\Model\FormId',
            'XLite\Model\Inventory',
            'XLite\Model\LanguageLabel',
            'XLite\Model\LanguageLabelTranslation',
            'XLite\Model\Language',
            'XLite\Model\LanguageTranslation',
            'XLite\Model\Module',
            'XLite\Model\Membership',
            'XLite\Model\Product',
            'XLite\Model\ProductTranslation',
            'XLite\Model\Role',
            'XLite\Model\Role\Permission',
            'XLite\Model\Session',
            'XLite\Model\SessionCell',
            'XLite\Model\State',
            'XLite\Model\TmpVar',
            'XLite\Model\ViewList',
            'XLite\Model\WidgetParam\Bool',
            'XLite\Model\WidgetParam\Set',
            'XLite\Model\WidgetParam\Int',
            'XLite\Model\WidgetParam\String',
            'XLite\Model\WidgetParam\File',
            'XLite\Model\WidgetParam\Collection',
            'XLite\Model\QueryBuilder\AQueryBuilder',
            'XLite\Model\Repo\ARepo',
            'XLite\Model\Repo\Base\I18n',
            'XLite\Model\Repo\Base\Image',
            'XLite\Model\Repo\Base\Common',
            'XLite\Model\Repo\Address',
            'XLite\Model\Repo\Cart',
            'XLite\Model\Repo\Category',
            'XLite\Model\Repo\Config',
            'XLite\Model\Repo\Country',
            'XLite\Model\Repo\Currency',
            'XLite\Model\Repo\FormId',
            'XLite\Model\Repo\LanguageLabel',
            'XLite\Model\Repo\Language',
            'XLite\Model\Repo\Module',
            'XLite\Model\Repo\Product',
            'XLite\Model\Repo\Profile',
            'XLite\Model\Repo\Session',
            'XLite\Model\Repo\SessionCell',
            'XLite\Model\Repo\State',
            'XLite\Model\Repo\TmpVar',
            'XLite\Model\Repo\ViewList',
            'XLite\Module\AModule',
            'XLite\View\AView',
            'XLite\View\Controller',
            'XLite\View\Dialog',
            'XLite\View\Form\AForm',
            'XLite\View\FormField\AFormField',
            'XLite\View\Button\Submit',
            'XLite\View\Button\Regular',
            'XLite\View\ItemsList\AItemsList',
        );
    }

    /**
     * Get compress file path 
     * 
     * @return string
     */
    protected function getPath()
    {
        return LC_DIR_CACHE_CLASSES . 'core.php';
    }

    /**
     * Check - code compressed cache enabled or not
     * 
     * @return boolean
     */
    protected function isCacheEnabled()
    {
        return !\Includes\Utils\ConfigParser::getOptions(array('performance', 'developer_mode'));
    }
}
