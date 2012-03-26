/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Button minicontroller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 */

jQuery().ready(
  function() {
    core.bind(
      'widgetLoad',
      function (event, widget) {
        if ('\\XLite\\View\\Product\\Details\\Customer\\Page\\Main' == widget.widgetClass) {
          FB.XFBML.parse(jQuery('div.fb-like').parent().get(0));
        }
      }
    );
  }
);
