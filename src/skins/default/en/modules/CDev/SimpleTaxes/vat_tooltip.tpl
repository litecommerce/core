{**
 * Tooltip widget
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="tooltip-main staxes-vat-tooltip">
  {if:isImageTag()}
    <img {getAttributesCode():h} src="images/spacer.gif" alt="Help" />
  {else:}
    <span {getAttributesCode():h}>{getParam(#caption#)}</span>
  {end:}
  <div class="help-text staxes-vat-tooltip-box">
    <ul>
      <li FOREACH="getVATTaxes(),name,value"><span class="name">{name}:</span><span class="value">{formatPrice(value)}</span></li>
    </ul>
  </div>
</div>
<div class="clear"></div>
