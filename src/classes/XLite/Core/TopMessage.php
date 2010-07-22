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

namespace XLite\Core;

/**
 * Top message
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TopMessage extends \XLite\Base\Singleton
{
    /**
     * Message types 
     */

    const INFO    = 'info';
    const WARNING = 'warning';
    const ERROR   = 'error';

    /**
     * Message fields 
     */

    const FIELD_TEXT = 'text';
    const FIELD_TYPE = 'type';


    /**
     * Types list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $types = array(
        self::INFO,
        self::WARNING,
        self::ERROR,
    );

    /**
     * Current messages 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $messages = array();

    /**
     * Constructor
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
        $this->messages = $this->getMessages();
        $this->clear();
    }

    /**
     * Add message
     * 
     * @param string $text Message text
     * @param string $type Message type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function add($text, $type = self::INFO, $rawText = false)
    {
        $result = false;

        if (!empty($text)) {
            $text = strval($text);
            if (0 < strlen($text)) {

                if (!$rawText) {
                    $text = static::t($text);
                }

                if (!in_array($type, $this->types)) {
                    $type = self::INFO;
                }

                $messages = $this->getMessages();
                $messages[] = array(
                    self::FIELD_TEXT => $text,
                    self::FIELD_TYPE => $type,
                );
                $this->messages = $messages;
                \XLite\Model\Session::getInstance()->set('topMessages', $messages);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Add information-type message with additional translation arguments
     * 
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments
     * @param string $code      Language code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function addInfo($text, array $arguments = array(), $code = null)
    {
        static::getInstance()->add(static::t($text, $arguments, $code), self::INFO, true);
    }

    /**
     * Add warning-type message with additional translation arguments
     * 
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments
     * @param string $code      Language code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function addWarning($text, array $arguments = array(), $code = null)
    {
        static::getInstance()->add(static::t($text, $arguments, $code), self::WARNING, true);
    }

    /**
     * Add error-type message with additional translation arguments
     * 
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments
     * @param string $code      Language code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function addError($text, array $arguments = array(), $code = null)
    {
        static::getInstance()->add(static::t($text, $arguments, $code), self::ERROR, true);
    }

    /**
     * Add messages
     * 
     * @param array $text Message text
     * @param string $type Message type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addBatch(array $messages, $type = self::INFO)
    {
        $result = true;

        foreach ($messages as $message) {
            $result = $result && $this->add($message, $type);
        }

        return $result;
    }

    /**
     * Get messages 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMessages()
    {
        $messages = \XLite\Model\Session::getInstance()->get('topMessages');
        if (!is_array($messages)) {
            $messages = array();
        }

        return $messages;
    }

    /**
     * Get previous messages 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPreviousMessages()
    {
        return $this->messages;
    }

    /**
     * Clear list
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function clear()
    {
        \XLite\Model\Session::getInstance()->set('topMessages', array());
    }
}
