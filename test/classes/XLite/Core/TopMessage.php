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
 * Top message
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_TopMessage extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Message types 
     */
    const INFO    = 'info';
    const WARNING = 'warning';
    const ERROR   = 'error';


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
     * Method to access the singleton 
     * 
     * @return XLite_Core_TopMessage
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

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
    public function add($text, $type = self::INFO)
    {
        $result = false;

        if (is_scalar($text)) {
            $text = strval($text);
            if (0 < strlen($text)) {
                if (!in_array($type, $this->types)) {
                    $type = self::INFO;
                }

                $messages = $this->getMessages();
                $messages[] = array(
                    'text' => $text,
                    'type' => $type,
                );
                $this->session->set('topMessages', $messages);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Add messages
     * 
     * @param string $text Message text
     * @param string $type Message type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addBatch(array $messages, $type = self::INFO)
    {
        $result = false;

        foreach ($messages as $message) {
            $currentResult = $this->add($message, $type);
            $result = $result && $currentResult;
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
        $messages = $this->session->get('topMessages');
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
        $this->session->set('topMessages', array());
    }
}
