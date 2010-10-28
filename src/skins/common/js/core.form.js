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

  form = $(form).filter('form').eq(0);
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
        var e = $.Event('beforeSubmit');
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
  $('form').each(
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
  return $(this.form.elements);
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
  this.$element = $(elm);

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
    var lbl = $('label[for="' + this.element.id + '"]');
    if (lbl.length) {
      label = $.trim(lbl.eq(0).html()).replace(/:$/, '').replace(/<.+$/, '');
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
  if (this.element.constructor == HTMLInputElement && 'hidden' == this.element.type) {
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

      var label = this.getLabel();
      if (label) {
        res.message = res.message.replace(/Field/, '\'' + label + '\' field');
      }

      if (!silent) {
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
  return $(document.createElement('p'))
    .insertAfter(this.$element)
    .addClass('error')
    .addClass('inline-error')
    .html(message);
}

// Hide element inline error message 
CommonElement.prototype.hideInlineError = function()
{
  return $('p.inline-error', this.element.parentNode).remove();
}

// Mark element as in-progress element
CommonElement.prototype.markAsProgress = function()
{
  var mark = $('<div></div>')
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
  return $('.single-progress-mark', this.element.parentNode).remove();
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
      $(o.element.form).submit();
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

  if ('undefined' != typeof($.fn.mousewheel)) {
    o.$element.mousewheel(delayedUpdate);
  }
}

// Check - element changed or not
CommonElement.prototype.isChanged = function(onlyVisible)
{
  if (onlyVisible && !this.isVisible()) {
    return false;
  }

  if (
    (this.element.constructor == HTMLInputElement && -1 != $.inArray(this.element.type, ['text', 'password', 'hidden']))
    || this.element.constructor == HTMLSelectElement
    || this.element.constructor == HTMLTextAreaElement
  ) {
    return this.element.initialValue != this.element.value;
  }

  if (this.element.constructor == HTMLInputElement && -1 != $.inArray(this.element.type, ['checkbox', 'radio'])) {
    return this.element.initialValue != this.element.checked;
  }

  return false;
}

// Save element value as initial value
CommonElement.prototype.saveValue = function()
{
  if (
    (this.element.constructor == HTMLInputElement && -1 != $.inArray(this.element.type, ['text', 'password', 'hidden']))
    || this.element.constructor == HTMLSelectElement
    || this.element.constructor == HTMLTextAreaElement
  ) {
    this.element.initialValue = this.element.value;

  } else if (this.element.constructor == HTMLInputElement && -1 != $.inArray(this.element.type, ['checkbox', 'radio'])) {
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

    $(this.$element)
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
    stateSwitcher.value = this.element.constructor == HTMLInputElement ? '1' : '';
    country.form.appendChild(stateSwitcher);
    new CommonElement(stateSwitcher);

    this.element.currentCountryCode = country.value;
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

      $(this.stateInput).replaceWith(inp);
      this.stateInput = inp;

      if (isFocused) {
        this.stateInput.focus();
      }

      $(this.stateInput)
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
        if (this.stateInput.constructor != HTMLInputElement) {
          replaceElement.call(this, 'input');
          this.stateInput.value = o.lastStateText;
        }

        stateSwitcher.value = '1';

    } else {

        // As select box
        if (this.stateInput.constructor == HTMLSelectElement) {
          $('option', this.stateInput).remove();

        } else {
          o.lastStateText = this.stateInput.value;
          replaceElement.call(this, 'select');
        }

        for (var i = 0; i < CountriesStates[this.value].length; i++) {
          var s = CountriesStates[this.value][i];
          this.stateInput.options[i] = new Option(s.state, s.state_code);
        }

        stateSwitcher.value = '';

      }
    }

    $(country).change(change);

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

  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  return {
    status:  !apply || !this.element.value.length || this.element.value.search(re) !== -1,
    message: 'Field is not e-mail address! Please correct',
    apply:   apply
  };
}

// Integer
CommonElement.prototype.validateInteger = function()
{
  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && value == Math.round(value)),
    message: 'Field is not integer! Please correct',
    apply:   apply
  };
}

// Float
CommonElement.prototype.validateFloat = function()
{
  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  return {
    status:  !apply || !this.element.value.length || !isNaN(parseFloat(this.element.value)),
    message: 'Field is not float! Please correct',
    apply:   apply
  };
}

// Positive number
CommonElement.prototype.validatePositive = function()
{
  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 <= value),
    message: 'Field is not positive! Please correct',
    apply:   apply
  };
}

// Negative number
CommonElement.prototype.validateNegative = function()
{
  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 >= value),
    message: 'Field is not negative! Please correct',
    apply:   apply
  };
}

// Non-zero number
CommonElement.prototype.validateNonZero = function()
{
  var value = parseFloat(this.element.value);

  return {
    status:  !apply || !this.element.value.length || (!isNaN(value) && 0 != value),
    message: 'Field is zero! Please correct',
    apply:   apply
  };
}

// Range (min - max) number
CommonElement.prototype.validateRange = function()
{
  var apply = this.element.constructor == HTMLInputElement || this.element.constructor == HTMLTextAreaElement;

  var result = {
    status:  true,
    message: 'Field is invalid! Please correct',
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
  $.fn.commonController = function(property) {
    var args = Array.prototype.slice.call(arguments, 1);

    this.each(
      function() {
        if ('undefined' == typeof(this.commonController)) {
          if (this.constructor == HTMLFormElement) {
            new CommonForm(this);

          } else if (
            this.constructor == HTMLInputElement
            || this.constructor == HTMLSelectElement
            || this.constructor == HTMLTextAreaElement
            || this.constructor == HTMLButtonElement
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

