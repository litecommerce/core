/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * javascript core
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * OOP core
 */

// OOP extends emulation
function extend(child, p) {
  var F = function() { };
  F.prototype = p.prototype;
  child.prototype = new F();
  child.prototype.constructor = child;
  child.superclass = p.prototype;
}

// Decorate / add method
function decorate(c, methodName, method)
{
  c = getClassByName(c);

  var result = false;

  if (c) {
    method.previousMethod = 'undefined' == typeof(c.prototype[methodName]) ? null : c.prototype[methodName];
    c.prototype[methodName] = method;
    result = true;
  }

  return result;
}

// Get class object by name (or object)
function getClassByName(c)
{
  if (c && c.constructor == String) {
    c = eval('(("undefined" != typeof(window.' + c + ') && ' + c + '.constructor == Function) ? ' + c + ' : null)');

  } else if (!c || c.constructor != Function) {
    c = null;
  }

  return c;
}

// Base class
function Base()
{
}

// Get superclass without class name
Base.prototype.getSuperclass = function()
{
    var m = this.constructor.toString().match(/function ([^\(]+)/);

    return eval(m[1] + '.superclass');
}

// Call parent method by name nad arguments list
Base.prototype.callSupermethod = function(name, args)
{
    superClass = this.getSuperclass();

    return superClass[name].apply(this, args);
}

// Core definition
window.core = {

  isDebug: false,

  isReady: false,

  isRequesterEnabled: false,

  savedEvents: [],

  messages: jQuery({}),

  // Trigger common message
  trigger: function(name, params)
  {
    var result = true;

    name = name.toLowerCase();

    if (this.isReady) {

      if (this.isDebug && 'undefined' != typeof(window.console)) {
        if (params) {
          console.log('Fire \'' + name + '\' event with arguments: ' + var_export(params, true));

        } else {
          console.log('Fire \'' + name + '\' event');
        }
      }

      result = this.messages.trigger(name, [params]);

    } else {
      this.savedEvents.push(
        {
          name: name,
          params: params
        }
      );
    }

    return result;
  },

  // Bind on common messages
  bind: function(name, callback)
  {
    this.messages.bind(name.toLowerCase(), callback);
  },

  // Unbind on common messages
  unbind: function(name, callback)
  {
    this.messages.unbind(name.toLowerCase(), callback);
  },

  // Get HTML data from server
  get: function(url, callback, data, options)
  {
    options = options || {};

    options = jQuery.extend(
      {
        async:       true,
        cache:       false,
        complete:    function(XMLHttpRequest, textStatus)
          {
            var callCallback = core.preprocessResponse(XMLHttpRequest, options, callback);
            data = core.processResponse(XMLHttpRequest);

            return (callCallback && callback) ? callback(XMLHttpRequest, textStatus, data) : true;
          },
        contentType: 'text/html',
        global:      false,
        timeout:     15000,
        type:        'GET',
        url:         url,
        data:        data
      },
      options
    );

    return jQuery.ajax(options);
  },

  // Post form data to server
  post: function(url, callback, data, options)
  {
    options = options || {};

    options = jQuery.extend(
      {
        async:       true,
        cache:       false,
        complete:    function(XMLHttpRequest, textStatus)
          {
            var callCallback = core.preprocessResponse(XMLHttpRequest, options, callback);
            data = core.processResponse(XMLHttpRequest);
            var notValid = !!XMLHttpRequest.getResponseHeader('not-valid');

            return (callCallback && callback) ? callback(XMLHttpRequest, textStatus, data, !notValid) : true;
          },
        contentType: 'application/x-www-form-urlencoded',
        global:      false,
        timeout:     15000,
        type:        'POST',
        url:         url,
        data:        data
      },
      options
    );

    return jQuery.ajax(options);
  },

  // Response preprocess (run callback or not)
  preprocessResponse: function(xhr, options, callback)
  {
    var result = true;

    var responseStatus = parseInt(xhr.getResponseHeader('ajax-response-status'));

    if (200 == xhr.status && 270 == responseStatus && xhr.getResponseHeader('AJAX-Location') && (!options || !options.rpc)) {
      core.get(
        xhr.getResponseHeader('AJAX-Location'),
        callback
      );

      result = false;

    } else if (278 == responseStatus) {

      // Redirect
      var url = xhr.getResponseHeader('AJAX-Location');

      if (url) {
        self.location = url;
      } else {
        self.location.reload(true);
      }

      result = false;
    }

    return result;
  },

  // Process response from server
  processResponse: function(xhr)
  {
    var responseStatus = parseInt(xhr.getResponseHeader('ajax-response-status'));

    if (4 == xhr.readyState && 200 == xhr.status) {
      var list = xhr.getAllResponseHeaders().split(/\n/);

      for (var i = 0; i < list.length; i++) {
        if (-1 !== list[i].search(/^event-([^:]+):(.+)/i)) {

          // Server-side event
          var m = list[i].match(/event-([^:]+):(.+)/i);
          core.trigger(m[1].toLowerCase(), eval('(' + m[2] + ')'));
        }
      }
    }

    return (4 == xhr.readyState && 200 == xhr.status) ? xhr.responseText : false;
  },

  showInternalError: function()
  {
    return this.showError(this.t('Javascript core internal error. Page will be refreshed automatically'));
  },

  showServerError: function()
  {
    return this.showError(this.t('Background request to server side is failed. Page will be refreshed automatically'));
  },

  showError: function(message)
  {
    core.trigger(
      'message',
      {'type': 'error', 'message': message}
    );
  },

  languageLabels: [],

  t: function(label, substitute)
  {
    var found = false;
    for (var i = 0; i < this.languageLabels.length && !found; i++) {
      if (this.languageLabels[i].name == label) {
        label = this.languageLabels[i].label;
        found = true;
      }
    }

    // TODO - add request language label from server-side
    if (!found) {
      var loadedLabel = core.rest.get('translation', label, false);
      if (loadedLabel) {
        this.languageLabels.push(
          {
            name:  label,
            label: loadedLabel
          }
        );
        label = loadedLabel;
      }
    }

    if (substitute) {
      for (var i in substitute) {
        label = label.replace(new RegExp('{{' + i + '}}'), substitute[i]);
      }
    }

    return label;
  },

  rest: {

    lastResponse: null,

    request: function(type, name, id, data, callback)
    {
      if (!type || !name) {
        return false;
      }

      this.lastResponse = null;

      var xhr = jQuery.ajax(
        {
          async: false !== callback,
          cache: false,
          complete: function(xhr, status) {
            return this.callback(xhr, status, callback);
          },
          context: this,
          data: data,
          timeout: 15000,
          type: ('get' == type ? 'GET' : 'POST'),
          url: URLHandler.buildURL(
            {
              target: 'rest',
              action: type,
              name:   name,
              id:     id
            }
          )
        }
      );

      if (false === callback) {
        xhr = (this.lastResponse && this.lastResponse.status == 'success') ? this.lastResponse.data : null;
      }

      return xhr;
    },

    get: function(name, id, callback) {
      return this.request('get', name, id, null, callback);
    },

    post: function(name, id, data, callback) {
      return this.request('post', name, id, data, callback);
    },

    put: function(name, data, callback) {
      return this.request('put', name, null, data, callback);
    },

    'delete': function(name, id, callback) {
      return this.request('delete', name, id, null, callback);
    },

    callback: function(xhr, status, callback)
    {
      try {
        var data = jQuery.parseJSON(xhr.responseText);

      } catch(e) {
        var data = null;
      }

      if (false === callback) {
        core.rest.lastResponse = data;

      } else if (callback) {
        callback(xhr, status, data);
      }
    }
  },

  autoload: function(className)
  {
    if ('function' == typeof(className)) {
      var m = className.toString().match(/function ([^\(]+)/);
      className = m[1];
    }

    jQuery(document).ready(
      function() {
        if ('undefined' != typeof(window[className])) {
          if ('function' == typeof(window[className].autoload)) {
            window[className].autoload();

          } else {
            eval('new ' + className + '();');
          }
        }
      }
    );
  },

  // Return value of variable that is given in class attribute: e.g. class="superclass productid-100001 test"
  getValueFromClass: function(obj, prefix)
  {
    var m = jQuery(obj)
      .attr('class')
      .match(new RegExp(prefix + '-([^ ]+)( |$)'));

    return m ? m[1] : null;
  },

  // Return value of variable that is given in comment block: e.g. <!-- 'productid': '100001', 'var': 'value', -->"
  getCommentedData: function(obj, name)
  {
    var children = jQuery(obj).get(0).childNodes;
    var re = /DATACELL/;
    var m = false;

    for (var i = 0; i < children.length && !m; i++) {
      if (8 === children[i].nodeType && -1 != children[i].data.search(re)) {
        m = children[i].data.replace(re, '');
        m = m.replace(/^\n\r/, '').replace(/\r\n$/, '');
        try {
          m = eval('(' + m + ')');
        } catch(e) {
          m = false;
        }
      }
    }

    if (m && name) {
      m = 'undefined' == typeof(m[name]) ? null : m[name];
    }

    return m ? m : null;
  },

  // Toggle link text and toggle obj visibility
  toggleText : function (link, text, obj)
  {
    if (undefined === link.prevValue) {
      link.prevValue = jQuery(link).html();
    }
    jQuery(link).html(jQuery(link).html() === text ? link.prevValue : text);
    jQuery(obj).toggle();
  },

  // Decorate class after page loading
  decorate: function(className, methodName, func)
  {
    core.bind(
      'load',
      function() {
        decorate(className, methodName, func);
      }
    );
  },

  // Decorate some class after page loading
  decorates: function(list, func)
  {
    core.bind(
      'load',
      function() {
        for (var i = 0; i < list.length; i++) {
          decorate(list[i][0], list[i][1], func);
        }
      }
    );
  },

  stringToNumber: function(number, dDelim, tDelim)
  {
    number = number.replace(new RegExp(tDelim, 'g'), '');

    var a = number.split(dDelim);

    return parseFloat(a[0] + '.' + a[1]);
  },

  numberToString: function(number, dDelim, tDelim)
  {
    number = number.toString();
    /*
      Author: Robert Hashemian
      http://www.hashemian.com/

      You can use this code in any manner so long as the author's name,
      Web address and this disclaimer is kept intact.
     ********************************************************/

    var a = number.split('.');
    var x = a[0]; // decimal
    var y = a[1]; // fraction
    var z = "";

    if (typeof(x) != "undefined") {
      // reverse the digits. regexp works from left to right.
      for (var i = x.length - 1; i >= 0; i--) {
        z += x.charAt(i);
      }

      // add separators. but undo the trailing one, if there
      z = z.replace(/(\d{3})/g, "$1" + tDelim);

      if (z.slice(-tDelim.length) == tDelim){
        z = z.slice(0, -tDelim.length);
      }

      x = "";

      // reverse again to get back the number
      for ( i = z.length - 1; i >= 0; i--) {
        x += z.charAt(i);
      }

      // add the fraction back in, if it was there
      if (typeof(y) != "undefined" && y.length > 0) {
        x += dDelim + y;
      }
    }

    return x;
  }

};

// HTTP requester detection
try {

  var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
  core.isRequesterEnabled = xhr ? true : false;

} catch(e) { }

// Common onready event handler
jQuery(document).ready(
  function() {
    core.isReady = true;
    core.trigger('load');
    for (var i = 0; i < core.savedEvents.length; i++) {
        core.trigger(core.savedEvents[i].name, core.savedEvents[i].params);
    }
    core.savedEvents = [];
  }
);

/**
 * Common functions
 */

// Check - specified object is HTML element or not
function isElement(obj, type)
{
  return obj && typeof(obj.tagName) != 'undefined' && obj.tagName.toLowerCase() == type;
}

core.bind(
  'load',
  function () {
    jQuery('input[type=checkbox]').each(
      function () {
        var checkbox = this;

        jQuery(checkbox).bind(
          'click',
          function () {
            return !jQuery(checkbox).attr('readonly');
          }
        );
      }
    );
  }
);
