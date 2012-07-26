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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector;

/**
 * CMS connector
 *
 */
class Handler extends \XLite\Core\CMSConnector
{
    /**
     * Message types translation table (XLite to Drupal)
     *
     * @var array
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
     */
    public function getCMSName()
    {
        return '____DRUPAL____';
    }

    /**
     * Return the default controller name
     *
     * @return string
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
     */
    public function init()
    {
        parent::init();

        $this->mapRequest($this->getLCArgs());
        $this->setPreviousTopMessages();
    }

    /**
     * Clear top message in Drupal
     *
     * @return array
     */
    public function clearTopMessages()
    {
        return drupal_get_messages();
    }

    /**
     * Method to get raw Drupal request arguments
     *
     * @return array
     */
    protected function getArgs()
    {
        return arg();
    }

    /**
     * Check if current page is an LC portal
     *
     * @return array
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
     */
    protected function getDefaultArgs()
    {
        return array('target' => $this->getDefaultTarget());
    }

    /**
     * Translate Drupal request into LC format
     *
     * @return array
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

    // {{{ URLs management

    /**
     * Get Drupal-based Clean URL
     *
     * @param string $path    Drupal "path"
     * @param array  $options A set of URL options
     *
     * @return string
     */
    public function getDrupalCleanURL($path, array $options)
    {
        $url = null;

        if (0 === strpos($path, \XLite\Core\Converter::DRUPAL_ROOT_NODE . '/')) {

            $args = explode('/', substr($path, strlen(\XLite\Core\Converter::DRUPAL_ROOT_NODE) + 1));
            $url  = $this->getCleanURL($this->getControllerArgs($args, false));
        }

        return $url;
    }

    /**
     * Get canonical URL by clean URL
     *
     * @param string $path Clean url
     *
     * @return string
     */
    public function getURLByCleanURL($path)
    {
        $cleanURL = null;

        if (preg_match('/(' . \XLite\Core\Converter::getCleanURLAllowedCharsPattern() . ')\.html?$/Si', $path, $parts)) {
            $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByCleanURL($parts[1]);

            if (isset($product)) {
                $cleanURL = $this->buildCleanURL('product', '', array('product_id' => $product->getProductId()));
            }

        } else {
            $parts  = preg_split('\'/\'', $path, 2, PREG_SPLIT_NO_EMPTY);
            $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneByCleanURL($parts[0]);

            if (isset($category)) {
                $params = array('category_id' => $category->getCategoryId());

                if (!empty($parts[1])) {
                    $query = \Includes\Utils\Converter::parseQuery($parts[1], '-', '/');

                    if (is_array($query)) {
                        $params += $query;
                    }
                }

                $cleanURL = $this->buildCleanURL('category', '', $params);
            }
        }

        return $cleanURL;
    }

    /**
     * Get Clean URL
     *
     * @param array $args Arguments
     *
     * @return string
     */
    protected function getCleanURL(array $args)
    {
        $url = null;

        $target = $args['target'];
        unset($args['target']);

        if (in_array($target, $this->getCleanURLTargets())) {

            if (!empty($args[$target . '_id'])) {

                $id = $args[$target . '_id'];
                unset($args[$target . '_id']);

                if (empty($args['action'])) {
                    unset($args['action']);
                }

                $url = $this->{'get' . ucfirst($target) . 'CleanURL'}($id, $args);
            }
        }

        return $url;
    }

    /**
     * getCleanURLTargets
     *
     * @return array
     */
    protected function getCleanURLTargets()
    {
        return array(
            'category',
            'product',
        );
    }

    /**
     * Get category clean URL by category id
     *
     * @param integer $id     Category ID
     * @param array   $params URL params OPTIONAL
     *
     * @return string|void
     */
    protected function getCategoryCleanURL($id, array $params = array())
    {
        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($id);
        
        return (isset($category) && $category->getCleanURL())
            ? \Includes\Utils\URLManager::trimTrailingSlashes($category->getCleanURL())
                . '/' . \Includes\Utils\Converter::buildQuery($params, '-', '/')
            : null;
    }       
    
    /**
     * Get product Clean URL by product id
     *
     * @param integer $productId Product ID
     *
     * @return string
     */
    protected function getProductCleanURL($productId)
    {
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($productId);
        $result  = null;
        
        if (isset($product) && $product->getCleanURL()) {
            $result = $product->getCleanURL();
            
            if (!preg_match('/\.html?$/Si', $result)) {
                $result .= '.html';
            }   
        }   
        
        return $result;
    }

    /**
     * Build CleanURL
     *
     * @param string $target Page identifier
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     */
    protected function buildCleanURL($target, $action = '', array $params = array())
    {
        return \XLite\Core\Converter::buildDrupalPath($target, $action, $params);
    }

    // }}}
}
