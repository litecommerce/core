{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Dialog
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="block block-block">
  <h2 IF="getHead()">{t(getHead())}</h2>
  <div class="content"><widget template="{getBody()}" /></div>
</div>
