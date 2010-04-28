{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Extended options widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
var extendedOptionsNames = [{getOptionNamesAsJavascript()}];

var extendedOptionsData = [
{foreach:product.expandedItems,key,opts}
  { key: '{getOptionsAsJavascript(opts)}', quantity: {getOptionQuantityRange(key)} },
{end:}
  false
];

$(document).ready(
  function() {
    new extendedOptionsController($('.product-details'), extendedOptionsNames, extendedOptionsData);
  }
);
</script>
