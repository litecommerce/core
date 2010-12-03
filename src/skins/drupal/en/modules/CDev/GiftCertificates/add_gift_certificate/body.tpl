{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add / update gift certificate
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script type="text/javascript">
var gcMinAmount = {config.GiftCertificates.minAmount};
var gcMaxAmount = {config.GiftCertificates.maxAmount};
var bordersDir = '{gc.bordersDir}';
</script>

<widget class="\XLite\Module\CDev\GiftCertificates\View\Form\GiftCertificate\Add" name="addgc" className="gift-certificate" />

  {displayViewListContent(#giftcert.childs#)}

<widget name="addgc" end />
