/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Common form / element controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * Form
 */

// Constructor
function CommonForm(form)
{
  if (!form) {
    return false;
  }

  form = jQuery(form).filter('form').eq(0);
  if (!form.length || form.get(0).commonController) {
    return false;
  }

  this.form = form.get(0);
  this.$form = form;

  var o = this;

  this.form.commonController = this;

  var methods = [
    'validate',    'submitBackground', 'isChanged',
    'getElements'
  ];

  for (var i = 0; i < methods.length; i++) {
    var method = methods[i];
    this.form[method] = new Function('', 'return this.commonController.' + method + '.apply(this.commonController, arguments);');
  }

  this.form.isBgSubmitting = false;

  // Central validation before form submit and background submit
  form.submit(
    function(event)
    {
      if (this.isBgSubmitting) {
        return false;
      }

      var result = this.validate();

      if (result && o.submitOnlyChanged && !o.isChanged(true)) {
        result = false;
      }

      if (result && o.backgroundSubmit) {
        var e = jQuery.Event('beforeSubmit');
        o.$form.trigger(e);

        if (false !== e.result && o.submitBackground(null, true)) {
          result = false;
        }
      }

      return result;
    }
  );

  this.bindElements();

  // Process invalidElement event
  core.bind(
    'invalidElement',
    function(event, data) {
      if (o.form.isBgSubmitting && o.form.elements.namedItem(data.name)) {
        o.form.elements.namedItem(data.name).markAsInvalid(data.message, null, true);
      }
    }
  );

}

extend(CommonForm, Base);

// Autoload class method
CommonForm.autoload = function()
{
  jQuery('form').each(
    function() {
      new CommonForm(this);
    }
  );
}

// Form DOM element
CommonForm.prototype.form = null;

// Form jQuery element
CommonForm.prototype.$form = null;

/**
 * Options
 */

// Auto-submit form in background mode
CommonForm.prototype.backgroundSubmit = false;

// Submit form ony if form changed
CommonForm.prototype.submitOnlyChanged = false;

// POST request as RPC-request
CommonForm.prototype.postAsRPC = true;

// GET request as RPC-request
CommonForm.prototype.getAsRPC = false;

// Get elements jQuery collection
CommonForm.prototype.getElements = function()
{
  return this.$form.find(':input');
}

// Bind form elements
CommonForm.prototype.bindElements = function()
{
  this.getElements().each(
    function() {
      new CommonElement(this);
    }
  );
}

// Vaidate form
CommonForm.prototype.validate = function(silent)
{
  return 0 == this.getElements().filter(
    function() {
      return !this.validate(silent);
    }
  ).length;
}

// Enabled background submit mode and set callbacks
CommonForm.prototype.enableBackgroundSubmit = function(beforeCallback, afterCallback)
{
  this.backgroundSubmit = true;

  if (beforeCallback) {
    this.$form.bind('beforeSubmit', beforeCallback);
  }

  if (afterCallback) {
    this.$form.bind('afterSubmit', afterCallback);
  }
}

// Submit form in background mode
CommonForm.prototype.submitBackground = function(callback, disableValidation, options)
{
  var result = false;

  if (disableValidation || this.form.validate()) {

    this.preprocessBackgroundSubmit();

    var o = this;

    var isPOST = 'POST' == this.$form.attr('method').toUpperCase();
    var method = isPOST ? 'post' : 'get';
 
    options = options || {};
 
    if (
      'undefined' == typeof(options.rpc)
      && ((isPOST && this.postAsRPC) || (!isPOST && this.getAsRPC))
    ) {
      options.rpc = true;
    }

    result = core[method](
      this.$form.attr('action'),
      function(XMLHttpRequest, textStatus, data, isValid) {
        o.postprocessBackgroundSubmit();
        o.$form.trigger('afterSubmit', arguments);

        return callback ? callback(XMLHttpRequest, textStatus, data, isValid) : true;
      },
      this.$form.serialize(),
      options
    );

    if (!result) {
      core.showInternalError();
    }
  }

  return result;
}

