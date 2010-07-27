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
 * CMS connector 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * @var    booln
     * @access protected
     * @since  3.0.0
     */
    protected static $currentCMS = null;


    /**
     * List of widgets which can be exported
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $widgetsList = array(
        '\XLite\View\TopCategories'    => 'Categories list',
        '\XLite\View\Minicart'         => 'Minicart',
        '\XLite\View\Subcategories'    => 'Subcategories',
        '\XLite\View\CategoryProducts' => 'Category products list',
        '\XLite\View\ProductBox'       => 'Product block',
        '\XLite\View\PoweredBy'        => '\'Powered by\' block',
    );

    /**
     * Page types 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $pageTypes = array(
        'category'   => 'Category page',
        'product'    => 'Product page',
        'cart'       => 'Shopping cart',
        'checkout'   => 'Checkout',
        'order_list' => 'Orders list',
    );


    /**
     * getProfileDBFields 
     * 
     * @param int $cmsUserId CMS user Id
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getProfileDBFields($cmsUserId)
    {
        return array('cms_profile_id' => intval($cmsUserId), 'cms_name' => $this->getCMSName());
    }

    /**
     * getProfileWhereCondition 
     * 
     * @param int $cmsUserId CMS user Id
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getProfileWhereCondition($cmsUserId)
    {
        return \XLite\Core\Converter::getInstance()->buildQuery(
            $this->getProfileDBFields($cmsUserId), '=', ' AND ', '\''
        ) . ' AND order_id = \'0\'';
    }

    /**
     * Return ID of LC profile associated with the passed ID of CMS profile
     * 
     * @param int $cmsUserId CMS profile ID
     *  
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfileIdByCMSId($cmsUserId)
    {
        $profile = \XLite\Model\CachingFactory::getObject(__METHOD__ . $cmsUserId, '\XLite\Model\Profile');

        // Not initialized
        if (!$profile->isRead) {
            $profile->find($this->getProfileWhereCondition($cmsUserId));
        }

        return $profile->get('profile_id');
    }

    /**
     * getCleanURLTargets 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCleanURLTargets()
    {
        return array('category', 'product');
    }

    /**
     * getCategoryCleanURL
     * 
     * @param int $categoryId category ID
     *  
     * @return string|null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryCleanURL($categoryId)
    {
        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($categoryId);

        return (isset($category) && $category->getCleanUrl())
            ? preg_replace('/\/+$/Ss', '', $category->getCleanUrl()) . '/'
            : null;
    }

    /**
     * getProductCleanURL 
     * 
     * @param int $productId product ID
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductCleanURL($productId)
    {
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($productId);

        return (isset($product) && $product->getCleanUrl())
            ? ($product->getCleanUrl() . (preg_match('/(\.html|\/htm)$/Ss', $url) ? '.html' : ''))
            : null;
    }


    /**
     * Return currently used CMS name
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    abstract public function getCMSName();

    /**
     * Return the default controller name 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    abstract public function getDefaultTarget();

    /**
     * Get landing link
     *
     * @return string
     * @access public
     * @since  3.0.0 EE
     */
    abstract public function getLandingLink();


    /**
     * Determines if we export content into a CMS
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public static function isCMSStarted()
    {
        return isset(self::$currentCMS);
    }

    /**
     * Save passed params in the requester 
     * 
     * @param array $request params to map
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function mapRequest(array $request)
    {
        \XLite\Core\Request::getInstance()->mapRequest($request);
    }

    /**
     * Handler should called this function first to prevent any possible conflicts
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        self::$currentCMS = $this->getCMSName();
    }

    /**
     * Check if a widget requested from certain CMS
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkCurrentCMS()
    {
        return $this->getCMSName() === self::$currentCMS;
    }

    /**
     * Return list of widgets which can be exported
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getWidgetsList()
    {
        return $this->widgetsList;
    }

    /**
     * Get page types
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getPageTypes()
    {
        return $this->pageTypes;
    }

    /**
     * Return application instance 
     * 
     * @param string $applicationId cache key
     *  
     * @return XLite
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $applicationId cache key
     *
     * @return \XLite\View\Controller
     * @access public
     * @since  3.0.0
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
     * @param string $applicationId cache key
     * 
     * @return \XLite\Controller\AController
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $applicationId cache key
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $class  widget class name
     * @param array  $params widget params
     * @param int    $delta  drupal-specific param - so called "delta"
     *  
     * @return \XLite\Core\WidgetDataTransport
     * @access public
     * @since  3.0.0
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
     * @param string $target controller target
     * @param array  $params controller params
     *
     * @return \XLite\Core\WidgetDataTransport
     * @access public
     * @since  3.0.0
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
     * @param int   $cmsUserId CMS user Id
     * @param array $data      data to prepare
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function prepareProfileData($cmsUserId, array $data)
    {
        return $this->getProfileDBFields($cmsUserId) + $data;
    }

    /**
     * Check and return (if allowed) current user profile
     * 
     * @param int $cmsUserId internal user ID in CMS
     *  
     * @return \XLite\Model\Profile
     * @access public
     * @since  3.0.0
     */
    public function getProfile($cmsUserId)
    {
        return \XLite\Model\Auth::getInstance()->getProfile($this->getProfileIdByCMSId($cmsUserId));
    }



    // -----> FIXME - to revise

    /**
     * Check controller access
     * FIXME - do not uncomment: this will break the "runFrontController()" functionality
     * TODO  - code must be refactored
     *
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function isAllowed()
    {
        return true;

        /*$oldController = $this->getController();

        $this->getApplication()->setController();
        $controller = \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . '-' . \XLite\Core\Request::getInstance()->target,
            $this->getApplication(),
            'getController'
        );

        $result = $controller->checkAccess()
            && $this->getViewer()->checkVisibility();

        $this->getApplication()->setController($oldController);

        return $result;*/
    }

    /**
     * Get Clean URL 
     * 
     * @param array $args Arguments
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCleanURL(array $args)
    {
        $url = null;

        if (in_array($args['target'], $this->getCleanURLTargets())) {
            $idParam = $args['target'] . '_id';

            if (!empty($args[$idParam])) {
                $url = $this->{'get' . ucfirst($args['target']) . 'CleanURL'}($args[$idParam]);
            }
        }

        return $url;
    }

    /**
     * Get canonical URL by clean URL 
     * TODO - to improve
     * 
     * @param string $path Clean url
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURLByCleanURL($path)
    {
        $cleanUrl = null;

        // By product

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->findByCleanUrl(preg_replace('/(?:\.html|\.htm)$/Ss', '', $path));

        if (isset($product)) {
            $cleanUrl = \XLite\Core\Converter::buildURL(
                'product',
                '',
                array('product_id' => $product->get('product_id'))
            );
        }

        // By category

        if (!$cleanUrl) {

            $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryByCleanUrl(preg_replace('/\/+$/Ss', '', $path));
            if ($category) {
                $cleanUrl = \XLite\Core\Converter::buildURL(
                    'category',
                    '',
                    array('category_id' => $category->category_id)
                );
            }

        }

        return $cleanUrl;
    }

    /**
     * Get session TTL (in seconds) 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSessionTtl()
    {
        return \XLite\Model\Session::getInstance()->getTtl();
    }
}

