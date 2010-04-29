{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Send to friend link
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="send-to-friend">
  <a class="send-to-friend product-{product.product_id}" href="{buildURL(#send_friend#,##,_ARRAY_(#product_id#^product.product_id,#isPopup#^#1#))}"><span>Tell a friend</span></a>
</div>
