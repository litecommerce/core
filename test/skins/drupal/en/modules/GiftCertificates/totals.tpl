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
 *}
<li IF="!cart.payedByGC=0" class="paid-gc">
  <em>
    Paid with GC:
    <widget class="XLite_Module_GiftCertificates_View_Form_GiftCertificate_Remove" name="remove_gc" />
      <widget class="XLite_View_Button_Image" label="Remove GC" />
    <widget name="remove_gc" end />
  </em>
  {price_format(cart,#payedByGC#):h}
</li>
