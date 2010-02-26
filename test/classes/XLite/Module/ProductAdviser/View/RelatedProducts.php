<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_Module_ProductAdviser_View_RelatedProducts
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Module_ProductAdviser_View_RelatedProducts extends XLite_View_Dialog
{
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('product');

	/**
	 * Available display modes list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $displayModes = array(
		'list'  => 'List',
        'icons' => 'Icons',
        'table' => 'Table'
	);


	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'Related products';
	}

	/**
	 * Get widget directory
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getDir()
	{
        return 'modules/ProductAdviser/RelatedProducts/' . $this->getDisplayMode();
	}

	/**
     * Get widget display mode parameter (menu | dialog)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
		return $this->config->ProductAdviser->rp_template;
    }

    protected function checkRelatedProducts()
    {
        $rp = $this->getComplex('product.RelatedProducts');
        return !empty($rp);
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        $return = parent::isVisible()
            && ('Y' == $this->config->ProductAdviser->related_products_enabled)
            && $this->checkRelatedProducts()
            && empty($this->page);

/*        
        echo "related_products_enabled: '" . $this->config->ProductAdviser->related_products_enabled . "'<br>";
        echo "product_id: '" . $this->product_id . "'<br>";
        echo "product.RelatedProducts: '" . $this->getComplex('product.RelatedProducts') . "'<br>";
//        echo "checkRelatedProducts(): '" . $this->checkRelatedProducts() . "'<br>";
        echo "page: '" . $this->page . "'<br>";
 */
        return $return;
    }

}
