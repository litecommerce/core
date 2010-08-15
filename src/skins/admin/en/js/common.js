/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Common functions
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
var URLHandler = {

  mainParams: {target: true, action: true},

  baseURLPart: 'admin.php?',
  argSeparator: '&',
  nameValueSeparator: '=',

  // Return query param
  getParamValue: function(name, params)
  {
    return name + this.nameValueSeparator + params[name];
  },

  // Get param value for the target and action params
  getMainParamValue: function(name, params)
  {
    return URLHandler.getParamValue(name, params);
  },

  // Get param value for the remained params
  getQueryParamValue: function(name, params)
  {
    return URLHandler.getParamValue(name, params);
  },

  // Build HTTP query
  implodeParams: function(params, method)
  {
    result = '';
    isStarted = false;

    for (x in params) {

      if (isStarted) {
        result += this.argSeparator;
      } else {
        isStarted = true;
      }

      result += method(x, params);
    }

    return result;
  },

  // Implode target and action params
  implodeMainParams: function(params)
  {
    return this.implodeParams(params, this.getMainParamValue);
  },

  // Implode remained params
  implodeQueryParams: function(params)
  {
    return this.implodeParams(params, this.getQueryParamValue);
  },
  
  // Return some params
  getParams: function(params, toReturn)
  {
    result = [];

    for (x in toReturn) {
      result[x] = params[x];
    }

    return result;
  },

  // Unset some params
  clearParams: function(params, toClear)
  {
    result = [];

    for (x in params) {
      if (!(x in toClear)) {
        result[x] = params[x];
      }
    }
    
    return result;
  },

  // Compose target and action
  buildMainPart: function(params)
  {
    return this.implodeMainParams(this.getParams(params, this.mainParams));
  },

  // Compose remained params
  buildQueryPart: function(params)
  {
    return this.argSeparator + this.implodeQueryParams(this.clearParams(params, this.mainParams));
  },

  // Compose URL
  buildURL: function(params)
  {
    return this.baseURLPart + this.buildMainPart(params) + this.buildQueryPart(params);
  }
}

/**
 * Columns selector
 */
$(document).ready(
  function() {
    $('input.column-selector').click(
      function(event) {
        if (!this.columnSelectors) {
          var idx = $(this).parents('th').get(0).cellIndex;
          var table = $(this).parents('table').get(0);
          this.columnSelectors = [];
          for (var r = 0; r < table.rows.length; r++) {
            this.columnSelectors.push($(':checkbox', table.rows[r].cells[idx]).get(0));
          }
          this.columnSelectors = $(this.columnSelectors);
        }

        this.columnSelectors.attr('checked', this.checked ? 'checked' : '');
      }
    );
  }
);

/**
 * Common input validator
 */
function InputValidator(container)
{
  if (!container) {
    return false;
  }

  container = $(container);
  if (!container.length) {
    return false;
  }

  var o = this;

  $(':input', container).each(
    function() {
      o.assignValidator(this);
    }
  );
}

InputValidator.prototype.classRegExp = /^field-(.+)$/;

InputValidator.prototype.assignValidator = function(elm)
{
  if (elm.className && typeof(elm.validators) == 'undefined') {
    elm.validators = [];

    var classes = elm.className.split(/ /);
    var m, methodName;
    for (var i = 0; i < classes.length; i++) {

      m = classes[i].match(this.classRegExp);

      if (m && m[1]) {
        methodName = m[1].replace(/-[a-z]/, this.buildMethodName);
        methodName = 'validate' + methodName.substr(0, 1).toUpperCase() + methodName.substr(1);
        if (typeof(this[methodName]) !== 'undefined') {
          elm.validators[elm.validators.length] = this[methodName];
        }
      }
    }

    if (elm.validators.length) {
      elm.validator = this;
      var o = this;

      elm.labelName = null;
      if (elm.id) {
        var lbl = $('label[for="' + elm.id + '"]').eq(0);
        if (lbl.length) {
          elm.labelName = $.trim(lbl.html()).replace(/:$/, '').replace(/<.+$/, '');
        }
      }
      elm.validate = function(silent) {
        return o.checkElement.call(this, null, silent);
      }

      $(elm).change(
        function(event) {
          return this.validate();
        }
      );

      if (elm.form && !elm.form.validate) {
        elm.form.validate = function() {
          return o.checkForm.call(this);
        }

        $(elm.form).submit(
          function(event) {
            return this.validate();
          }
        );
      }

    }
  }
}

