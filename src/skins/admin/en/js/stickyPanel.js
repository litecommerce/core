/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sticky panel controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 */

function StickyPanel(base)
{
  base = jQuery(base);
  if (0 < base.length && base.hasClass('sticky-panel')) {
    base.get(0).controller = this;
    this.base = base;

    this.process();
  }
}

extend(StickyPanel, Base);

// Autoloader
StickyPanel.autoload = function()
{
  jQuery('.sticky-panel').each(
    function() {
      new StickyPanel(this);
    }
  );
}

// Default options
StickyPanel.prototype.defaultOptions = {
  bottomPadding:       25,
  parentContainerLock: true
};

// Panel
StickyPanel.prototype.panel = null;

// Timer resource
StickyPanel.prototype.timer = null;

// Current document
StickyPanel.prototype.doc = null;

// Last scroll top
StickyPanel.prototype.lastScrollTop = null;

// Panel height
StickyPanel.prototype.panelHeight = null;

// Parent container top range
StickyPanel.prototype.parentContainerTop = null;

// Process widget (initial catch widget)
StickyPanel.prototype.process = function()
{
  // Initialization
  this.panel = this.base.find('.box').eq(0);

  this.base.height(this.panel.outerHeight());

  this.doc = jQuery(window.document);
  this.lastScrollTop = this.doc.scrollTop();
  this.panelHeight = this.base.height();
  this.parentContainerTop = this.base.parent().offset().top;

  var centeredBox = this.base.find('.centered-box').eq(0);
  if (centeredBox.length) {
    centeredBox.width(this.panel.outerWidth());
  }

  // Assign move operators
  var controller = this;
  this.checkRepositionEventCallback = function(event) {
    return controller.checkRepositionEvent(event);
  }

  jQuery(window).scroll(this.checkRepositionEventCallback);
  jQuery(window).resize(this.checkRepositionEventCallback);
  core.bind(
    'stickyPanelReposition',
    function (event) {
      return controller.reposition(event);
    }
  );
  this.reposition();

  // Form change activation behavior
  if (this.isFormChangeActivation()) {
    var form = this.base.parents('form').eq(0);
    form.bind(
      'state-changed',
      function() {
        controller.markAsChanged();
      }
    );
    form.bind(
      'state-initial',
      function() {
        controller.unmarkAsChanged();
      }
    );
  }
}

// Get options
StickyPanel.prototype.getOptions = function()
{
  var options = this.base.data('options') || {};

  jQuery.each(
    this.defaultOptions,
    function (key, value) {
      if ('undefined' == typeof(options[key])) {
        options[key] = value;
      }
    }
  );

  return options;
}

// Check reposition - need change behavior or not
StickyPanel.prototype.checkRepositionEvent = function()
{
  if (this.timer) {
    clearTimeout(this.timer);
    this.timer = null;
  }

  var controller = this;

  setTimeout(
    function()
    {
      controller.checkRepositionEventTick();
    },
    50
  );
}

// Check reposition - need change behavior or not (on set timer)
StickyPanel.prototype.checkRepositionEventTick = function()
{
  var scrollTop = this.doc.scrollTop();
  if (Math.abs(scrollTop - this.lastScrollTop) > 0) {
    this.lastScrollTop = scrollTop;
    this.reposition();
  }
}

// Reposition
StickyPanel.prototype.reposition = function()
{
  var options = this.getOptions();

  this.panel.stop();

  var boxScrollTop = this.base.offset().top;
  var docScrollTop = this.doc.scrollTop();
  var windowHeight = jQuery(window).height();
  var diff = windowHeight - boxScrollTop + docScrollTop - this.panelHeight - options.bottomPadding;

  if (0 > diff) {
    if (options.parentContainerLock && this.parentContainerTop > (boxScrollTop + diff)) {
      this.panel.css({position: 'absolute', top: this.parentContainerTop - boxScrollTop});

    } else if ('fixed' != this.panel.css('position')) {
      this.panel.css({position: 'fixed', top: windowHeight - this.panelHeight - options.bottomPadding});
    }

  } else if (this.panel.css('top') != '0px') {
    this.panel.css({position: 'absolute', top: 0});

  }
}

// Check - form change activation behavior
StickyPanel.prototype.isFormChangeActivation = function()
{
  return this.base.hasClass('form-change-activation');
}

// Mark as changed
StickyPanel.prototype.markAsChanged = function()
{
  this.getFormChangedButtons().each(
    function() {
      this.enable();
    }
  );

  this.getFormChangedLinks().removeClass('disabled');
}

// Unmark as changed
StickyPanel.prototype.unmarkAsChanged = function()
{
  this.getFormChangedButtons().each(
    function() {
      this.disable();
    }
  );

  this.getFormChangedLinks().addClass('disabled');
}

// Get a form button, which should change as the state of the form
StickyPanel.prototype.getFormChangedButtons = function()
{
  return this.base.find('button');
}

// Get a form links, which should change as the state of the form
StickyPanel.prototype.getFormChangedLinks = function()
{
  return this.base.find('.cancel');
}

// Autoload
core.autoload(StickyPanel);
