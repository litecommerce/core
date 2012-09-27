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

namespace XLite\Controller\Customer;

/**
 * Abstract controller for Customer interface
 *
 */
abstract class ACustomer extends \XLite\Controller\AController
{
    /**
     * cart
     *
     * @var \XLite\Model\Cart
     */
    protected $cart;

    /**
     * Initial cart fingerprint
     *
     * @var array
     */
    protected $initialCartFingerprint;

    /**
     * Breadcrumbs
     *
     * @var \XLite\View\Location
     */
    protected $locationPath;

    // {{{ Breadcrumbs

    /**
     * Return current location path
     *
     * @return \XLite\View\Location
     */
    public function getLocationPath()
    {
        if (!isset($this->locationPath)) {
            $this->defineLocationPath();
        }

        return $this->locationPath;
    }

    /**
     * Method to create the location line
     *
     * @return void
     */
    protected function defineLocationPath()
    {
        $this->locationPath = array();

        // Ability to add part to the line
        $this->addBaseLocation();

        // Ability to define last element in path via short function
        $location = $this->getLocation();

        if ($location) {
            $this->addLocationNode($location);
        }
    }

    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return null;
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        // Common element for all location lines
        $this->locationPath[] = new \XLite\View\Location\Node\Home();
    }

    /**
     * Add node to the location line
     *
     * @param string $name     Node title
     * @param string $link     Node link OPTIONAL
     * @param array  $subnodes Node subnodes OPTIONAL
     *
     * @return void
     */
    protected function addLocationNode($name, $link = null, array $subnodes = null)
    {
        $this->locationPath[] = \XLite\View\Location\Node::create($name, $link, $subnodes);
    }

    // }}}

    /**
     * Return current category Id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return parent::getCategoryId() ?: $this->getRootCategoryId();
    }

    /**
     * Return cart instance
     *
     * @return \XLite\Model\Order
     */
    public function getCart()
    {
        return \XLite\Model\Cart::getInstance();
    }

    /**
     * Get the full URL of the page
     * Example: getShopURL('cart.php') = "http://domain/dir/cart.php
     *
     * @param string  $url    Relative URL OPTIONAL
     * @param boolean $secure Flag to use HTTPS OPTIONAL
     * @param array   $params Optional URL params OPTIONAL
     *
     * @return string
     */
    public function getShopURL($url = '', $secure = null, array $params = array())
    {
        if (!isset($secure) && \XLite\Core\Config::getInstance()->Security->full_customer_security) {
            $secure = true;
        }

        return parent::getShopURL($url, $secure, $params);
    }

    /**
     * Check if cuurrent user is logged in
     *
     * @return boolean
     */
    public function isLogged()
    {
        return \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Handles the request
     *
     * @return void
     */
    public function handleRequest()
    {
        if (!$this->checkStorefrontAccessability()) {
            $this->closeStorefront();
        }

        // Save initial cart fingerprint
        $this->initialCartFingerprint = $this->getCart()->getEventFingerprint();

        return parent::handleRequest();
    }


    /**
     * Stub for the CMS connectors
     *
     * @return void
     */
    protected function checkStorefrontAccessability()
    {
        return !\XLite\Core\Config::getInstance()->General->shop_closed;
    }

    /**
     * Perform some actions to prohibit access to storefornt
     *
     * @return void
     */
    protected function closeStorefront()
    {
        include LC_DIR_SKINS . '/storefront_closed.html';
        exit (0);
    }

    /**
     * Return template to use in a CMS
     *
     * @return string
     */
    protected function getCMSTemplate()
    {
        return 'center_top.tpl';
    }

    /**
     * Select template to use
     *
     * @return string
     */
    protected function getViewerTemplate()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED) ? $this->getCMSTemplate() : parent::getViewerTemplate();
    }

    /**
     * Recalculates the shopping cart
     *
     * @return void
     */
    protected function updateCart()
    {
        $this->getCart()->updateOrder();
        \XLite\Core\Database::getRepo('XLite\Model\Cart')->update($this->getCart());

        $this->assembleEvent();

        $this->initialCartFingerprint = $this->getCart()->getEventFingerprint();
    }

    /**
     * Assemble updateCart event
     *
     * @return boolean
     */
    protected function assembleEvent()
    {
        $result = false;

        $diff = array();

        $old = $this->initialCartFingerprint;
        $new = $this->getCart()->getEventFingerprint();
        $items = array();

        // Assembly changed
        foreach ($new['items'] as $n => $cell) {

            $found = false;

            foreach ($old['items'] as $i => $oldCell) {

                if ($cell['key'] == $oldCell['key']) {

                    if ($cell['quantity'] != $oldCell['quantity']) {
                        $cell['quantity_change'] = $cell['quantity'] - $oldCell['quantity'];
                        $items[] = $cell;
                    }

                    unset($old['items'][$i]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cell['quantity_change'] = $cell['quantity'];
                $items[] = $cell;
            }
        }

        // Assemble removed
        foreach ($old['items'] as $cell) {
            $cell['quantity_change'] = $cell['quantity'] * -1;
            $items[] = $cell;
        }

        if ($items) {
            $diff['items'] = $items;
        }

        if ($old['total'] != $this->getCart()->getTotal()) {
            $diff['total'] = $this->getCart()->getTotal() - $old['total'];
        }

        if ($diff) {
            \XLite\Core\Event::updateCart($diff);
            $result = true;
        }

        return $result;
    }

    /**
     * isCartProcessed
     *
     * @return boolean
     */
    protected function isCartProcessed()
    {
        return $this->getCart()->isProcessed() || $this->getCart()->isQueued();
    }

    /**
     * Get or create cart profile
     *
     * @return \XLite\Model\Profile
     */
    protected function getCartProfile()
    {
        $profile = $this->getCart()->getProfile();

        if (!$profile) {
            $profile = new \XLite\Model\Profile;
            $profile->setLogin('');
            $profile->setOrder($this->getCart());
            $profile->create();

            $this->getCart()->setProfile($profile);

            \XLite\Core\Auth::getInstance()->loginProfile($profile);

            \XLite\Core\Database::getEM()->persist($profile);
            \XLite\Core\Database::getEM()->flush();
        }

        return $profile;
    }

    /**
     * Check - need use secure protocol or not
     *
     * @return boolean
     */
    protected function needSecure()
    {
        return parent::needSecure()
            || (!\XLite\Core\Request::getInstance()->isHTTPS()) && \XLite\Core\Config::getInstance()->Security->full_customer_security;
    }

    // {{{ Clean URLs related routines

    /**
     * Preprocessor for no-action run
     *
     * @return void
     */
    protected function doNoAction()
    {
        parent::doNoAction();

        if (LC_USE_CLEAN_URLS && !$this->isAJAX() && !$this->isRedirectNeeded() && $this->isRedirectToCleanURLNeeded()) {
            $this->performRedirectToCleanURL();
        }
    }

    /**
     * Check if redirect to clean URL is needed
     *
     * @return boolean
     */
    protected function isRedirectToCleanURLNeeded()
    {
        return preg_match(
            '/\/cart\.php/Si',
            \Includes\Utils\ArrayManager::getIndex(\XLite\Core\Request::getInstance()->getServerData(), 'REQUEST_URI')
        );
    }

    /**
     * Redirect to clean URL
     *
     * @return void
     */
    protected function performRedirectToCleanURL()
    {
        $data = \XLite\Core\Request::getInstance()->getGetData();

        if (\XLite::TARGET_DEFAULT === ($target = $this->getTarget())) {
            $target = '';

        } else {
            unset($data['target']);
        }

        $this->setReturnURL(\XLite\Core\Converter::buildFullURL($target, '', $data));
    }

    // }}}
}
