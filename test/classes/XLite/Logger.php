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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Logger 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Logger extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Logger defaults 
     */
    const LOGGER_DEFAULT_TYPE = null;
    const LOGGER_DEFAULT_NAME = '/dev/null';
    const LOGGER_DEFAULT_LEVEL = LOG_DEBUG;
    const LOGGER_DEFAULT_IDENT = 'X-Lite';


    /**
     * Options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = array(
        'type'  => self::LOGGER_DEFAULT_TYPE,
        'name'  => self::LOGGER_DEFAULT_NAME,
        'level' => self::LOGGER_DEFAULT_LEVEL,
        'ident' => self::LOGGER_DEFAULT_IDENT
    );

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->options = array_merge(
            $this->options,
            XLite::getInstance()->getOptions('log_details')
        );
    }
    
    /**
     * Get class instance 
     * 
     * @return XLite_Logger
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Add log record
     * 
     * @param string $message Message
     * @param string $level   Level code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function log($message, $level = null)
    {
        require_once LC_EXT_LIB_DIR . 'Log.php';

        $dir = getcwd();
        chdir(LC_DIR);

        $logger = Log::singleton(
            $this->getType(),
            $this->getName(),
            $this->getIdent()
        );

        if (is_null($level)) {
            $level = $this->getLevel();
        }

        $logger->log($message, $level);

        chdir($dir);
    }

    /**
     * Get log type 
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getType()
    {
        return $this->options['type'];
    }

    /**
     * Get logger name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getName()
    {
        return $this->options['name'];
    }

    /**
     * Get logger identtificator 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIdent()
    {
        return $this->options['ident'];
    }

    /**
     * Get log level 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLevel()
    {
        $target = isset(XLite_Core_Request::getInstance()->target)
            ? XLite_Core_Request::getInstance()->target
            : XLite::TARGET_DEFAULT;

        $xself = isset($GLOBALS['XLITE_SELF'])
            ? basename($GLOBALS['XLITE_SELF'], '.php')
            : 'unknown';

        return $xself . ':' . $target;
    }
}
