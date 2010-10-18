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

<div class="address-dialog">

  <h2 IF="{address}">{t(#Change address#)}</h2>

  <h2 IF="{!address}">{t(#New address#)}</h2>

  <widget IF="{address}" class="\XLite\View\Model\Address\Address" modelObject="{address}" useBodyTemplate="1" />

  <widget IF="{!address}" class="\XLite\View\Model\Address\Address" profile_id="{profile_id}" useBodyTemplate="1" />

</div>

