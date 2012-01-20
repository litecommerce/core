/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Inline form field common controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

CommonForm.elementControllers.push(
  {
    pattern: '.inline-field',
    handler: function () {

      // Field properties and methods

      var field = jQuery(this);

      this.viewValuePattern = '.view';
      var line = field.parents('.line').eq(0);
      var row = line.get(0);
      var inputs = jQuery('.field :input', this);

      // Get field position into current line
      this.getPositionIntoLine = function()
      {
        var inlineField = this;
        var inlineFieldIndex = 0;
        line.find('.inline-field').each(
          function (index) {
            if (this == inlineField) {
              inlineFieldIndex = index;
            }
          }
        );

        return inlineFieldIndex;
      }

      // View click effect (show field and hide view)
      jQuery('.view', this).click(
        function() {
          if (field.hasClass('editable') && !field.parents('.line').hasClass('remove-mark')) {
            line.addClass('edit-open-mark');
            jQuery(this).next().find(':input').eq(0).focus().select();
          }
        }
      );

      // Save field into view
      this.saveField = function()
      {
        field.find(this.viewValuePattern).html(inputs.eq(0).val());
      }

      // Sanitize-and-set value into field
      this.sanitize = function()
      {
      }

      // Field input(s)

      inputs.bind(
        'undo',
        function () {
          field.get(0).saveField();
        }
      );

      // Input blur effect (initialize save fields group)
      inputs.blur(
        function () {
          var result = !jQuery(this.form).validationEngine('validateField', '#' + this.id);

          if (result && row) {
            row.inlineGroupBlurTimeout = setTimeout(
              function () {
                row.inlineGroupBlurTimeout = false;
                row.saveFields();
              },
              100
            );
          }

          return result;
        }
      );

      // Cancel save fields group if focus move to input in this group
      inputs.focus(
        function () {
          if (row && row.inlineGroupBlurTimeout) {
            clearTimeout(row.inlineGroupBlurTimeout);
            row.inlineGroupBlurTimeout = false;
          }
        }
      );

      // Input methods
      inputs.each(
        function () {

          var current = this;

          // Get next inputs into thid field
          this.getNextInputs = function()
          {
            var found = false;

            return field.find('.field :input').find(
              function () {
                if (this == current) {
                  found = true;
                }

                return found && this != current;
              }
            );
          }

          // Get previous inputs into thid field
          this.getPreviousInputs = function()
          {
            var found = true;

            return field.find('.field :input').find(
              function () {
                if (this == current) {
                  found = false;
                }

                return found;
              }
            );
          }

        }
      );

      // Move focus to next field in this column (if axists)
      inputs.keypress(
        function (event) {
          var result = true;

          // Press 'Tab' button
          if (9 == event.keyCode) {
            var found = false;
            var current = this;

            var target = event.shiftKey ? this.getPreviousInputs() : this.getNextInputs();
            if (0 < target.length) {

              // Go to target (next / previous) input into current inline-field box
              target.eq(0).focus();

            } else {

              // Go to similar inline-field into next / previous line
              var l = line;
              var f;
              do {
                l = event.shiftKey ? l.prev('.line') : l.next('.line');
                if (l.length) {
                  var f = l
                    .find('.inline-field.editable')
                    .eq(jQuery(this).parents('.inline-field').get(0).getPositionIntoLine())
                    .find('.view');
                }

              } while (l.length && 0 == f.length);

              if (l.length && f.length) {
                result = false;
                f.click();
              }
            }
          }

          return result;
        }
      );

      // Line (fields group) methods

      if (row && typeof(row.saveFields) == 'undefined') {

        row.inlineGroupBlurTimeout = false;

        // Save line fields into views
        row.saveFields = function()
        {
          jQuery('.inline-field', this).each( 
            function () {
              this.sanitize();
              this.saveField();
            }
          );
          jQuery(this).removeClass('edit-open-mark');
        }

        // Add line hover effect
        line.hover(
          function() {
            jQuery(this).addClass('edit-mark');
          },
          function() {
            jQuery(this).removeClass('edit-mark');
          }
        );
      }

    }
  }
);
