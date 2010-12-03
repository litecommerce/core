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
<script language="JavaScript" IF="product.hasOptionValidator()" type="text/javascript">
var optionsCount = {count(product.productOptions)};
<!--
function product_option(name_of_option)
{
    for(i = 0; i < optionsCount; i++) {
        for (element = 0; element < document.add_to_cart.elements.length; element++) {
            if (document.add_to_cart.elements[element].name.search(name_of_option) != -1) {
                return document.add_to_cart.elements[element];
            }    
        }    
    }        
    return -1;       
}

function isValid()
{   
// -->

{validatorJSCode:h}

// <!--
}
// -->
</script>
