/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Minicart controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function xlite_minicart_toggle(id) {
	var el = document.getElementById(id);
  if (el && el.className) {
    if (!el._initialized) {
      $(el).click(
        function (event) {
          event.stopPropagation();
        }
      );
      $(document).click(
        function() {
          if (el.className.search(/expanded/) != -1) {
            xlite_minicart_toggle(el.id);
          }
        }
      );
      el._initialized = true;
    }

  	var c = el.className.replace('collapsed', 'expanded');
	  if (c == el.className) {
		  c = el.className.replace('expanded', 'collapsed');
    }
	  el.className = c;
  }
}
