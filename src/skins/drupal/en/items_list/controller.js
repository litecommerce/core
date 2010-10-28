/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Main class
function ListsController(base)
{
  ListsController.superclass.constructor.apply(this, arguments);

  if (this.base && this.base.length) {
    this.block = this.getListView();
  }
}

extend(ListsController, AController);

ListsController.prototype.name = 'ItemsListController';

ListsController.prototype.findPattern = '.items-list';

ListsController.prototype.block = null;

ListsController.prototype.selfUpdated = false;

ListsController.prototype.getListView = function()
{
  return new ListView(this.base);
}

// Initialize controller
ListsController.prototype.initialize = function()
{
  var o = this;

  this.base.bind(
    'reload',
    function(event, box) {
      o.bind(box);
    }
  );
}

/**
 * Main widget (listView) 
 */

function ListView(base)
{
  ListView.superclass.constructor.apply(this, arguments);

  this.widgetClass  = core.getCommentedData(base, 'widget_class');
  this.widgetTarget = core.getCommentedData(base, 'widget_target');
}
 
extend(ListView, ALoadable);
 
ListView.prototype.shadeWidget = true;

ListView.prototype.sessionCell = null;

ListView.prototype.postprocess = function(isSuccess, initial)
{
  ListView.superclass.postprocess.apply(this, arguments);

  if (isSuccess) {

    var o = this;

    // Register page click handler
    $('.pager a', this.base).click(
      function() {
        return !o.load({'pageId': core.getValueFromClass(this, 'page')});
      }
    );

    // Register page count change handler
    $('input.page-length', this.base).change(
      function() {
        count = parseInt($(this).val());

        if (isNaN(count)) {
          //TODO We must take it from the previous widget parameters ... 
          count = 10;
        } else if (count < 1) {
          count = 1;
        }

        if (count != $(this).val()) {
          $(this).val(count);
        }

        return !o.load({'itemsPerPage': count});
      }
    );

    // Fix for Opera. Opera does not interpret onChange event if client presses Enter key.
    if ($.browser.opera) {
      $('input.page-length', this.base).bind('keypress',
        function (e) {
          if (e.keyCode == '13') {
            $(this).change();
          }
        }
      );
    }

  }
}

