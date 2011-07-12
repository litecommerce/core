{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment method row
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
{if:method.processor.getIconPath(order,method)}
  <img src="{preparePaymentMethodIcon(method.processor.getIconPath(order,method))}" alt="{method.getName()}" />
{else:}
  {if:method.getDescription()}{method.getDescription()}{else:}{method.getName()}{end:}
{end:}
