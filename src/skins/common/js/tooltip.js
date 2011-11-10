/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Tooltip widget JS class
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function Tooltip()
{
  var obj = this;

  jQuery(this.pattern).each(
    function () {
      attachTooltip(
        jQuery(obj.caption, this),
        jQuery('.help-text', this).hide().html()
      );
    }
  );
}

Tooltip.prototype.pattern = '.tooltip-main';
Tooltip.prototype.caption = '.tooltip-caption';

// Autoloading new Tooltip widget
core.autoload(Tooltip);