InputValidator.prototype.buildMethodName = function(str)
{
  return str.substr(1).toUpperCase();
}

InputValidator.prototype.checkElement = function(event, silent)
{
  var result = {status: true};

  // Check visibility
  if (0 < this.validators.length) {
    var hidden = $(this).parents().filter(
      function() {
        return this.style.display == 'none';
      }
    );

    if (0 < hidden.length) {
      return true;
    }
  }

  for (var i = 0; i < this.validators.length && result.status; i++) {
    result = this.validators[i].call(this, event);
    if (!result.status) {
      $(this).addClass('validation-error');
      if (!silent) {
        if (this.labelName) {
          result.message = result.message.replace(/Field/, '\'' + this.labelName + '\' field');
        }
        alert(result.message);
        var o = $(this);
        setTimeout(
          function() {
            o.focus();
          },
          200
        );
      }

    } else {
      $(this).removeClass('validation-error');
    }
  }

  return result.status;
}

InputValidator.prototype.checkForm = function()
{
  var result = true;

  $(':input', this).each(
    function() {
      if (this.validate && result) {
        result = this.validate();
      }
    }
  );

  return result;
}

/**
 * Validators
 */

InputValidator.prototype.validateEmail = function()
{
  var re = new RegExp(
    "^[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z](?:[a-z0-9-]*[a-z0-9])?$",
    'gi'
  );

  return {
    status: !this.value.length || this.value.search(re) !== -1,
    message: 'Field is not e-mail address! Please correct'
  };
}

InputValidator.prototype.validateInteger = function()
{
  return {
    status: !this.value.length || this.value.search(/^[-+]?[0-9]+$/) !== -1,
    message: 'Field is not integer! Please correct'
  };
}

InputValidator.prototype.validateFloat = function()
{
  return {
    status: !this.value.length || this.value.search(/^[-+]?[0-9]+\.?[0-9]*$/) !== -1,
    message: 'Field is not float! Please correct'
  };
}

InputValidator.prototype.validatePositive = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 <= value,
    message: 'Field is not positive! Please correct'
  };
}

InputValidator.prototype.validateNegative = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 >= value,
    message: 'Field is not negative! Please correct'
  };
}

InputValidator.prototype.validateNonZero = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 != value,
    message: 'Field is zero! Please correct'
  };
}

InputValidator.prototype.validateRange = function()
{
  var result = {
    status: true,
    message: 'Field is invalid! Please correct'
  };

  var value = parseFloat(this.value);

  if (this.value.length) {
    if (typeof(this.min) !== 'undefined' && this.min > value) {
      result.status = false;
      result.message = 'Field too small!';
    }

    if (typeof(this.max) !== 'undefined' && this.max < value) {
      result.status = false;
      result.message = 'Field too big!';
    }
  }

  return result;
}

InputValidator.prototype.validateRequired = function()
{
  return {
    status: this.value !== null && 0 < this.value.length,
    message: 'Field is required!'
  };
}

$(document).ready(
  function() {
    new InputValidator(document);
  }
);

// Dialog

// Abstract open dialog
function openDialog(selector, additionalOptions)
{
  if (!$('.ui-dialog ' + selector).length) {
    var options =  {
      dialogClass: 'popup',
      draggable: false,
      modal: true,
      resizable: false,
      height: 500,
      open: function(event) {
        $('.ui-dialog').css(
          {
            overflow: 'visible',
          }
        );
      }
    }

    if (additionalOptions) {
      for (var k in additionalOptions) {
        options[k] = additionalOptions[k];
      }
    }

    $(selector).dialog(options);

  } else {
    $(selector).dialog('open');
  }
}

