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

namespace XLite\Module\CDev\SimpleCMS\Controller\Customer;

/**
 * Page controller
 *
 * @see   ____class_see____
 * @since 1.0.18
 */
class Page extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target', 'id');

    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        $page = $this->getPage();

        return parent::checkAccess()
            && $page
            && $page->getEnabled();
    }

    /**
     * Alias
     *
     * @return \XLite\Module\CDev\SimpleCMS\Model\Page
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPage()
    {
        return $this->getId()
            ? \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')->find($this->getId())
            : null;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return $this->checkAccess()
            ? $this->getPage()->getName() 
            : 'Page not found';
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->checkAccess()
            ? $this->getPage()->getName() 
            : 'Page not found';
    }

    /**
     * Return current model id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getId()
    {
        return intval(\XLite\Core\Request::getInstance()->id);
    }


    /**
     * Get meta description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMetaDescription()
    {
        $page = $this->getPage();

        return $page ? $page->getTeaser() : parent::getMetaDescription();
    }

    /**
     * Get meta keywords
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getKeywords()
    {
        $page = $this->getPage();

        return $page ? $page->getMetaKeywords() : parent::getKeywords();
    }

    /**
     * Return current (or default) page object
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModelObject()
    {
        return $this->getPage();
    }

    /**
     * Check if redirect to clean URL is needed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function isRedirectToCleanURLNeeded()
    {
        return parent::isRedirectToCleanURLNeeded() || (!\XLite::isCleanURL() && $this->getModelObject()->getCleanURL());
    }

}
