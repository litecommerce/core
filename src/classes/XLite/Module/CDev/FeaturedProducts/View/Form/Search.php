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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\FeaturedProducts\View\Form;

/**
 * Search
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Search extends \XLite\View\Form\Product\Search\Admin\AAdmin
{
    /**
     * getDefaultTarget
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return 'product_list';
    }

    /**
     * getDefaultAction
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'search_featured_products';
    }

    /**
     * getDefaultParams
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultParams()
    {
        $params = parent::getDefaultParams();
        $params[\XLite\Controller\AController::RETURN_URL] = \Includes\Utils\URLManager::getSelfURL() . '&mode=search_featured_products';

        return $params;
    }

}