// Loadable dialog
function loadDialog(url, dialogOptions, callback)
{
  openWaitBar();

  var selector = 'tmp-dialog-' + (new Date()).getTime();

  $.get(
    url,
    {},
    function(data, status, ajax) {
      if (data) {
        var div = $(document.body.appendChild(document.createElement('div')))
          .hide()
          .html($.trim(data));
        if (1 == div.get(0).childNodes.length) {
          div = $(div.get(0).childNodes[0]);
        }

        div.addClass(selector);

        openDialog('.' + selector, dialogOptions);
        closeWaitBar();

        if (callback) {
          callback();
        }
      }
    }
  );

  return '.' + selector;
}

// Load dialog by link
function loadDialogByLink(link, url, options)
{
  if (!link.linkedDialog) {
    link.linkedDialog = loadDialog(url, options);

  } else {
    openDialog(link.linkedDialog, options);
  }
}

function openWaitBar()
{
  if (typeof(window._waitBar) == 'undefined') {
    window._waitBar = document.body.appendChild(document.createElement('div'));
    var selector = 'wait-bar-' + (new Date()).getTime();
    window._waitBar.style.display = 'none';
    window._waitBar.className = 'wait-box ' + selector;

    var box = window._waitBar.appendChild(document.createElement('div'));
    box.className = 'box';

    window._waitBar = '.' + selector;
  }

  if ($('.ui-dialog ' + window._waitBar).length) {
    $(window._waitBar).dialog('open');

  } else {

    var options =  {
      dialogClass:   'popup',
      draggable:     false,
      modal:         true,
      resizable:     false,
      closeOnEscape: false,
      minHeight:     11,
      width:         200,
      open:          function() {
        $(window._waitBar).css('min-height', 'auto');
        $('.ui-dialog-titlebar-close', $(window._waitBar).parents('.ui-dialog').eq(0)).remove();
      }
    };

    $(window._waitBar).dialog(options);
  }
}

function closeWaitBar()
{
  if (typeof(window._waitBar) != 'undefined') {
    $(window._waitBar).dialog('close');
  }
}

// Check for the AJAX support
function hasAJAXSupport()
{
  if (typeof(window.ajaxSupport) == 'undefined') {
    window.ajaxSupport = false;
    try {

      var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
      window.ajaxSupport = xhr ? true : false;

    } catch(e) { }
  }

  return window.ajaxSupport;
}

function normalizeSelect(name) {
	var tmp = document.getElementById(name);
	if (tmp)
		tmp.options[tmp.options.length-1] = null;
}
				

function moveSelect(left, right, type) {
	if (type != 'R') {
		var tmp = left;
		left = right;
		right = tmp;
	}
	if (!left || !right)
		return false;

	while (right.selectedIndex != -1) {
		left.options[left.options.length] = new Option(right.options[right.selectedIndex].text, right.options[right.selectedIndex].value);
		right.options[right.selectedIndex] = null;
	}

	return true;
}

function saveSelects(objects) {
	if (!objects)
		return false;

	for (var sel = 0; sel < objects.length; sel++) {
		if (document.getElementById(objects[sel]))
			if (document.getElementById(objects[sel]+"_store").value == '')
				for (var x = 0; x < document.getElementById(objects[sel]).options.length; x++)
					document.getElementById(objects[sel]+"_store").value += document.getElementById(objects[sel]).options[x].value+";";
	}
	return true;
}

// Check list of checkboxes
function checkMarks(form, reg, lbl) {
	var is_exist = false;

	if (!form || form.elements.length == 0)
		return true;

	for (var x = 0; x < form.elements.length; x++) {
		if (form.elements[x].name.search(reg) == 0 && form.elements[x].type == 'checkbox' && !form.elements[x].disabled) {
			is_exist = true;

			if (form.elements[x].checked)
				return true;
		}
	}

	if (!is_exist)
		return true;

	if (lbl) {
		alert(lbl);

	} else if (lbl_no_items_have_been_selected) {
		alert(lbl_no_items_have_been_selected);

	}

	return false;
}

