{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods list : right action
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="payment.methods.list.row", weight=300)
 *}

<div IF="hasRightActions(method)" class="action right-action">

  <img src="images/spacer.gif" class="separator" alt="" />

  <div IF="canRemoveMethod(method)" class="remove"><a href="{buildURL(#payment_settings#,#remove#,_ARRAY_(#id#^method.getMethodId()))}" title="{t(#Remove#)}"><img src="images/spacer.gif" alt="" /></a></div>
  {if:method.getWarningNote()}
    <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
    <div class="warning"><a href="{method.getConfigurationURL()}"><img src="images/spacer.gif" alt="{method.getWarningNote()}" /></a></div>
  {elseif:!method.isCurrencyApplicable()}
    <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
    <div class="warning"><a href="{buildURL(#currency#)}"><img src="images/spacer.gif" alt="{t(#This method does not support the current store currency and is not available for customers#)}" /></a></div>
  {elseif:method.isTestMode()}
    <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
    <div class="test-mode"><a href="{method.getConfigurationURL()}"><img src="images/spacer.gif" alt="{t(#This method is in test mode#)}" /></a></div>
  {elseif:method.isConfigurable()}
    <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
    <div class="configure"><a href="{method.getConfigurationURL()}" title="{t(#Configure#)}"><img src="images/spacer.gif" alt="" /></a></div>
  {end:}
</div>
