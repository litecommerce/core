{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search summary
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.search.base", weight="10")
 *}
<a IF="getTotalCount()" href="javascript:void(0);" class="dynamic search-orders dynamic-close"><span>Search orders</span><img src="images/spacer.gif" alt="" /></a>
<div class="orders-total">Total: <span>{getTotalCount()}</span> orders{if:getTotalCount()}, found: <span>{getCount()}</span> orders{end:}</div>
