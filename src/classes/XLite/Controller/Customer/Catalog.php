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
 * ____description____
 *
 */
abstract class Catalog extends \XLite\Controller\Customer\ACustomer
{
    /**
     * getModelObject
     *
     * @return \XLite\Model\AEntity
     */
    abstract protected function getModelObject();

    /**
     * Define and set handler attributes; initialize handler
     *
     * @param array $params Handler params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->params[] = 'category_id';
    }

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     */
    public function getCategory()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($this->getCategoryId());
    }

    /**
     * Returns the page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        $model = $this->getModelObject();

        return ($model && $model->getName()) ? $model->getName() : parent::getTitle();
    }

    /**
     * Returns the page title (for the <title> tag)
     *
     * @return string
     */
    public function getPageTitle()
    {
        $model = $this->getModelObject();

        return ($model && $model->getMetaTitle()) ? $model->getMetaTitle() : $this->getTitle();
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        $model = $this->getModelObject();

        return $model ? $model->getDescription() : null;
    }

    /**
     * Get meta description
     *
     * @return string
     */
    public function getMetaDescription()
    {
        $model = $this->getModelObject();

        return strip_tags(
            $model
                ? ($model->getMetaDesc() ? $model->getMetaDesc() : $this->getDescription())
                : parent::getMetaDescription()
        );
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        $model = $this->getModelObject();

        return $model ? $model->getMetaTags() : parent::getKeywords();
    }


    /**
     * Return path for the current category
     *
     * @return array
     */
    protected function getCategoryPath()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategoryPath($this->getCategoryId());
    }

    /**
     * Preprocessor for no-action run
     *
     * @return void
     */
    protected function doNoAction()
    {
        parent::doNoAction();

        if (!$this->isAJAX()) {
            \XLite\Core\Session::getInstance()->productListURL = $this->getURL();
        }
    }

    /**
     * Check if redirect to clean URL is needed
     *
     * @return boolean
     */
    protected function isRedirectToCleanURLNeeded()
    {
        return parent::isRedirectToCleanURLNeeded() || (!\XLite::isCleanURL() && $this->getModelObject()->getCleanURL());
    }

    /**
     * Return link to category page
     *
     * @param \XLite\Model\Category $category Category model object to use
     *
     * @return string
     */
    protected function getCategoryURL(\XLite\Model\Category $category)
    {
        return $this->buildURL('category', '', array('category_id' => $category->getCategoryId()));
    }

    /**
     * Prepare subnodes for the location path node
     *
     * @param \XLite\Model\Category $category Node category
     *
     * @return array
     */
    protected function getLocationNodeSubnodes(\XLite\Model\Category $category)
    {
        $nodes = array();

        foreach ($category->getSiblings(true) as $category) {
            $nodes[] = \XLite\View\Location\Node::create(
                $category->getName(),
                $this->getCategoryURL($category)
            );
        }

        return $nodes;
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        foreach ($this->getCategoryPath() as $category) {
            if ($category->isVisible()) {
                $this->addLocationNode(
                    $category->getName(),
                    $this->getCategoryURL($category),
                    $this->getLocationNodeSubnodes($category)
                );

            } else {
                break;
            }
        }
    }
}