// Prepare form before background submit
CommonForm.prototype.preprocessBackgroundSubmit = function()
{
  this.form.isBgSubmitting = true;

  this.getElements().commonController('preprocessBackgroundSubmit');
}

// Prepare form after background submit
CommonForm.prototype.postprocessBackgroundSubmit = function()
{
  this.form.isBgSubmitting = false;

  this.getElements().commonController('postprocessBackgroundSubmit');
}

// Form has any changed state watcher's elements
CommonForm.prototype.hasChangedWatcher = function()
{
  return 0 < this.getElements().filter(
    function() {
      return this.isChangedWatcher();
    }
  ).length;
}

// Check - form changed or not
CommonForm.prototype.isChanged = function(onlyVisible)
{
  return 0 < this.getElements().filter(
    function() {
      return this.isChanged(onlyVisible);
    }
  ).length;
}

/**
 * Element
 */

// Constructor
function CommonElement(elm)
{
  if (elm && !elm.commonController) {
    this.bind(elm);
  }
}

extend(CommonElement, Base);

CommonElement.prototype.element = null;

CommonElement.prototype.$element = null;

// Validattion class-base rule pattern
CommonElement.prototype.classRegExp = /^field-(.+)$/;

CommonElement.prototype.onFocusTTL = 200;

CommonElement.prototype.watchTTL = 2000;

// Bind element
CommonElement.prototype.bind = function(elm)
{
  this.element = elm;
  this.$element = jQuery(elm);

  var o = this;

  this.element.commonController = o;

  // Add methods and properties
  var methods = [
    'showInlineError', 'hideInlineError', 'markAsInvalid',
    'unmarkAsInvalid', 'markAsProgress',  'unmarkAsProgress',
    'validate',        'isChanged',       'markAsWatcher',
    'toggleActivity',  'isEnabled',       'enable',
    'disable',         'isVisible'
  ];

  for (var i = 0; i < methods.length; i++) {
    var method = methods[i];
    this.element[method] = new Function('', 'return this.commonController.' + method + '.apply(this.commonController, arguments);');
  }

  this.saveValue();
  this.element.isInitialError = this.$element.hasClass('validation-error');

  // Assign behaviors
  if (this.$element.hasClass('watcher')) {
    this.markAsWatcher();
  }

  if (this.$element.hasClass('field-state') && this.$element.hasClass('linked')) {
    this.linkWithCountry();
  }

  if (this.$element.hasClass('column-switcher') && 0 < this.$element.parents('th').length) {
    this.markAsColumnSwitcher();
  }

  if (this.$element.hasClass('wheel-ctrl')) {
    this.markAsWheelControlled();
  }
}

// Get validators by form element
CommonElement.prototype.getValidators = function()
{
  var validators = [];

  if (this.element.className) {
    var classes = this.element.className.split(/ /);
    var m, methodName;
    for (var i = 0; i < classes.length; i++) {

      m = classes[i].match(this.classRegExp);

      if (m && m[1]) {
        methodName = m[1].replace(/-[a-z]/, this.buildMethodName);
        methodName = 'validate' + methodName.substr(0, 1).toUpperCase() + methodName.substr(1);
        if (typeof(this[methodName]) !== 'undefined') {
          validators.push(
            {
              key:    m[1],
              method: this[methodName]
            }
          );
        }
      }
    }
  }

  return validators;
}

// Get element label by form element
CommonElement.prototype.getLabel = function()
{
  var label = null;

  if (this.element.id) {
    var lbl = jQuery('label[for="' + this.element.id + '"]');
    if (lbl.length) {
      label = jQuery.trim(lbl.eq(0).html()).replace(/:$/, '').replace(/<.+$/, '');
    }
  }

  return label;
}

// Check - element visible or not
CommonElement.prototype.isVisible = function()
{
  if (this.element.style.display == 'none') {
    return false;
  }

  return 0 == this.$element
    .parents()
    .filter(
      function() {
        return this.style.display == 'none';
      }
    )
    .length;
}

