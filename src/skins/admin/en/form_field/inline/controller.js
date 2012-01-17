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

jQuery().ready(
  function () {

    jQuery('.inline-field').each(
      function () {

        // Field properties and methods

        this.viewValuePattern = '.view';

        // Get field position into current line
        this.getPositionIntoLine = function()
        {
          var inlineField = this;
          var inlineFieldIndex = 0;
          jQuery(this).parents('.line').eq(0).find('.inline-field').each(
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
            var field = jQuery(this).parents('.inline-field.editable');
            if (field.length) {
              field.parents('.line').eq(0).addClass('edit-open-mark');
              jQuery(this).next().find(':input').focus().select();
            }
          }
        );

        // Save field into view
        this.saveField = function()
        {
          jQuery(this).find(this.viewValuePattern).html(jQuery(this).find('.field :input').eq(0).val());
        }

        // Sanitize-and-set value into field
        this.sanitize = function()
        {
        }

        // Field input(s)

        var inputs = jQuery('.field :input', this);

        // Input blur effect (initialize save fields group)
        inputs.blur(
          function () {
            var result = !jQuery(this.form).validationEngine('validateField', '#' + this.id);

            if (result) {
              var line = jQuery(this).parents('.line').get(0);
              if (line) {
                line.inlineGroupBlurTimeout = setTimeout(
                  function () {
                    line.inlineGroupBlurTimeout = false;
                    line.saveFields();
                  },
                  100
                );
              }
            }

            return result;
          }
        );

        // Cancel save fields group if focus move to input in this group
        inputs.focus(
          function () {
            var line = jQuery(this).parents('.line').get(0);
            if (line && line.inlineGroupBlurTimeout) {
              clearTimeout(line.inlineGroupBlurTimeout);
              line.inlineGroupBlurTimeout = false;
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

              return jQuery(this).parents('.field').eq(0).find(':input').find(
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

              return jQuery(this).parents('.field').eq(0).find(':input').find(
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
                var line = jQuery(this).parents('.line').eq(0);
                do {
                  line = event.shiftKey ? line.prev('.line') : line.next('.line');
                  if (line.length) {
                    var field = line
                      .find('.inline-field.editable')
                      .eq(jQuery(this).parents('.inline-field').get(0).getPositionIntoLine())
                      .find('.view');
                  }

                } while (line.length && 0 == field.length);

                if (line.length && field.length) {
                  result = false;
                  field.click();
                }
              }
            }

            return result;
          }
        );

        // Line (fields group) methods

        var line = jQuery(this).parents('.line').get(0);

        if (line && typeof(line.saveFields) == 'undefined') {

          line.inlineGroupBlurTimeout = false;

          // Save line fields into views
          line.saveFields = function()
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
          jQuery(line).hover(
            function() {
              jQuery(this).addClass('edit-mark');
            },
            function() {
              jQuery(this).removeClass('edit-mark');
            }
          );
        }        
      }
    );
  }
);
