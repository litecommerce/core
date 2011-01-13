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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * ADrupal 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ADrupal extends \XLite\Base\Singleton
{
    /**
     * Initialized handler instance
     * 
     * @var    \XLite\Module\CDev\DrupalConnector\Handler
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $handler;

    /**
     * Already registered resources
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $registeredResources = array('js' => array(), 'css' => array());


    // ------------------------------ Application layer -

    /**
     * Return instance of current CMS connector
     * 
     * @return \XLite\Module\CDev\DrupalConnector\Handler
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandler()
    {
        if (!isset($this->handler)) {
            $this->handler = \XLite\Module\CDev\DrupalConnector\Handler::getInstance();
            $this->handler->init();
        }

        return $this->handler;
    }

    /**
     * Execute a controller action
     *
     * @param string $target Controller target
     * @param string $action Action to perform
     * @param array  $data   Request data
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function runController($target, $action = null, array $data = array())
    {
        $data = array('target' => $target, 'action' => $action) + $data;

        $this->getHandler()->mapRequest(array(\XLite\Core\CMSConnector::NO_REDIRECT => true) + $data);
        $this->getHandler()->runController(md5(serialize($data)));
    }


    // ------------------------------ Resources (CSS and JS) -

    /**
     * Get resources (from list) which are not already registered
     * 
     * @param string $type  Resource type ("js" or "css")
     * @param array  $files Resource files
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     i* @since  3.0.0
     */
    protected function getUniqueResources($type, array $files)
    {
        $files = array_diff($files, static::$registeredResources[$type]);
        static::$registeredResources[$type] = array_merge(static::$registeredResources[$type], $files);

        return $files;
    }

    /**
     * Get JS scope
     *
     * @param string $file Resource file path
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getJSScope($file)
    {
        return preg_match('/.skins.common.js./Ss', $file) ? 'header' : 'footer';
    }

    /**
     * Get file unique basename
     *
     * @param string $file Resource file path
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getResourceBasename($file)
    {
        return preg_replace('/\.(css|js)$/Ss', '.' . uniqid() . '.$1', basename($file));
    }

    /**
     * Get resource description in Drupal format
     *
     * @param string $file Resource file path
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getResourceInfoCommon($file)
    {
        return array(
            'type'     => 'file',
            'basename' => $this->getResourceBasename($file),
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param string $file Resource file path
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getResourceInfoJS($file)
    {
        return array(
            'scope' => $this->getJSScope($file),
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param string $file Resource file path
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getResourceInfoCSS($file)
    {
        return array(
            'group' => CSS_DEFAULT,
        );
    }

    /**
     * Get resource description in Drupal format
     * 
     * @param string $type Resource type ("js" or "css")
     * @param string $file Resource file path
     *  
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getResourceInfo($type, $file)
    {
        return $this->getResourceInfoCommon($file) + $this->{__FUNCTION__ . strtoupper($type)}($file);
    }

    /**
     * Register single resource
     *
     * @param string $type Resource type ("js" or "css")
     * @param string $file Resource file path
     *
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerResource($type, $file)
    {
        return call_user_func_array('drupal_add_' . $type, array($file, $this->getResourceInfo($type, $file)));
    }

    /**
     * Register LC widget resources
     *
     * @param \XLite\View\AView $widget LC widget to get resources list
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerResources(\XLite\View\AView $widget)
    {
        foreach ($widget->getRegisteredResources() as $type => $files) {
            foreach ($this->getUniqueResources($type, $files) as $file) {
                $this->registerResource($type, $file);
            }
        }
    }
}
