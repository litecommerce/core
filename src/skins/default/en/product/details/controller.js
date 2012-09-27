/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product details controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Controller
 */

function ProductDetailsController(base)
{
  this.callSupermethod('constructor', arguments);

  if (this.base.get(0) && jQuery('form.product-details', this.base).get(0).elements.namedItem('product_id')) {
    this.productId = jQuery('form.product-details', this.base).get(0).elements.namedItem('product_id').value;

    this.block = new ProductDetailsView(this.base, this.productId);

    var o = this;

    core.bind(
      'updateCart',
      function(event, data) {
        if (!o.selfAdded) {
          for (var i = 0; i < data.items.length; i++) {
            if (data.items[i].object_type == 'product' && data.items[i].object_id == o.productId) {
              o.block.load();
            }
          }
        }
      }
    );
  }
}

extend(ProductDetailsController, AController);

// Prodiuct id
ProductDetailsController.prototype.productId = null;

// Controller name
ProductDetailsController.prototype.name = 'ProductDetailsController';

// Find pattern
ProductDetailsController.prototype.findPattern = 'div.product-details';

// Controller associated main widget
ProductDetailsController.prototype.block = null;

// Controller associated buttons block widget
ProductDetailsController.prototype.buttonsBlock = null;

ProductDetailsController.prototype.selfAdded = false;

// Initialize controller
ProductDetailsController.prototype.initialize = function()
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
 * Main widget
 */

function ProductDetailsView(base, productId)
{
  this.callSupermethod('constructor', arguments);

  this.productId = productId;

  var o = this;

  this.linkClickHandler = function(event)
  {
    event.stopPropagation();

    o.showLightbox();
    jQuery('.product-image-gallery li.selected a', o.base).eq(0).trigger('click');

    return false;
  }
}

extend(ProductDetailsView, ALoadable);

// Prodiuct id
ProductDetailsView.prototype.productId = null;

// Shade widget
ProductDetailsView.prototype.shadeWidget = true;

// Widget target
ProductDetailsView.prototype.widgetTarget = 'product';

// Widget class name
ProductDetailsView.prototype.widgetClass = '\\XLite\\View\\Product\\Details\\Customer\\Page\\Main';

// Imgaes gallery
ProductDetailsView.prototype.gallery = null;

// Zoom layer max. width
ProductDetailsView.prototype.zoomMaxWidth = 460;

// Zoom widget
ProductDetailsView.prototype.zoomWidget = false;

// Zoom layer max. width
ProductDetailsView.prototype.kZoom = 1.3;

// Postprocess widget
ProductDetailsView.prototype.postprocess = function(isSuccess, initial)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {

    // Save gallery list items
    this.gallery = jQuery('.image .product-image-gallery li', this.base);

    var o = this;

    // Arrow-based image navigation
    jQuery('.image .left-arrow', this.base).click(
      function (event) {
        o.switchImage(-1);
      }
    );

    jQuery('.image .right-arrow', this.base).click(
      function (event) {
        o.switchImage(1);
      }
    );

    // Form AJAX-based submit
    jQuery('form.product-details', this.base).eq(0).bind(
      'submit',
      function(event)
      {
        return o.addProductToCart(event, this);
      }
    )

    // Cloud zoom
    var cloud = jQuery('.cloud-zoom', this.base);

    if (cloud.length) {
      this.zoomWidget = true;

      if (core.getCommentedData(cloud, 'kZoom')) {
        this.kZoom = core.getCommentedData(cloud, 'kZoom');
      }

      var imageWrapper = jQuery(document.createElement('div')).addClass('wrapper');
      cloud.wrap(imageWrapper);
    }

    if (jQuery('.product-image-gallery li a').length) {
      // TODO: improve to skip additional JS manipulations
      // like resizing etc when it is not needed
      this.selectImage(0);
    } else if (this.zoomWidget && !cloud.data('zoom')) {
      cloud.CloudZoom();
    }

    // Change Continue shopping button for QuickLook mode
    if (this.base.hasClass('product-quicklook') && 0 < this.base.parents('.blockUI').length) {
      jQuery('button.continue', this.base)
        .unbind('click')
        .removeAttr('onclick');
      jQuery('button.continue', this.base).click(
        function() {
          popup.close();
          return false;
        }
      );
    }

    // Gallery
    if (typeof(window.lightBoxImagesDir) != 'undefined') {
      jQuery('.loupe', this.base).click(
        function(event) {
          o.showLightbox();
          setTimeout(
            function() {
              jQuery('.product-image-gallery li.selected a').eq(0).trigger('click');
            },
            500
          );

          return false;
        }
      );
    }

    this.hideLightbox();

    // Tabs
    jQuery('.product-details-tabs .tabs li span', this.base).click(
      function () {
        if (!jQuery(this).parent().hasClass('active')) {

          var id = this.id.substr(5);

          jQuery(this).parents('ul').eq(0).find('li.active').removeClass('active');
          jQuery(this).parent().addClass('active');

          var box = jQuery(this).parents('.product-details-tabs');
          box.find('.tab-container').hide();
          box.find('#' + id).show();
        }

        return true;
      }
    );
  }
}

