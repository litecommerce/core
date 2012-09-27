/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Abstract loadable block (widget)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

// Constructor
function ALoadable(base) {
  if (base && this.checkBase(jQuery(base))) {
    this.base = jQuery(base);

    var o = this;

    this.loadHandlerCallback = function(xhr, s, data) {
      return o.loadHandler(xhr, s, data);
    }

    this.postprocess(true, true);
  }
}

extend(ALoadable, Base);

// Base element(s)
ALoadable.prototype.base = null;

// Shade flag
ALoadable.prototype.isShowModalScreen = false;

// Widget loading flag
ALoadable.prototype.isLoading = false;

// Widget has deferred load operation
ALoadable.prototype.deferredLoad = false;

// Preload event custom handler
ALoadable.prototype.preloadHandler = null;

// Postload event custom handler
ALoadable.prototype.postloadHandler = null;

// Options

// Use shade
ALoadable.prototype.shadeWidget = true;

// Use widget blocking
ALoadable.prototype.blockWidget = true;

// Update page title from loaded request or not
ALoadable.prototype.updatePageTitle = false;

// Update breadcrumb last node from loaded request or not
ALoadable.prototype.updateBreadcrumb = false;

// Widget target
ALoadable.prototype.widgetTarget = null;

// Widget parameters
ALoadable.prototype.widgetParams = null;

// Widget class name
ALoadable.prototype.widgetClass = null;

// Page title element pattern
ALoadable.prototype.titlePattern = 'h1:eq(0)';

// Page title element pattern in request's response data
ALoadable.prototype.titleRequestPattern = 'h2.ajax-title-loadable:eq(0)';

// Container element pattern in request's response data
ALoadable.prototype.containerRequestPattern = 'div.ajax-container-loadable';


// Check base
ALoadable.prototype.checkBase = function(base)
{
  var result = 0 < base.length;

  if (result) {
    base.map(
      function() {
        if (result && 'undefined' != typeof(this.loadable)) {
          result = false;
        }
      }
    );
  }

  return result;
}

// Load widget
ALoadable.prototype.load = function(params)
{
  if (!core.isRequesterEnabled) {
    return false;
  }

  if (this.isLoading) {
    this.deferredLoad = true;
    return true;
  }

  this.isLoading = true;
  this.deferredLoad = false;

  var url = this.buildWidgetRequestURL(params);

  this.base.trigger('preload', [this, url]);
  if (typeof(this.preloadHandler) == 'function') {

    // Call preload event handler
    this.preloadHandler.call(this.base);
  }

  this.saveState();

  this.shade();

  return core.get(url, this.loadHandlerCallback);
}

// Build request widget URL (AJAX)
ALoadable.prototype.buildWidgetRequestURL = function(params)
{
  return URLHandler.buildURL(this.getParams(params));
}

// [ABSTRACT] Get additional parameters
ALoadable.prototype.getParams = function(params)
{
  params = params ? params : {};

  // Set widget target and parameters
  if ('undefined' == typeof(params.target) && this.widgetTarget) {
    params.target = this.widgetTarget;
  }

  if (this.widgetParams) {
    for (var i in this.widgetParams) {
      params[i] = this.widgetParams[i];
    }
  }

  // TODO remove if it will be no use!!
  if ('undefined' == typeof(params.sessionCell) && this.sessionCell) {
    params.sessionCell = this.sessionCell;
  }

  if ('undefined' == typeof(params.action)) {
    params.action = '';
  }

  if ('undefined' == typeof(params.widget) && this.widgetClass) {
    params.widget = this.widgetClass;
  }

  return params;
}

// onload handler
ALoadable.prototype.loadHandler = function(xhr, s, data)
{
  var processed = false;

  this.isLoading = false;

  if (false !== data) {
    var container = this.getTemporaryContainer();
    container.html(data);
    processed = this.placeRequestData(this.extractRequestData(container));

    container.remove();
  }

  this.unshade();

  this.postprocess(processed);

  if (!this.isLoading && this.deferredLoad) {
    this.deferredLoad = false;
    this.load();

  } else if (processed && typeof(this.postloadHandler) == 'function') {

    // Call postload event handler
    this.postloadHandler.call(this.base, xhr);
  }

  core.trigger('widgetLoad', this);

  return processed;
}

// Get temporary container
ALoadable.prototype.getTemporaryContainer = function()
{
  var div = document.createElement('DIV');
  div.style.display = 'none';
  jQuery('body').get(0).appendChild(div);

  return jQuery(div);

}

// Extract widget data
ALoadable.prototype.extractRequestData = function(div)
{
  return div;
}

