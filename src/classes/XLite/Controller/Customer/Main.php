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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Customer;

/**
 * \XLite\Controller\Customer\Main 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Main extends \XLite\Controller\Customer\Category
{
    /**
     * Controller parameters list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target');

    /**
     * handleRequest 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        if ($this->config->General->add_on_mode) {

            // switch to cart in Add-on mode
            $addOnModePage = $this->config->General->add_on_mode_page;

            if ('cart.php' !== $addOnModePage) {

                $this->redirect($addOnModePage);

            } else {

                parent::handleRequest();

            }

        } else {

            parent::handleRequest();

        }

    }


    /**
     * Preprocessor for no-action ren
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doNoAction()
    {
        parent::doNoAction();

        if (!\XLite\Core\Request::getInstance()->isAJAX()) {
            \XLite\Core\Session::getInstance()->productListURL = $this->getURL();
            \XLite\Core\Session::getInstance()->continueShoppingURL = $this->getURL();
        }
    }

    /**
     * Check controller visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return $this->getCategory()
            && \XLite\Model\Repo\Category::CATEGORY_ID_ROOT == $this->getCategory()->getCategoryId();
    }
}

