{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Applied gift certificate row
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.totals", weight="50")
 *}
<li IF="!cart.payedByGC=0" class="paid-gc">
  <em>
    Paid with GC:
    <widget IF="!target=#checkout#" class="\XLite\Module\GiftCertificates\View\Form\GiftCertificate\Remove" name="remove_gc" />
      <widget class="\XLite\View\Button\Image" label="Remove GC" action="remove_gc" />
    <widget IF="!target=#checkout#" name="remove_gc" end />
  </em>
  {price_format(cart,#payedByGC#):h}
</li>
