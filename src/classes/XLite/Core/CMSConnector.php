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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * CMS connector
 *
 */
abstract class CMSConnector extends \XLite\Base\Singleton
{
    /**
     * Name of the request param, which determines the redirect behaviour
     */
    const NO_REDIRECT = '____NO_REDIRECT____';

    /**
     * Current CMS name
     *
     * @var boolean
     */
    protected static $currentCMS = null;

    /**
     * List of widgets which can be exported
     *
     * @var array
     */
    protected $widgetsList = array(
        '\XLite\View\TopCategories' => 'Categories list',
        '\XLite\View\Minicart'      => 'Minicart',
        '\XLite\View\Subcategories' => 'Subcategories',
        '\XLite\View\ProductBox'    => 'Product block',
        '\XLite\View\PoweredBy'     => '\'Powered by\' block',

        '\XLite\View\ItemsList\Product\Customer\Category' => 'Category products list',
        '\XLite\View\Search'                              => 'Search product list',
        '\XLite\View\Form\Product\Search\Customer\Simple' => 'Products search simple form',
    );

    /**
     * Page types
     *
     * @var array
     */
    protected $pageTypes = array(
        'category'   => 'Category page',
        'product'    => 'Product page',
        'cart'       => 'Shopping cart',
        'checkout'   => 'Checkout',
        'order_list' => 'Orders list',
    );

    /**
     * Return currently used CMS name
     *
     * @return string
     */
    abstract public function getCMSName();

    /**
     * Determines if we export content into a CMS
     *
     * @return boolean
     */
    public static function isCMSStarted()
    {
        return isset(static::$currentCMS);
    }

    /**
     * Save passed params in the requester
     *
     * @param array $request Params to map
     *
     * @return void
     */
    public function mapRequest(array $request)
    {
        \XLite\Core\Request::getInstance()->mapRequest($request);
    }

    /**
     * Initialization
     *
     * @return void
     */
    public function init()
    {
        static::$currentCMS = $this->getCMSName();
    }

    /**
     * Check if a widget requested from certain CMS
     *
     * @return boolean
     */
    public function checkCurrentCMS()
    {
        return $this->getCMSName() === static::$currentCMS;
    }

    /**
     * Return list of widgets which can be exported
     *
     * @param string $name Widget name OPTIONAL
     *
     * @return array|string|null
     */
    public function getWidgetsList($name = null)
    {
        return isset($name) ? @$this->widgetsList[$name] : $this->widgetsList;
    }

    /**
     * Get page types
     *
     * @return array
     */
    public function getPageTypes()
    {
        return $this->pageTypes;
    }

    /**
     * Return application instance
     *
     * @param string $applicationId Cache key OPTIONAL
     *
     * @return \XLite
     */
    public function getApplication($applicationId = null)
    {
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . $applicationId, \XLite::getInstance(), 'run'
        );
    }

    /**
     * Return viewer for current page
     *
     * @param string $applicationId Cache key OPTIONAL
     *
     * @return \XLite\View\Controller
     */
    public function getViewer($applicationId = null)
    {
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . $applicationId, $this->getApplication($applicationId), 'getViewer'
        );
    }

    /**
     * Get controller
     *
     * @param string $applicationId Cache key OPTIONAL
     *
     * @return \XLite\Controller\AController
     */
    public function getController($applicationId = null)
    {
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . $applicationId, $this->getApplication($applicationId), 'getController'
        );
    }

    /**
     * Run controller
     *
     * @param string $applicationId Cache key OPTIONAL
     *
     * @return void
     */
    public function runController($applicationId = null)
    {
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . $applicationId, $this->getApplication($applicationId), 'runController'
        );
    }

    /**
     * Return widget
     *
     * @param string  $class  Widget class name
     * @param array   $params Widget params OPTIONAL
     * @param integer $delta  Drupal-specific param - so called "delta" OPTIONAL
     *
     * @return \XLite\Core\WidgetDataTransport
     */
    public function getWidget($class, array $params = array(), $delta = 0)
    {
        return new \XLite\Core\WidgetDataTransport(
            \XLite\Core\Operator::isClassExists($class) ? $this->getViewer()->getWidget($params, $class) : null
        );
    }

    /**
     * Return controller for current page
     *
     * @param string $target Controller target
     * @param array  $params Controller params OPTIONAL
     *
     * @return \XLite\Core\WidgetDataTransport
     */
    public function getPageInstance($target, array $params = array())
    {
        $class = \XLite\Core\Converter::getControllerClass($target);

        return new \XLite\Core\WidgetDataTransport(
            \XLite\Core\Operator::isClassExists($class) ? new $class(array('target' => $target) + $params) : null
        );
    }

    /**
     * Add CMS-specific fields to profile data
     *
     * @param integer $cmsUserId CMS user Id
     * @param array   $data      Data to prepare
     *
     * @return array
     */
    public function prepareProfileData($cmsUserId, array $data)
    {
        return $this->getProfileDBFields($cmsUserId) + $data;
    }

    /**
     * Return LC profile associated with the passed ID of CMS profile
     *
     * @param integer $cmsUserId CMS profile ID
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfileByCMSId($cmsUserId)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Profile')
            ->findOneByCMSId($this->getProfileDBFields($cmsUserId));
    }

    /**
     * Get profiled DB condition fields list
     *
     * @param integer $cmsUserId CMS user Id
     *
     * @return array
     */
    protected function getProfileDBFields($cmsUserId)
    {
        return array(
            'cms_profile_id' => intval($cmsUserId),
            'cms_name'       => $this->getCMSName(),
        );
    }

    /**
     * Return ID of LC profile associated with the passed ID of CMS profile
     *
     * @param integer $cmsUserId CMS profile ID
     *
     * @return integer
     */
    public function getProfileIdByCMSId($cmsUserId)
    {
        $profile = $this->getProfileByCMSId($cmsUserId);

        return $profile ? $profile->getProfileId() : null;
    }

    /**
     * Return ID of CMS profile associated with the passed ID of LC profile
     *
     * @param integer $lcUserId LC profile ID
     *
     * @return integer
     */
    public function getCMSIdByProfileId($lcUserId)
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
            ->find($lcUserId);

        return $profile ? $profile->getCMSProfileId() : null;
    }

    /**
     * Check and return (if allowed) current user profile
     *
     * @param integer $cmsUserId Internal user ID in CMS
     *
     * @return \XLite\Model\Profile
     */
    public function getProfile($cmsUserId)
    {
        return \XLite\Core\Auth::getInstance()->getProfile($this->getProfileIdByCMSId($cmsUserId));
    }
}
