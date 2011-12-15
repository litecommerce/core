{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product search form template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.product.customer.search", weight="100")
 * @ListChild (list="itemsList.product.grid.customer.search", weight="100")
 * @ListChild (list="itemsList.product.list.customer.search", weight="100")
 * @ListChild (list="itemsList.product.table.customer.search", weight="100")
 *}

<div class="search-product-form">
  <widget class="\XLite\View\Form\Product\Search\Customer\Main" name="products_search" />

  <div class="search-form main">
    {displayViewListContent(#itemsList.product.search.form.main#)}
  </div>

  <div class="search-form options" id="advanced_search_options">
    {displayViewListContent(#itemsList.product.search.form.options#)}
  </div>

  <widget name="products_search" end />
</div>
