{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Additional buttons list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="additional-buttons">
  <div FOREACH="getAdditionalButtons(),i,button" class="{getSubcellClass(buttonArrayPointer,i,button)}" >{button.display():h}</div>
</div>

