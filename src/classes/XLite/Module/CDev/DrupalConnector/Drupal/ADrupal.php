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

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * ADrupal
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ADrupal extends \XLite\Base\Singleton
{
    /**
     * Already registered resources
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $registeredResources = array('js' => array(), 'css' => array());

    /**
     * Unique suffix to resource filenames
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.13
     */
    protected static $resourcesBaseUID;

    /**
     * Resources weight counter
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $resourcesCounter = 0;

    /**
     * Initialized handler instance
     *
     * @var   \XLite\Module\CDev\DrupalConnector\Handler
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $handler;

    // {{{ Application layer

    /**
     * Return instance of current CMS connector
     *
     * @return \XLite\Module\CDev\DrupalConnector\Handler
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param string $action Action to perform OPTIONAL
     * @param array  $data   Request data OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function runController($target, $action = null, array $data = array())
    {
        $data = array('target' => $target, 'action' => $action) + $data;

        $this->getHandler()->mapRequest(array(\XLite\Core\CMSConnector::NO_REDIRECT => true) + $data);
        $this->getHandler()->runController(md5(serialize($data)));
    }

    // }}}

    // {{{ Resources (CSS and JS)

    /**
     * Register LC widget resources
     *
     * @param \XLite\View\AView $widget LC widget to get resources list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function registerResources(\XLite\View\AView $widget)
    {
        foreach ($widget->getRegisteredResources() as $type => $files) {
            $method = 'drupal_add_' . $type;

            foreach ($files as $name => $data) {
                if (empty(static::$registeredResources[$type][$name])) {
                    $method($data['file'], $this->getResourceInfo($type, $data));

                    static::$registeredResources[$type][$name] = true;
                }
            }
        }
    }

    /**
     * Get resource description in Drupal format
     *
     * @param string $type Resource type ("js" or "css")
     * @param array  $file Resource file info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResourceInfo($type, array $file)
    {
        return $this->getResourceInfoCommon($file) + $this->{__FUNCTION__ . strtoupper($type)}($file);
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResourceInfoCommon(array $file)
    {
        return array(
            'type'     => 'file',
            'basename' => $this->getResourceBasename($file['file']),
            'weight'   => isset($file['weight']) ? $file['weight'] : static::$resourcesCounter,
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResourceInfoJS(array $file)
    {
        $scope = $this->getJSScope($file['file']);

        return array(
            'scope' => $scope,
            'defer' => ('footer' == $scope),
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResourceInfoCSS(array $file)
    {
        return array(
            'group' => CSS_DEFAULT,
            'media' => isset($file['media']) ? $file['media'] : 'all',
        );
    }

    /**
     * Get JS scope
     *
     * @param string $file Resource file path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResourceBasename($file)
    {
        return preg_replace('/\.(css|js)$/Ss', '.' . $this->getUniqueID() . '.$1', basename($file));
    }

    /**
     * Return unique identifier
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getUniqueID()
    {
        return static::$resourcesBaseUID . ++static::$resourcesCounter;
    }

    /**
     * Protected constructor
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        static::$resourcesBaseUID = uniqid();
    }

    // }}}
}