// Build validator method name helper
CommonElement.prototype.buildMethodName = function(str)
{
  return str.substr(1).toUpperCase();
}

// Validate form element
CommonElement.prototype.validate = function(silent, noFocus)
{
  var result = true;

  // Hidden input always validate successfull
  if (isElement(this.element, 'input') && 'hidden' == this.element.type) {
    return true;
  }

  // Element is fail server validation and element's value did not changed
  if (this.$element.hasClass('server-validation-error') && !this.isChanged()) {
    return false;
  }

  if (!silent) {
    this.unmarkAsInvalid();
  }

  var validators = this.getValidators();

  // Check visibility
  if (0 == validators.length || !this.isVisible()) {
    return result;
  }

  // Check by validators
  for (var i = 0; i < validators.length && result; i++) {

    var res = validators[i].method.call(this);
    if (!res.status && res.apply) {
      result = false;

      if (!silent) {
        res.message = core.t(res.message);
        this.markAsInvalid(res.message, validators[i].key);

        if (!noFocus) {
          var o = this;
          setTimeout(
            function() {
              o.$element.focus();
            },
            o.onFocusTTL
          );
        }
      }
    }
  }

  return result;
}

// Mark element as invalid (validation is NOT passed)
CommonElement.prototype.markAsInvalid = function(message, key, serverSideError)
{
  this.$element
    .addClass('validation-error')
    .data('lastValidationError', message)
    .data('lastValidationKey', key)
    .not('.forbid-inline-error')
    .each(
      function() {
        this.hideInlineError();
        this.showInlineError(message);
      }
    );

  if (serverSideError) {
    this.$element.addClass('server-validation-error');
  }

  this.$element.trigger('invalid');
}

// Unmark element as invalid (validation is passed)
CommonElement.prototype.unmarkAsInvalid = function()
{
  if (this.$element.hasClass('validation-error')) {
    this.$element.trigger('valid');
  }

  this.$element
    .data('lastValidationError', null)
    .data('lastValidationKey', null)
    .removeClass('validation-error')
    .removeClass('server-validation-error')
    .each(
      function() {
        this.hideInlineError();
      }
    );
}

// Show element inline error message 
CommonElement.prototype.showInlineError = function(message)
{
  return jQuery(document.createElement('p'))
    .insertAfter(this.$element)
    .addClass('error')
    .addClass('inline-error')
    .html(message);
}

// Hide element inline error message 
CommonElement.prototype.hideInlineError = function()
{
  return jQuery('p.inline-error', this.element.parentNode).remove();
}

// Mark element as in-progress element
CommonElement.prototype.markAsProgress = function()
{
  var mark = jQuery('<div></div>')
    .insertAfter(this.$element)
    .addClass('single-progress-mark');

  var h = this.$element.height() + parseInt(this.$element.css('margin-top')) + parseInt(this.$element.css('margin-bottom'));
  var pos = this.$element.position();

  return mark.css(
    {
      top:  (pos.top + Math.round((h - 18) / 2)) + 'px',
      left: (pos.left + this.$element.outerWidth()) + 'px'
    }
  );
}

// Unmark element as in-progress element
CommonElement.prototype.unmarkAsProgress = function()
{
  return jQuery('.single-progress-mark', this.element.parentNode).remove();
}

