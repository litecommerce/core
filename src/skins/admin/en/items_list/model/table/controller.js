/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Items list controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Items list controller
 */
function TableItemsList(cell, URLParams, URLAJAXParams)
{
  TableItemsList.superclass.constructor.apply(this, arguments);
}

extend(TableItemsList, ItemsList);

TableItemsList.prototype.form = null;

// Set a param and send the request
TableItemsList.prototype.process = function(paramName, paramValue)
{
  var form = this.container.parents('form').get(0);

  var result = true;

  if (!form || !form.commonController.isChanged(true)) {
    result = TableItemsList.superclass.process.apply(this, arguments);
  }

  return result;
}

// Place new list content
TableItemsList.prototype.placeNewContent = function(content)
{
  TableItemsList.superclass.placeNewContent.apply(this, arguments);
  var form = this.container.parents('form').get(0);
  if (form) {
    form.bindElements();
    form.change();
  }
}

// Pager listener
TableItemsList.prototype.listeners.pager = function(handler)
{
  jQuery('.table-pager .input input', handler.container).change(
    function() {
      return !handler.process('pageId', this.value - 1);
    }
  );

  jQuery('.table-pager a', handler.container).click(
    function() {
      return !(jQuery(this).hasClass('disabled') || handler.process('pageId', jQuery(this).data('pageId')));
    }
  );

}

// Item per page input listener
TableItemsList.prototype.listeners.pagesCount = function(handler)
{
  jQuery('select.page-length', handler.container).change(
    function() {
      return !handler.process('itemsPerPage', this.options[this.selectedIndex].value);
    }
  );
}

// Form listener
TableItemsList.prototype.listeners.form = function(handler)
{
  var form = handler.container.parents('form').eq(0);

  if (form.get(0)) {
    form.get(0).commonController.submitOnlyChanged = true;
  }

  form.bind(
    'state-changed',
    function () {
      handler.processFormChanged(jQuery(this));
    }
  );
  form.bind(
    'state-initial',
    function () {
      handler.processFormUndo(jQuery(this));
    }
  );

}

// Process form and form's elements after form changed
TableItemsList.prototype.processFormChanged = function(form)
{
  this.container.find('.table-pager .input input, .table-pager .page-length').each(
    function () {
      jQuery(this).attr('disabled', 'disabled');
      this.setAttribute('disabled', 'disabled');
    }
  );

  this.container.find('.table-pager a').addClass('disabled').removeClass('enabled');
}

// Process form and form's elements after form cancel all changes
TableItemsList.prototype.processFormUndo = function(form)
{
  this.container.find('.table-pager .input input, .table-pager .page-length').removeAttr('disabled');
  this.container.find('.table-pager a').removeClass('disabled').addClass('enabled');
}

// Inline creation button listener
TableItemsList.prototype.listeners.createButton = function(handler)
{
  jQuery('button.create-inline', handler.container)
    .removeAttr('onclick')
    .click(
      function (event) {

        event.stopPropagation();

        var box = jQuery('tbody.create', handler.container);
        var length = box.find('.line').length;
        var idx = length + 1;
        var line = box.find('.create-tpl').clone(true);
        line
          .show()
          .removeClass('create-tpl')
          .addClass('create-line')
          .addClass('line')
          .find(':input').each(
            function () {
              if (this.id) {
                this.id = this.id.replace(/-0-/, '-n' + idx + '-');
              }
              this.name = this.name.replace(/\[0\]/, '[' + (-1 * idx) + ']');
            }
          );

        box.append(line);

        var form = box.parents('form').get(0);
        if (form) {
          form.commonController.bindElements();
        }

        jQuery('.no-items').css('display', 'none');

        if (2 == box.children('tr').length) {
          var leftAction = jQuery('tbody.lines tr td.actions.left', handler.container).eq(0);
          if (leftAction.length) {
            line.find('td.actions.left').width(leftAction.width())
          }

          var rightAction = jQuery('tbody.lines tr td.actions.right', handler.container).eq(0);
          if (rightAction.length) {
            line.find('td.actions.right').width(rightAction.width())
          }
        }

        return false;
      }
    );
}

// Remove button for inline creation listener
TableItemsList.prototype.listeners.removeLineButton = function(handler)
{
  jQuery('.create-tpl button.remove', handler.container).click(
    function () {
      if (0 == jQuery(this).parents('tr.create-tpl').length) {
        jQuery(this).parents('tr').eq(0).remove();
        if (0 == jQuery('tr.create-line').length && 0 == jQuery('tr.line').length) {
          jQuery('.no-items').css('display', '');
        }
      }
    }
  );
}

// Selector actions
TableItemsList.prototype.listeners.selector = function(handler)
{
  jQuery('.actions div.selector', handler.container).click(
    function () {
      var input = jQuery('input', this).get(0);
      input.checked = !input.checked;
      jQuery('input', this).change();
    }
  );

  jQuery('.actions input.selector', handler.container).click(
    function (event) {
      event.stopPropagation();
    }
  );

  jQuery('.actions input.selector', handler.container).change(
    function () {
      var box = jQuery(this).parent('div.selector');

      if (this.checked) {
        box.addClass('checked');

      } else {
        box.removeClass('checked');
      }

      return true;
    }
  );
}

// Position changed
TableItemsList.prototype.listeners.positionChanged = function(handler)
{
  jQuery('tbody.lines', handler.container).bind(
    'positionChange',
    function () {
      var i = 0;
      var length = jQuery(this).find('.lines').length;
      jQuery(this).find('.lines').each(
        function () {
          var tr = jQuery(this);

          if (0 == i) {
            tr.addClass('first');
          } else {
            tr.removeClass('first');
          }

          if (length - 1 == i) {
            tr.addClass('last');
          } else {
            tr.removeClass('last');
          }

          if (0 == (i + 1) % 2) {
            tr.addClass('even');
          } else {
            tr.removeClass('even');
          }
        }
      );
    }
  );
}

// Head sort
TableItemsList.prototype.listeners.headSort = function(handler)
{
  jQuery('thead th a.sort', handler.container).click(
    function() {
      return jQuery(this).hasClass('current-sort')
        ? !handler.process('sortOrder', 'asc' == jQuery(this).data('direction') ? 'desc' : 'asc')
        : !handler.process('sortBy', jQuery(this).data('sort'));
    }
  );
}

// Head search
TableItemsList.prototype.listeners.headSearch = function(handler)
{
  jQuery('tbody.head-search input,tbody.head-search select', handler.container).change(
    function() {
      var result = false;
      jQuery(this).parents('td').eq(0).find('input,select,textarea').each(
        function () {
          result = handler.setURLParam(this.name, this.value) || result;
        }
      );

      if (result) {
        handler.loadWidget();
      }

      return false;
    }
  );
}

// Reassign items list controller
TableItemsList.prototype.reassign = function()
{
  new TableItemsList(this.params.cell, this.params.urlparams, this.params.urlajaxparams);
}


function TableItemsListQueue()
{
  jQuery('.widget.items-list').each(function(index, elem){
    new TableItemsList(jQuery(elem));
  });
}

core.autoload(TableItemsListQueue);
