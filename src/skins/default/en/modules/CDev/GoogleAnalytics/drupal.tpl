{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Header
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<script type="text/javascript">
if (typeof(window._gaq) != 'undefined') {
{foreach:getGatCommands(),command}
  _gaq.push([{command:h}]);

{end:}
}
</script>
