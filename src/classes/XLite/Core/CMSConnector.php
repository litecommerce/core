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
     * Get profiled DB condition fields list
     * 
     * @param integer $cmsUserId CMS user Id
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getProfileDBFields($cmsUserId)
    {
        return array(
            'cms_profile_id' => intval($cmsUserId),
            'cms_name'       => $this->getCMSName(),
        );
    }

    /**
     * getProfileWhereCondition 
     * TODO: remove this method
     *
     * @param integer $cmsUserId CMS user Id
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getProfileWhereCondition($cmsUserId)
    {
        return \Includes\Utils\Converter::buildQuery(
            $this->getProfileDBFields($cmsUserId), '=', ' AND ', '\''
        ) . ' AND order_id = \'0\'';
    }

    /**
     * Return ID of LC profile associated with the passed ID of CMS profile
     * 
     * @param integer $cmsUserId CMS profile ID
     *  
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfileIdByCMSId($cmsUserId)
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
            ->findOneByCMSId($this->getProfileDBFields($cmsUserId));

        return $profile ? $profile->getProfileId() : null;
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
        return array(
            'category',
            'product',
        );
    }

    /**
     * Get category clean URL by category id
     * 
     * @param integer $id     Category ID
     * @param array   $params URL params
     *  
     * @return string|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryCleanURL($id, array $params = array())
    {
        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($id);

        return (isset($category) && $category->getCleanUrl())
            ? \Includes\Utils\URLManager::trimTrailingSlashes($category->getCleanUrl())
                . '/' . \Includes\Utils\Converter::buildQuery($params, '-', '/')
            : null;
    }

    /**
     * Get product Clean URL by product id
     * 
     * @param integer $productId Product ID
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductCleanURL($productId)
    {
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($productId);

        $result = null;

        if (isset($product) && $product->getCleanUrl()) {
            $result = $product->getCleanUrl();
            if (!preg_match('/\.html?$/Ss', $result)) {
                $result .= '.html';
            }
        }

        return $result;
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
     * @return boolean 
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
     * @param array $request Params to map
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
     * @return boolean 
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
     * @param string $applicationId Cache key OPTIONAL
     *  
     * @return \XLite
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
     * @param string $applicationId Cache key OPTIONAL
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
     * @param string $applicationId Cache key OPTIONAL
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
     * @param string $applicationId Cache key OPTIONAL
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
     * @param string  $class  Widget class name
     * @param array   $params Widget params
     * @param integer $delta  Drupal-specific param - so called "delta" OPTIONAL
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
     * @param string $target Controller target
     * @param array  $params Controller params
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
     * @param integer $cmsUserId CMS user Id
     * @param array   $data      Data to prepare
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
     * @param integer $cmsUserId Internal user ID in CMS
     *  
     * @return \XLite\Model\Profile
     * @access public
     * @since  3.0.0
     */
    public function getProfile($cmsUserId)
    {
        return \XLite\Core\Auth::getInstance()->getProfile($this->getProfileIdByCMSId($cmsUserId));
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

        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')
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

            $parts = preg_split('\'/\'', $path, 2, PREG_SPLIT_NO_EMPTY);

            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->findOneByCleanUrl($parts[0]);

            if ($category) {

                $params  = array('category_id' => $category->getCategoryId());

                if (!empty($parts[1])) {

                    $query = \Includes\Utils\Converter::parseQuery($parts[1], '-', '/');

                    if (is_array($query)) {

                        $params += $query;

                    }

                }

                $cleanUrl = \XLite\Core\Converter::buildURL('category', '', $params);

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
        return \XLite\Model\Session::TTL;
    }
}

