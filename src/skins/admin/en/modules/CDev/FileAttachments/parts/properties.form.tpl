{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Title
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *
 * @ListChild (list="product.attachments.properties", weight="100", zone="admin")
 *}
<table cellspacing="0" class="form">
  {displayViewListContent(#product.attachments.form#,_ARRAY_(#attachment#^attachment))}
</table>