// Mark element as change watcher
CommonElement.prototype.markAsWatcher = function(beforeCallback)
{
  var o = this;

  this.element.selfSubmitting = false;
  this.element.selfSubmitTO = null;
  this.element.lastValue = this.element.value;

  var submitElement = function(event) {
    if (o.element.selfSubmitTO) {
      clearTimeout(o.element.selfSubmitTO);
      o.element.selfSubmitTO = null;
    }

    if (
      (o.isChanged() || (o.$element.hasClass('validation-error') && !o.$element.hasClass('server-validation-error')))
      && (!beforeCallback || beforeCallback(o.element))
    ) {
      jQuery(o.element.form).submit();
    }
  }

  var delayedUpdate = function(event) {
    if (this.lastValue != this.value) {

      this.lastValue = this.value;

      if (this.selfSubmitTO) {
        clearTimeout(this.selfSubmitTO);
        this.selfSubmitTO = null;
      }

      this.selfSubmitTO = setTimeout(submitElement, o.watchTTL);
    }
  }

  o.$element
    .blur(submitElement)
    .keyup(delayedUpdate);

  if ('undefined' != typeof(jQuery.fn.mousewheel)) {
    o.$element.mousewheel(delayedUpdate);
  }
}

// Element is column checkboxes switcher
CommonElement.prototype.markAsColumnSwitcher = function()
{
  this.$element.click(
    function() {
      var idx = jQuery(this).parents('th').get(0).cellIndex;
      var newState = this.checked;

      jQuery(this).parents('table').find('tr').each(
        function() {
          jQuery(this.cells[idx]).find(':checkbox').get(0).checked = newState;
        }
      );
    }
  );
}

// Element is mouse wheel controlled
CommonElement.prototype.markAsWheelControlled = function()
{
  var o = this;
  this.$element.mousewheel(
    function(event, delta) {
      return o.updateByMouseWheel(event, delta);
    }
  );
}

// Update element by mosue wheel
CommonElement.prototype.updateByMouseWheel = function(event, delta)
{
  event.stopPropagation();

  var value = false;
  var mantis = 0;

  if (this.element.value.length == 0) {
    value = 0;

  } else if (this.element.value.search(/^ *[+-]?[0-9]+\.?[0-9]* *$/) != -1) {
    var m = this.element.value.match(/^ *[+-]?[0-9]+\.([0-9]+) *$/);
    if (m && m[1]) {
      mantis = m[1].length;
    }

    value = parseFloat(this.element.value);
    if (isNaN(value)) {
      value = false;
    }
  }

  if (value !== false) {
    var min = jQuery(this).data('min');
    var max = jQuery(this).data('max');

    value = value + delta * -1;

    if (typeof(min) != 'undefined' && min > value) {
      value = min;
    }

    if (typeof(max) != 'undefined' && max < value) {
      value = max;
    }

    value = mantis
      ? Math.round(value * Math.pow(10, mantis)) / Math.pow(10, mantis)
      : Math.round(value);

    var oldValue = this.element.value;
    this.element.value = value;

    if (!this.validate(true)) {
      this.element.value = oldValue;
    }
  }

  return false;
}


// Element is state watcher
CommonElement.prototype.isWatcher = function()
{
  return 'undefined' != typeof(this.element.selfSubmitting);
}

// Element is changed state watcher
CommonElement.prototype.isChangedWatcher = function()
{
  return this.isWatcher() && this.element.selfSubmitTO;
}

// Check - element changed or not
CommonElement.prototype.isChanged = function(onlyVisible)
{
  if (onlyVisible && !this.isVisible()) {
    return false;
  }

  if (
    (isElement(this.element, 'input') && -1 != jQuery.inArray(this.element.type, ['text', 'password', 'hidden']))
    || isElement(this.element, 'select')
    || isElement(this.element, 'textarea')
  ) {
    return this.element.initialValue != this.element.value;
  }

  if (isElement(this.element, 'input') && -1 != jQuery.inArray(this.element.type, ['checkbox', 'radio'])) {
    return this.element.initialValue != this.element.checked;
  }

  return false;
}

// Save element value as initial value
CommonElement.prototype.saveValue = function()
{
  if (
    (isElement(this.element, 'input') && -1 != jQuery.inArray(this.element.type, ['text', 'password', 'hidden']))
    || isElement(this.element, 'select')
    || isElement(this.element, 'textarea')
  ) {
    this.element.initialValue = this.element.value;

  } else if (isElement(this.element, 'input') && -1 != jQuery.inArray(this.element.type, ['checkbox', 'radio'])) {
    this.element.initialValue = this.element.checked;
  }
}

