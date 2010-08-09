{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Change options
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\Module\ProductOptions\View\Form\Item\ChangeOptions" name="change_options" className="change-options" />

  <h1>Edit options</h1>

  <widget class="\XLite\Module\ProductOptions\View\ProductOptions" product="{getProduct()}" selectedOptions="{getSelectedOptions()}" />

  <widget class="\XLite\View\Button\Submit" label="Update" />

<widget name="change_options" end />