ProductDetailsView.prototype.showLightbox = function()
{
  var o = this;

  jQuery('.product-image-gallery a', this.base)
    .unbind('click')
    .colorbox(
      {
        onComplete: function() {
          jQuery('#cboxCurrent').css('display', 'none');
        },
        onClosed: function() {
          o.hideLightbox();
        }
      }
    );
}

ProductDetailsView.prototype.hideLightbox = function()
{
  var o = this;

  jQuery('.product-image-gallery a', this.base)
    .unbind('click')
    .bind(
      'click',
      function(event) {
        event.stopPropagation();

        if (!jQuery(this).parents('li').eq(0).hasClass('selected')) {

          var i = jQuery.inArray(this, jQuery(this).parents('ul').eq(0).find('a').get());

          o.selectImage(i);
        }

        return false;
      }
    );
}

// Get base element for shade / unshade operation
ProductDetailsView.prototype.getShadeBase = function() {
  return jQuery('.shade-base', this.base).eq(0);
}

// Image gallery switcher
ProductDetailsView.prototype.switchImage = function(diff)
{
  var selected = -1;
  var i = 0;

  // Detect current index
  this.gallery.each(
      function() {
        if (selected == -1 && jQuery(this).hasClass('selected')) {

          selected = i;
        }

        i++;
      }
  );

  if (selected == -1) {

    selected = 0;
  }

  // Calculate new position
  var next = selected + diff;

  if (next < 0) {

    next = this.gallery.length - Math.abs(next) % this.gallery.length;

  } else if (next >= this.gallery.length) {

    next = next % this.gallery.length;
  }

  return this.selectImage(next);
}

// Select image from gallery
ProductDetailsView.prototype.selectImage = function(pos)
{
  this.gallery.removeClass('selected');

  // Refresh main image and another options + cloud zoom plugin restart
  next = this.gallery.eq(pos);
  next.addClass('selected');

  if (this.zoomWidget) {

    var cloud = jQuery('.cloud-zoom', this.base);

    if (cloud.data('zoom')) {

      cloud.data('zoom').destroy();

    } else {

      cloud.unbind('click', this.linkClickHandler);
    }

    cloud.attr('href', jQuery('a', next).attr('href'));
  }

  var middle = jQuery('img.middle', next).eq(0)

  if (middle) {

    jQuery('.image .product-photo img', this.base)
      .hide()
      .attr('src', middle.attr('src'))
      .width(middle.width())
      .height(middle.height())
      .show();

    // Center align images
    var shiftX = Math.max(0, jQuery('.image .product-photo', this.base).width() - middle.width());
    var shiftY = Math.max(0, jQuery('.image .product-photo', this.base).height() - middle.height());

    if (this.zoomWidget) {

      jQuery('.image .product-photo .wrapper').css('padding', shiftY/2 + 'px ' + shiftX/2 + 'px');
      jQuery('img', cloud).css('padding', 0);

    } else {

      jQuery('.image .product-photo img').css('padding', shiftY/2 + 'px ' + shiftX/2 + 'px');
    }
  }

  eval('var tmp = {' + jQuery('a', next).attr('rev') + '}');

  if (this.zoomWidget) {

    if (tmp.width > middle.attr('width') * this.kZoom || tmp.height > middle.attr('height') * this.kZoom) {

      cloud.CloudZoom();

    } else {

      cloud.click(this.linkClickHandler);
    }
  }
}

// Get additional parameters
ProductDetailsView.prototype.getParams = function(params)
{
  params = this.callSupermethod('getParams', arguments);

  params.product_id = this.productId;
  params.added = 1;

  return params;
}

// Form submit handler
ProductDetailsView.prototype.addProductToCart = function(event, form)
{
  var o = this;

  var callback = function (XMLHttpRequest, textStatus, data, isValid) {
    return o.postprocessAdd2Cart(XMLHttpRequest, textStatus, data, isValid);
  }

  if (this.submitForm(form, callback)) {

    if (popup && popup.elementId == 'product-quicklook') {

      popup.openAsWait();

    } else {

      this.shade();
    }

    this.base.get(0).controller.selfAdded = true;
  }

  return false;
}

// Form POST processor
ProductDetailsView.prototype.postprocessAdd2Cart = function(XMLHttpRequest, textStatus, data, isValid)
{
  this.base.get(0).controller.selfAdded = false;

  if (isValid) {

    if (popup) {

      popup.close();
    }

    this.load();

  } else {

    this.unshade();
  }
}

core.autoload(ProductDetailsController);
