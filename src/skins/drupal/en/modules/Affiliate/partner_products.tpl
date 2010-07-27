{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget template="modules/Affiliate/dialog.tpl" head="Search product" body="modules/Affiliate/product_search.tpl">

<span class="Text" IF="mode=#search#">{productsFound} product(s) found</span>

<widget template="modules/Affiliate/dialog.tpl" head="Search results" body="modules/Affiliate/product_list.tpl" IF="{mode=#search#&products}">
