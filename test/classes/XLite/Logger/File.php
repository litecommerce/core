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
 * @subpackage Logger
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once LC_EXT_LIB_DIR . 'Log.php';

/**
 * File-based logger
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Logger_File extends Log
{

    /**
     * Log file header
     */
    const DEFAULT_CONTENT = '<?php die(); /* WARNING: Do not change this line! */ ?>';    
    
    /**
     * File name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $filename = '';

    /**
     * File pointer 
     * 
     * @var    resource
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fp = null;

    /**
     * Opened flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $opened = false;

    /**
     * Constructor
     * 
     * @param string  $logName Log name
     * @param string  $ident   Identifier
     * @param boolean $conf    Configuration
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($logName, $ident = '', $conf = false)
    {
        $filenamePattern = '/\.php$/';
        if (!preg_match('/\.php$/', $logName)) {
            $logName .= '.php';
        }

        $this->filename = $logName;
        $this->ident = $ident;
    }
    
    /**
     * Open log file
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function open()
    {
        if (!$this->opened) {
            $addContent = !file_exists($this->filename);

            $this->fp = @fopen($this->filename, 'a');
            $this->opened = true;

            if ($addContent && $this->fp) {
                fwrite($this->fp, self::DEFAULT_CONTENT . "\n");
            }
        }
    }
    
    /**
     * Close log file
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function close()
    {
        if ($this->opened) {
            if ($this->fp) {
                fclose($this->fp);
            }

            $this->opened = false;
        }
    }
    
    /**
     * Add log 
     * 
     * @param string $message  Message
     * @param string $priority Priority code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function log($message, $priority)
    {
        if (!$this->opened) {
            $this->open();
        }
        
        $entry = sprintf(
            '%s %s [%s] %s' . "\n",
            strftime('%b %d %T'),
            $this->ident,
            $priority,
            $message
        );

        if ($this->fp) {
            fwrite($this->fp, $entry);
        }
    }
}
