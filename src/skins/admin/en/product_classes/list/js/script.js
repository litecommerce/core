/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Decoration of the products list widget class
core.bind(
  'load',
  function() {

    // Standard submit event handler is removed (AJAX is used)
    jQuery('#product-class-form').submit(
      function (event) {
        event.preventDefault();
      }
    );

    // Decoration of 'enterValue' method of Advanced Input widget
    decorate(
      'AdvancedInputHandler',
      'enterValue',
      function (widget)
      {
        var prevLabel = widget.label.html();
        arguments.callee.previousMethod.apply(this, arguments);

        if (widget.label.closest('td').hasClass('new-product-class')) {
          widget.label.html(prevLabel);
        }
      }
    );

    // Decoration of 'addActions' method of Advanced Input widget
    decorate(
      'AdvancedInputHandler',
      'addActions',
      function(widget)
      {
        // previous method call
        arguments.callee.previousMethod.apply(this, arguments);

        var o = this;

        widget.input.keypress(
          function (event) {
            if (13 === event.which) {
              event.preventDefault();

              widget.input.attr('readonly', 'readonly');
              jQuery('img.progress', widget.inputBlock).addClass('ajax-progress');
              widget.cancel.hide();

              var id   = widget.input.attr('id').toString().replace(/posteddata-|-name/g, '');
              var name = widget.input.val();

              var action = 'new' === id ? 'add' : 'update';

              jQuery.ajax(
                {
                  type: 'get',
                  url: URLHandler.buildURL({'target': 'product_class', 'action': action, 'name': name, 'id' : id}),
                  timeout: 15000,
                  complete: function(xhr, s) {
                    widget.input.removeAttr('readonly');
                    jQuery('img.progress', widget.inputBlock).removeClass('ajax-progress');
                    widget.cancel.show();
                    widget.cancelValue = name;

                    o.enterValue(widget);

                    if ('add' === action) {
                      o.addNewClass(widget);
                    }

                  }
                }   
              );  // jQuery.ajax
            }
          }
        ); // widget.input.keypress

        var removeActionObject = jQuery('.remove-product-class a.remove', widget.inputBlock.closest('tr'));
        var href = removeActionObject.attr('href');

        removeActionObject.attr('href', 'javascript:void(0);');

        removeActionObject.click(
          function (event) {
            var o = jQuery(this);

            event.stopPropagation();

            jQuery('img.remove', o).removeClass('remove').addClass('ajax-progress');

            jQuery.ajax(
              {
                type: 'get',
                url: href,
                timeout: 15000,
                complete: function(xhr, s) {
                  jQuery('img.ajax-progress', o).addClass('remove').removeClass('ajax-progress');

                  o.closest('tr').slideUp('slow').remove();
                }
              }
            ); // jQuery.ajax
          }
        ); // o.click

      } // addActions method
    ); // 'postprocess' method decoration (EXISTING method)



  }
); // core.bind()


/*

window.core.multiAdd = function (addArea, addObj, removeElement) 
{
  var cloneObj;

  if (cloneObj == undefined) {
    cloneObj = {};
  }

  jQuery(addObj).click(
    function () 
    {
      if (cloneObj[addArea] == undefined) {
        cloneObj[addArea] = jQuery(addArea);
      }

      cloneObj[addArea].clone().append(
        jQuery(removeElement).click(
          function() 
          {
            jQuery(this).closest(addArea).remove();
          }
        )
      )
      .insertAfter(cloneObj[addArea]);
    }
  );
}


*/
