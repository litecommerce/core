{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Simple form for searching products template
 *   
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="simple-search-product-form">
  <widget class="\XLite\View\Form\Product\Search\Customer\Main" name="simple_products_search" />
    <input type="text" class="form-text" size=30 name="substring" value="{substring}" />
  <widget name="simple_products_search" end />
</div>