// Prepare element before background submit
CommonElement.prototype.preprocessBackgroundSubmit = function()
{
  if (this.$element.hasClass('progress-mark-owner') && this.isVisible()) {
    this.markAsProgress();
  }

  if (!this.element.readonly) {
    this.element.readonly = true;
    this.element.isTemporaryReadonly = true;
  }
}

// Prepare element after background submit
CommonElement.prototype.postprocessBackgroundSubmit = function()
{
  this.saveValue();

  if (this.$element.hasClass('progress-mark-owner')) {
    this.unmarkAsProgress();
  }

  if (this.element.isTemporaryReadonly) {
    this.element.readonly = false;
    this.element.isTemporaryReadonly = null;
  }
}

CommonElement.prototype.linkWithCountry = function()
{
  var countryName = this.element.name.replace(/state/, 'country');
  var country = this.element.form.elements.namedItem(countryName);

  if (country && 'undefined' != typeof(window.CountriesStates)) {

    this.element.isFocused = false;

    jQuery(this.$element)
      .focus(
        function() {
          this.isFocused = true;
        }
      )
      .blur(
        function() {
          this.isFocused = false;
        }
      );

    var stateSwitcher = document.createElement('input');
    stateSwitcher.type = 'hidden';
    stateSwitcher.name = this.element.name.replace(/state/, 'is_custom_state');
    stateSwitcher.value = isElement(this.element, 'input') ? '1' : '';
    country.form.appendChild(stateSwitcher);
    new CommonElement(stateSwitcher);

    this.element.currentCountryCode = false;
    country.stateInput = this.element;

    var o = this;

    o.lastStateText = '';

    var replaceElement = function(type)
    {
      var inp = document.createElement(type);
      if (type == 'input') {
        inp.type = 'text';
      }
      inp.id = this.stateInput.id;
      inp.className = this.stateInput.className;
      inp.name = this.stateInput.name;
      inp.currentCountryCode = this.stateInput.currentCountryCode;

      var isFocused = this.stateInput.isFocused;

      jQuery(this.stateInput).replaceWith(inp);
      this.stateInput = inp;

      if (isFocused) {
        this.stateInput.focus();
      }

      jQuery(this.stateInput)
        .focus(
          function() {
            this.isFocused = true;
          }
        )
        .blur(
          function() {
            this.isFocused = false;
          }
        );

      o.bind(inp);
    }

    var change = function()
    {
      if (this.stateInput.currentCountryCode == this.value) {
        return true;
      }

      this.stateInput.currentCountryCode = this.value;

      if ('undefined' == typeof(CountriesStates[this.value])) {

        // As input box
        if (!isElement(this.stateInput, 'input')) {
          replaceElement.call(this, 'input');
          this.stateInput.value = o.lastStateText;
        }

        stateSwitcher.value = '1';

    } else {

        // As select box
        var previousSelected = null;
        if (isElement(this.stateInput, 'select')) {

          if (this.stateInput.selectedIndex > 1) {
            previousSelected = this.stateInput.options[this.stateInput.selectedIndex].value;
            jQuery('option', this.stateInput).remove();
          }

        } else {
          o.lastStateText = this.stateInput.value;
          replaceElement.call(this, 'select');
        }

        for (var i = 0; i < CountriesStates[this.value].length; i++) {
          var s = CountriesStates[this.value][i];
          this.stateInput.options[i] = new Option(s.state, s.state_code);

          if (previousSelected && previousSelected == s.state_code) {
            this.stateInput.options[i].selected = true;
            this.stateInput.selectedIndex = i;
          }
        }

        stateSwitcher.value = '';

      }
    }

    jQuery(country).change(change);

    change.call(country);
  }
}

