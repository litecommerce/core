{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods list : left action box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="payment.methods.list.row", weight=100)
 *}

<div class="action left-action">
  {if:canSwitch(method)}
    {if:method.getWarningNote()}

      {if:method.isEnabled()}
        <div class="switch enabled"><img src="images/spacer.gif" alt="{t(#Enabled#)}" /></div>
      {else:}
        <div class="switch disabled" title="{t(#This payment method cannot be enabled until you configure it#)}"><img src="images/spacer.gif" alt="{t(#Disabled#)}" /></div>
      {end:}

    {else:}

      {if:method.isEnabled()}
        <div class="switch enabled"><a href="{buildURL(#payment_settings#,#disable#,_ARRAY_(#id#^method.getMethodId()))}"><img src="images/spacer.gif" alt="{t(#Disable#)}" /></a></div>
      {else:}
        <div class="switch disabled"><a href="{buildURL(#payment_settings#,#enable#,_ARRAY_(#id#^method.getMethodId()))}"><img src="images/spacer.gif" alt="{t(#Enable#)}" /></a></div>
      {end:}

    {end:}

  {else:}

    {if:canEnable(method)}
      <div class="switch enabled" title="{method.getForcedEnabledNote()}"><img src="images/spacer.gif" alt="" /></div>
    {else:}
      <div class="switch disabled" title="{method.getForbidEnableNote()}"><img src="images/spacer.gif" alt="" /></div>
    {end:}

  {end:}

  <img src="images/spacer.gif" class="separator" alt="" />
</div>
