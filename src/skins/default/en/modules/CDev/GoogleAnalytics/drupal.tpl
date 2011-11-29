{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Header
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<script type="text/javascript">
if (typeof(window._gaq) != 'undefined') {
{foreach:getGatCommands(),command}
  _gaq.push([{command:h}]);

{end:}
}
</script>
