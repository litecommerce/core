<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Top categories widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Side bar with list of root categories (menu)
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_DrupalConnector_View_TopCategories extends XLite_View_TopCategories implements XLite_Base_IDecorator
{
    /**
     * Return root categories list 
     * 
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getCategories()
    {
		parent::getCategories();

        if ($this->categories) {

            $pathIds = array();

            $category_id = $this->category_id;
            if ($category_id) {
	            $category = new XLite_Model_Category($category_id);

                $pathIds = array();
                foreach ($category->getPath() as $c) {
                    $pathIds[] = $c->get('category_id');
                }
            }

            $last = count($this->categories) - 1;
            foreach ($this->categories as $i => $c) {
                $classes = array();

                if (!$c->getSubcategories()) {
                    $classes[] = 'leaf';
                }

                if (0 === $i) {
                    $classes[] = 'first';
                }

                if ($last === $i) {
                    $classes[] = 'last';
                }

                if (in_array($c->get('category_id'), $pathIds)) {
                    $classes[] = 'active-trail';
                }

                if ($category_id && $category_id == $c->get('category_id')) {
                    $this->categories[$i]->linkClassName = 'active';

                } else {
                    $this->categories[$i]->linkClassName = '';
                }

                $this->categories[$i]->className = implode(' ', $classes);
            }
        }

        return $this->categories;
    }
}