// Prepare element with onclick-based location change
CommonElement.prototype.processLocation = function()
{
  if (
    this.$element.attr('onclick')
    && -1 !== this.$element.attr('onclick').toString().search(/\.location[ ]*=[ ]*['"].+['"]/)
  ) {
    var m = this.$element.attr('onclick').toString().match(/\.location[ ]*=[ ]*['"](.+)['"]/);
    this.$element
      .data('location', m[1])
      .removeAttr('onclick');
  }
}

// Toggle element activity
CommonElement.prototype.toggleActivity = function(condition)
{
  if (
    ('undefined' != typeof(condition) && condition)
    || ('undefined' == typeof(condition) && !this.isEnabled())
  ) {
    this.enable();

  } else {
    this.disable();
  }
}

// Check element activity
CommonElement.prototype.isEnabled = function()
{
  return 'disabled' == this.$element.attr('disabled');
}

// Disable element
CommonElement.prototype.disable = function()
{
  this.$element
    .addClass('disabled')
    .attr('disabled', 'disabled');
}

// Enable element
CommonElement.prototype.enable = function()
{
  this.$element
    .removeClass('disabled')
    .removeAttr('disabled');
}

/**
 * Validators
 */

// E-mail
CommonElement.prototype.validateEmail = function()
{
  var re = new RegExp(
    "^[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z](?:[a-z0-9-]*[a-z0-9])?$",
    'gi'
  );

  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  return {
    status:  !apply || !this.element.value.length || this.element.value.search(re) !== -1,
    message: 'Enter a correct email',
    apply:   apply
  };
}

// Integer
CommonElement.prototype.validateInteger = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && value == Math.round(value)),
    message: 'Enter an integer',
    apply:   apply
  };
}

// Float
CommonElement.prototype.validateFloat = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  return {
    status:  !apply || !this.element.value.length || !isNaN(parseFloat(this.element.value)),
    message: 'Enter a number',
    apply:   apply
  };
}

// Positive number
CommonElement.prototype.validatePositive = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 <= value),
    message: 'Enter a positive number',
    apply:   apply
  };
}

// Negative number
CommonElement.prototype.validateNegative = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 >= value),
    message: 'Enter a negative number',
    apply:   apply
  };
}

// Non-zero number
CommonElement.prototype.validateNonZero = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 != value),
    message: 'Zero cannot be used',
    apply:   apply
  };
}

// Range (min - max) number
CommonElement.prototype.validateRange = function()
{
  var apply = isElement(this.element, 'input') || isElement(this.element, 'textarea');

  var result = {
    status:  true,
    message: 'This field is required',
    apply:   apply
  };

  if (apply && this.element.value.length) {

    var value = parseFloat(this.element.value);

    if (isNaN(value)) {
      result.status = false;

    } else if (typeof(this.element.min) !== 'undefined' && this.element.min > value) {
      result.status = false;
      result.message = 'Field too small!';

    } else if (typeof(this.element.max) !== 'undefined' && this.element.max < value) {

      result.status = false;
      result.message = 'Field too big!';
    }
  }

  return result;
}

// Required field
CommonElement.prototype.validateRequired = function()
{
  return {
    status:  this.element.value !== null && 0 < this.element.value.length,
    message: 'Field is required!',
    apply:   true
  };
}

// Autostart
core.autoload(CommonForm);

// Common controller as jQuery plugin
(function ($) {
  jQuery.fn.commonController = function(property) {
    var args = Array.prototype.slice.call(arguments, 1);

    this.each(
      function() {
        if ('undefined' == typeof(this.commonController)) {
          if (isElement(this, 'form')) {
            new CommonForm(this);

          } else if (
            isElement(this, 'input')
            || isElement(this, 'select')
            || isElement(this, 'textarea')
            || isElement(this, 'button')
          ) {
            new CommonElement(this);
          }
        }

        if (
          'undefined' !== typeof(this.commonController)
          && 'undefined' !== typeof(this.commonController[property])
        ) {
          if ('function' == typeof(this.commonController[property])) {
            this.commonController[property].apply(this.commonController, args);

          } else {
            this.commonController[property] = args[0];
          }
        }
      }
    );

    return this;
  }

})(jQuery);

