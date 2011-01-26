{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pack button warning text
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="warn-pack">
{t(#Probably you need to change "phar.readonly" variable. It should be equal to "Off". Please advise about server configuration with your hosting service provider#)}
</div>
<script type="text/javascript">
<!--
jQuery(document).ready(
  function () {
    jQuery('.popup-link').cluetip(
    {
      local: true,
      showTitle: false
    }
    );
  }
);
-->
</script>
