/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Blocks settings controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

jQuery().ready(
  function() {
    jQuery('form select').filter(

      function() {
        return -1 != this.name.search(/lc_widget.lc_block_XLite_.+_ItemsList_Product_Customer_.+..widgetType./);
      }

    ).change(

      function() {

        // Detect related select-box with display modes
        if (!this.displayMode) {
          var select = this.name.replace(/widgetType/, 'displayMode');
          this.displayMode = jQuery('select', this.form).filter(
            function() {
              return this.name == select;
            }
          ).get(0);
        }

        // Remove old options
        while (0 < this.displayMode.options.length) {
          this.displayMode.options[0] = null;
        }

        // Add new options
        var displayMode = this.displayMode;
        jQuery.each(
          lcConnectorBlocks[this.options[this.selectedIndex].value],
          function(k, v) {
            displayMode.options[displayMode.options.length] = new Option(v, k);
          }
        );
      }

    );
  }
);

