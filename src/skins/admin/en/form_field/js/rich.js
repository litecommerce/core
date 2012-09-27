/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Multiselect microcontroller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonElement.prototype.handlers.push(
  {
    canApply: function () {
      return 0 < this.$element.filter('select.rich').length;
    },
    handler: function () {
      var options = { minWidth: this.$element.width(), header: false, multiple: false, selectedList: 1 };
      if (this.$element.data('text')) {
        options.selectedText = this.$element.data('text');
      }
      if (this.$element.data('header')) {
        options.header = true;

      } else if (this.$element.data('filter')) {
        options.header = 'close';
      }

      if (this.$element.data('filter')) {
        options.classes = 'ui-multiselect-with-filter';
      }

      this.$element.multiselect(options);

      if (this.$element.data('filter')) {
        var options = {placeholder: this.$element.data('filter-placeholder')};
        this.$element.multiselectfilter(options);
        jQuery('.ui-multiselect-filter').each(
          function () {
            if (3 == this.childNodes[0].nodeType) {
              this.removeChild(this.childNodes[0]);
            }
          }
        );

      }
    }
  }
);