// Place request data
ALoadable.prototype.placeRequestData = function(box)
{
  // Update page title
  if (this.updatePageTitle || this.updateBreadcrumb) {
    this.replacePageTitle(box);
  }

  box = this.extractContent(box).children();

  var id = 'temporary-ajax-id-' + (new Date()).getTime();

  box.addClass(id);

  this.base.trigger('reload', [box]);

  if (0 < box.length) {
    this.base.replaceWith(box);
    this.base = jQuery('.' + id);
  } else {
    this.base.empty();
  }

  this.base.removeClass(id);

  return true;
}

// Extract widget content
ALoadable.prototype.extractContent = function(box)
{
  box = jQuery(this.containerRequestPattern, box);
  if (box.children().eq(0).hasClass('block')) {
    box = jQuery('.block > .content', box.eq(0));
  }

  return box;
}

// Replace page title
ALoadable.prototype.replacePageTitle = function(box)
{
  var title = jQuery(this.titleRequestPattern, box).eq(0).html();
  if (title) {
    this.placeNewPageTitle(title);
  }

  return title;
}

// Place new page title
ALoadable.prototype.placeNewPageTitle = function(title)
{
  if (this.updatePageTitle) {
    jQuery(this.titlePattern).eq(0).html(title);
  }

  if (this.updateBreadcrumb) {
    jQuery('#breadcrumb .last').find('a,span').html(title);
  }
}

// [ABSTRACT] Widget post processing (after new widget data placing)
ALoadable.prototype.postprocess = function(isSuccess, initial)
{
  if (isSuccess) {
    var o = this;

    this.base.map(
      function() {
        this.loadable = o;
      }
    );

    if (!initial && 'undefined' != typeof(window.CommonForm)) {
      this.base.filter('form').each(
        function() {
          new CommonForm(this);
        }
      );

      jQuery('form', this.base).each(
        function() {
          new CommonForm(this);
        }
      );

      this.base.filter('*:input').each(
        function() {
          new CommonElement(this);
        }
      );

      jQuery('*:input', this.base).each(
        function() {
          new CommonElement(this);
        }
      );

    }

  }
}

// [ABSTRACT] Widget save state (before widget load / reload)
ALoadable.prototype.saveState = function()
{
}

// Show modal screen
ALoadable.prototype.shade = function()
{
  if (!this.base || !this.base.length) {
    return false;
  }

  if (this.isShowModalScreen) {
    return true;
  }

  if (this.shadeWidget)  {
    var shade = this.getShadeBase();

    shade.block(
      {
        message: '<div></div>',
        css: {
          width: '30%',
          zIndex: 16000
        },
        overlayCSS: {
          opacity: 1,
          zIndex: 15000
        },
        centerY: true,
        centerX: true
      }
    );

    jQuery('.blockElement', shade)
      .css({padding: '', border: '', margin: '', textAlign: '', color: '', backgroundColor: '', cursor: ''})
      .addClass('block-wait');

    jQuery('.blockOverlay', shade)
      .css({padding: '', border: '', margin: '', textAlign: '', color: '', backgroundColor: '', cursor: ''});

    if (shade.height() < (jQuery('.blockMsg', shade).outerHeight() + 5)) {
      jQuery('.blockMsg', shade).addClass('mini-block-wait');
    }

    if (shade.height() < jQuery('.blockMsg', shade).outerHeight()) {
      jQuery('.blockMsg div', shade).remove();
    }

    jQuery('.blockMsg', shade).css(
      {
        'top':  Math.round((shade.height() - jQuery('.blockMsg', shade).outerHeight()) / 2) + 'px',
        'left': Math.round((shade.width() - jQuery('.blockMsg', shade).outerWidth()) / 2) + 'px'
      }
    );

  } else if (this.blockWidget) {

    this.base.click(this.blocker).focus(this.blocker);

  }

  this.isShowModalScreen = true;

  return true;
}

// Unshade widget
ALoadable.prototype.unshade = function()
{
  if (!this.base || !this.isShowModalScreen) {
    return false;
  }

  if (this.shadeWidget)  {
    this.getShadeBase().unblock();

  } else if (this.blockWidget) {
    this.base.unbind('click', this.blocker).unbind('focus', this.blocker);
  }

  this.isShowModalScreen = false;

  return true;
}

// Get base element for shade / unshade operation
ALoadable.prototype.getShadeBase = function() {
  return this.base;
}

// Event blocker
ALoadable.prototype.blocker = function(event) {
  event.stopPropagation();

  return false;
}

// Submit specified form
ALoadable.prototype.submitForm = function(form, callback)
{
  return form.submitBackground(
    callback,
    false,
    {
      rpc: 'POST' == jQuery(form).attr('method').toUpperCase()
    }
  );
}
