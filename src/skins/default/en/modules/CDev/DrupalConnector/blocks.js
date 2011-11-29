/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Blocks settings controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

jQuery().ready(
  function() {

    var processRecommendedSizes = function(displayMode) {
      var widgetType = jQuery('select[name="' + displayMode.name.replace(/displayMode/, 'widgetType') + '"]', displayMode.form);
      displayMode = jQuery(displayMode);

      if (widgetType.length && displayMode.length) {
        var key = widgetType.get(0).value + '.' + displayMode.get(0).value;
        widgetType = widgetType.get(0);
        var wkey = widgetType.name.replace(/^lc_widget\[(.+)\]\[.+$/, '$1');

        var width = jQuery('input[name="' + widgetType.name.replace(/widgetType/, 'iconWidth') + '"]', widgetType.form);
        var descWidth = width.parents('.form-item').find('.description').eq(0);
        var height = jQuery('input[name="' + widgetType.name.replace(/widgetType/, 'iconHeight') + '"]', widgetType.form);
        var descHeight = height.parents('.form-item').find('.description').eq(0);

        if (
          typeof(window.lcConnectorRecommendedIconSizes[wkey]) != 'undefined'
          && typeof(lcConnectorRecommendedIconSizes[wkey][key]) != 'undefined'
        ) {
          var size = lcConnectorRecommendedIconSizes[wkey][key];

          descWidth.show().html(lcConnectorRecommendedLabel.replace(/!size/, size[0]));
          descHeight.show().html(lcConnectorRecommendedLabel.replace(/!size/, size[1]));
          width.removeAttr('disabled', 'disabled');
          height.removeAttr('disabled', 'disabled');

        } else {
          descWidth.hide();
          descHeight.hide();
          width.attr('disabled', 'disabled');
          height.attr('disabled', 'disabled');
        }
      }
    }

    jQuery('form select').filter(

      function() {
        return -1 != this.name.search(/lc_widget.lc_block_XLite_.+\[widgetType\]/);
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

        if (this.displayMode) {

          var key = this.name.replace(/^lc_widget\[(.+)\]\[.+$/, '$1');
          if (typeof(lcConnectorBlocks[key]) != 'undefined') {

            // Remove old options
            var selected = this.displayMode.options[this.displayMode.selectedIndex].text;
            while (0 < this.displayMode.options.length) {
              this.displayMode.options[0] = null;
            }

            // Add new options
            var displayMode = this.displayMode;
            jQuery.each(
              lcConnectorBlocks[key][this.options[this.selectedIndex].value],
              function(k, v) {
                displayMode.options[displayMode.options.length] = new Option(v, k);

                if (selected && selected == v) {
                  displayMode.selectedIndex = displayMode.options.length - 1;
                }
              }
            );

            jQuery(displayMode).change();
          }

          // Show / hide some settings
          var isShow = 'sidebar' != this.options[this.selectedIndex].value;
          var elements = [
            jQuery('input[name="' + this.name.replace(/widgetType/, 'showDisplayModeSelector') + '"]', this.form).parents('.form-item').eq(0),
            jQuery('select[name="' + this.name.replace(/widgetType/, 'gridColumns') + '"]', this.form).parents('.form-item').eq(0),
            jQuery('input[name="' + this.name.replace(/widgetType/, 'showItemsPerPageSelector') + '"]', this.form).parents('.form-item').eq(0),
            jQuery('input[name="' + this.name.replace(/widgetType/, 'showSortBySelector') + '"]', this.form).parents('.form-item').eq(0)
          ];

          for (var i = 0; i < elements.length; i++) {
            var elm = elements[i];
            if (isShow) {
              elm.show();

            } else {
              elm.hide();
            }
          }
        }
      }

    );

    jQuery('form select').filter(

      function() {
        return -1 != this.name.search(/lc_widget.lc_block_XLite_.+\[displayMode\]/);
      }

    ).change(

      function() {

        var name = this.name.replace(/displayMode/, 'gridColumns');

        var select = jQuery('select[name="' + name + '"]', this.form).eq(0);

        if (select.length) {
          if ('grid' == this.options[this.selectedIndex].value) {
            select.removeAttr('disabled');

          } else {
            select.attr('disabled', 'disabled');
          }
        }

        processRecommendedSizes(this);
      }
    );

    jQuery('form select').filter(
      function() {
        return -1 != this.name.search(/lc_widget.lc_block_XLite_.+\[widgetType\]/);
      }
    ).change();
  }
);

