/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Cart / checkout additional controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.8
 */
core.decorate(
  'ProductDetailsView',
  'postprocess', 
  function(isSuccess, initial)
  {
    arguments.callee.previousMethod.apply(this, arguments);

    if (isSuccess) {

      jQuery('.tooltip-main', this.base).each(
        function() {
          var link = jQuery(this).find('.tooltip-caption');
          attachTooltip(link, jQuery(this).find('.help-text').html());
        }
      );
    }
  }
);
