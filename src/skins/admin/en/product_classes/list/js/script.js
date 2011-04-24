/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Update with AJAX product class widget
window.core.updateProductClassAJAX = function (obj, widget)
{
  widget.input.attr('readonly', 'readonly');
  jQuery('img.progress', widget.inputBlock).addClass('ajax-progress');
  widget.cancel.hide();

  var id   = core.getProductClassId(widget);
  var name = widget.input.val();
  var action = core.getProductClassAction(widget);

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

        obj.enterValue(widget);

        if ('add' === action) {
          core.addProductClass(widget, jQuery.parseJSON(xhr.responseText).data);
        }
      }
    }
  );  // jQuery.ajax

}

window.core.addProductClass = function (widget, data)
{
  jQuery('img.progress', widget.inputBlock).addClass('ajax-progress');

  jQuery.ajax(
    {
      type: 'get',
      url: URLHandler.buildURL(
        {
          'target': 'product_classes', 
          'widget': '\\XLite\\View\\ProductClass\\MainInput', 
          'className': data.name, 
          'classId' : data.id
        }
      ),
      timeout: 15000,
      complete: function(xhr, s) {

        // TODO!!!! change it
        var newRow = xhr.responseText.match(/<div class="ajax-container-loadable">((\W|\w)+)<\/div>$/)[1];

        jQuery('table.product-classes-list tr:last-child').before(newRow);

        var newHandler = new AdvancedInputHandler();

        newHandler.addActions(newHandler.initWidget(jQuery('table.product-classes-list tbody').children().eq(-2)));

        jQuery('img.progress', widget.inputBlock).removeClass('ajax-progress');
      }
    }
  );
}

// Return product class id from widget
window.core.getProductClassId = function (widget)
{
  return widget.input.attr('id').toString().replace(/posteddata-|-name/g, '');
}

// Return product class action from widget
window.core.getProductClassAction = function (widget)
{
  return 'new' == core.getProductClassId(widget) ? 'add' : 'update';
}

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

              core.updateProductClassAJAX(o, widget);
            }
          }
        ).blur(
          function (event) {
            if (
              widget.cancelValue !== jQuery(this).val()
              && 'update' === core.getProductClassAction(widget)
            ) {
              core.updateProductClassAJAX(o, widget);
            }
            // Clean value if "add new" input is out of focus
            if ('add' === core.getProductClassAction(widget)) {
              jQuery(this).val('');
            }
          }
        );

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
