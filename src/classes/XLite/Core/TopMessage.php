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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Core;

/**
 * Top message
 *
 * @see   ____class_see____
 * @since 1.0.0
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
    const FIELD_AJAX = 'ajax';

    /**
     * Types list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $types = array(self::INFO, self::WARNING, self::ERROR);

    /**
     * Current messages
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $messages = array();

    /**
     * Add information-type message with additional translation arguments
     *
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addInfo($text, array $arguments = array(), $code = null)
    {
        return static::getInstance()->add($text, $arguments, $code, self::INFO);
    }

    /**
     * Add warning-type message with additional translation arguments
     *
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addWarning($text, array $arguments = array(), $code = null)
    {
        return static::getInstance()->add($text, $arguments, $code, self::WARNING);
    }

    /**
     * Add error-type message with additional translation arguments
     *
     * @param string $text      Label name
     * @param array  $arguments Substitution arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addError($text, array $arguments = array(), $code = null)
    {
        return static::getInstance()->add($text, $arguments, $code, self::ERROR);
    }


    /**
     * Add message
     *
     * @param string  $text      Message text
     * @param array   $arguments Substitution arguments OPTIONAL
     * @param string  $code      Language code OPTIONAL
     * @param string  $type      Message type OPTIONAL
     * @param boolean $rawText   Preprocessing text flag OPTIONAL
     * @param boolean $ajax      AJAX message state
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function add($text, array $arguments = array(), $code = null, $type = self::INFO, $rawText = false, $ajax = true)
    {
        $result = false;

        if (!empty($text)) {

            if (!$rawText) {
                $text = static::t($text, $arguments, $code);
            }

            if (!in_array($type, $this->types)) {
                $type = self::INFO;
            }

            // To prevent duplicated messages
            // :TODO: use "array_unique()" instead
            $this->messages[$type . md5($text)] = array(
                self::FIELD_TEXT => $text,
                self::FIELD_TYPE => $type,
                self::FIELD_AJAX => $ajax,
            );

            \XLite\Core\Session::getInstance()->topMessages = $this->messages;

            $result = true;
        }

        return $result;
    }

    /**
     * Add messages
     *
     * @param array  $messages Message texts
     * @param string $type     Message type OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addBatch(array $messages, $type = self::INFO)
    {
        $result = true;

        foreach ($messages as $message) {
            $result = $result && $this->add($message, array(), null, $type);
        }

        return $result;
    }

    /**
     * Get messages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMessages()
    {
        $messages = \XLite\Core\Session::getInstance()->topMessages;

        if (!is_array($messages)) {
            $messages = array();
        }

        return $messages;
    }

    /**
     * Get messages for AJAX response
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAJAXMessages()
    {
        $messages = \XLite\Core\Session::getInstance()->topMessages;

        if (!is_array($messages)) {
            $messages = array();
        }

        foreach ($messages as $i => $message) {
            if (!$message[self::FIELD_AJAX]) {
                unset($messages[$i]);
            }
        }

        return $messages;
    }

    /**
     * Get previous messages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPreviousMessages()
    {
        return $this->messages;
    }

    /**
     * Unload previous messages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function unloadPreviousMessages()
    {
        $messages = $this->getPreviousMessages();
        $this->messages = array();
        $this->clear();

        return $messages;
    }

    /**
     * Clear list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clear()
    {
        \XLite\Core\Session::getInstance()->topMessages = array();
    }

    /**
     * Clear top messages list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clearTopMessages()
    {
        $this->clear();
    }


    /**
     * Clear only AJAX messages
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clearAJAX()
    {
        $messages = \XLite\Core\Session::getInstance()->topMessages;

        if (!is_array($messages)) {
            $messages = array();
        }

        foreach ($messages as $i => $message) {
            if ($message[self::FIELD_AJAX]) {
                unset($messages[$i]);
            }
        }

        \XLite\Core\Session::getInstance()->topMessages = $messages;
    }

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->messages = $this->getMessages();
    }
}
