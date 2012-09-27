{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="advanced-input-text">
  <div class="original-label">{t(getLabel())}</div>
  <div class="original-input">
    <widget template="{getDir()}/{getParentFieldTemplate()}" />
    <a class="cancel-input" href="javascript:void(0);">{t(#Cancel#)}</a>
    <img src="images/spacer.gif" class="progress" alt="" />
  </div>
</div>
