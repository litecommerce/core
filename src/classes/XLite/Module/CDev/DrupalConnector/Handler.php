<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector;

/**
 * CMS connector
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Handler extends \XLite\Core\CMSConnector
{
    /**
     * Message types translation table (XLite to Drupal)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $messageTypes = array(
        \XLite\Core\TopMessage::INFO    => 'status',
        \XLite\Core\TopMessage::WARNING => 'warning',
        \XLite\Core\TopMessage::ERROR   => 'error',
    );


    /**
     * Return name of current CMS
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCMSName()
    {
        return '____DRUPAL____';
    }

    /**
     * Return the default controller name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDefaultTarget()
    {
        return 'drupal';
    }

    /**
     * Get portal object by path
     *
     * @param string $path Path to compare
     *
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPortalByPath($path)
    {
        return \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortal($path);
    }

    /**
     * Get portal object by target
     *
     * @param string $target Target to search
     *
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPortalByTarget($target)
    {
        $class = \XLite\Core\Converter::getControllerClass($target);

        $portals = array_filter(
            \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortals(),
            function (\XLite\Module\CDev\DrupalConnector\Model\Portal $portal) use ($class) {
                return $portal->getController() === $class;
            }
        );

        return is_array($portals) ? array_shift($portals) : null;
    }

    /**
     * Initialization
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();

        $this->mapRequest($this->getLCArgs());
        $this->setPreviousTopMessages();
    }

    /**
     * Get Drupal-based Clean URL
     *
     * @param mixed $path    ____param_comment____
     * @param array $options ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDrupalCleanURL($path, array $options)
    {
        $url = null;
        if (0 === strpos($path, \XLite\Core\Converter::DRUPAL_ROOT_NODE . '/')) {
            $args = explode('/', substr($path, strlen(\XLite\Core\Converter::DRUPAL_ROOT_NODE) + 1));
            $url = $this->getCleanURL($this->getControllerArgs($args, false));
        }

        return $url;
    }

    /**
     * Clear top message in Drupal
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clearTopMessages()
    {
        return drupal_get_messages();
    }

    /**
     * Method to get raw Drupal request arguments
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getArgs()
    {
        return arg();
    }

    /**
     * Check if current page is an LC portal
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPortal()
    {
        $result = array(null, null, array());
        $item   = menu_get_item();

        if ($item && !empty($item['path'])) {

            $result[0] = \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortal($item['path']);

            if ($result[0]) {
                $result[1] = $item['path'];
                $result[2] = empty($item['page_arguments']) ? array() : $item['page_arguments'];
            }
        }

        return $result;
    }

    /**
     * Check if current page is an LC controller
     *
     * @param array &$args Request arguments
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isController(array &$args)
    {
        return \XLite\Module\CDev\DrupalConnector\Core\Converter::DRUPAL_ROOT_NODE === array_shift($args);
    }

    /**
     * Return portal arguments
     *
     * @param \XLite\Module\CDev\DrupalConnector\Model\Portal $portal   Portal model object
     * @param string                                          $path     Portal path
     * @param array                                           $args     Drupal URL arguments
     * @param array                                           $pageArgs Drupal page arguments OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPortalArgs(
        \XLite\Module\CDev\DrupalConnector\Model\Portal $portal,
        $path,
        array $args,
        array $pageArgs = array()
    ) {
        return $portal->getLCArgs($path, $args, \Includes\Utils\Converter::parseArgs($pageArgs), '-');
    }

    /**
     * Return controller arguments
     *
     * @param array   $args            Drupal URL arguments
     * @param boolean $includePOSTVars Flag OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getControllerArgs(array $args, $includePOSTVars = true)
    {
        $result = array();

        foreach (array('target', 'action') as $param) {
            $result[$param] = empty($args) ? '' : array_shift($args);
        }

        return array_merge(
            $result,
            \Includes\Utils\Converter::parseArgs($args, '-'),
            $includePOSTVars ? $_POST : array()
        );
    }

    /**
     * Return default arguments
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultArgs()
    {
        return array('target' => $this->getDefaultTarget());
    }

    /**
     * Translate Drupal request into LC format
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLCArgs()
    {
        $result = array();
        $args   = $this->getArgs();

        list($portal, $path, $pageArgs) = $this->getPortal();

        if ($portal) {

            // Portal (LC controller with custom URL)
            $result += $this->getPortalArgs($portal, $path, $args, $pageArgs);

        } elseif ($this->isController($args)) {

            // Regular LC controller
            $result += $this->getControllerArgs($args);

        } else {

            // Non-LC page
            $result += $this->getDefaultArgs();
        }

        return $result;
    }

    /**
     * Set Drupal messages using LC top messages data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setPreviousTopMessages()
    {
        foreach (\XLite\Core\TopMessage::getInstance()->unloadPreviousMessages() as $message) {
            drupal_set_message(
                $message['text'],
                isset($this->messageTypes[$message['type']]) ? $this->messageTypes[$message['type']] : 'status'
            );
        }
    }

    /**
     * Build CleanURL
     *
     * @param string $target Page identifier
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function buildCleanURL($target, $action = '', array $params = array())
    {
        return \XLite\Core\Converter::buildDrupalPath($target, $action, $params);
    }
}
