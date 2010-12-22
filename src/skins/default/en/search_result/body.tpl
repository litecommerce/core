{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search results
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div IF="!getCount()">
  No products found on your query. Please try to re-formulate the query.
</div>

<div IF="getCount()">
  <widget template="products_list/body.tpl" />
</div>
