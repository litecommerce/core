/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * javascript core
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * OOP core
 */

// OOP extends emulation
function extend(child, parent) {
  var F = function() { };
  F.prototype = parent.prototype;
  child.prototype = new F();
  child.prototype.constructor = child;
  child.superclass = parent.prototype;
}

// Decorate / add method
function decorate(class, methodName, method)
{
  class = getClassByName(class);

  var result = false;

  if (class) {
    method.previousMethod = 'undefined' == typeof(class.prototype[methodName]) ? null : class.prototype[methodName];
    class.prototype[methodName] = method;
    result = true;
  }

  return result;
}

// Get class object by name (or object)
function getClassByName(class)
{
  if (class && class.constructor == String) {
    class = eval('(("undefined" != typeof(window.' + class + ') && ' + class + '.constructor == Function) ? ' + class + ' : null)');

  } else if (!class || class.constructor != Function) {
    class = null;
  }

  return class;
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
  isReady: false,

  isRequesterEnabled: false,

  savedEvents: [],

  messages: $({}),

  // Trigger common message
  trigger: function(name, params)
  {
    var result = true;

    name = name.toLowerCase();

    if (this.isReady) {
    
      if ('undefined' != typeof(window.console)) {
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

    options = $.extend(
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

    return $.ajax(options);
  },

  // Post form data to server
  post: function(url, callback, data, options)
  {
    options = options || {};

    options = $.extend(
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

    return $.ajax(options);
  },

  // Response preprocess (run callback or not)
  preprocessResponse: function(xhr, options, callback)
  {
    var result = true;

    if (270 == xhr.status && xhr.getResponseHeader('Location') && (!options || !options.rpc)) {
      core.get(
        xhr.getResponseHeader('Location'),
        callback
      );

      result = false;
    }

    return result;
  },

  // Process response from server
  processResponse: function(xhr)
  {
    if (4 == xhr.readyState && (200 == xhr.status || (270 <= xhr.status && 300 > xhr.status))) {
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
      [{'type': 'error', 'message': message}]
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

    // TODO - add request languale label from server-side
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

      var xhr = $.ajax(
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
        var data = $.parseJSON(xhr.responseText);

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

    $(document).ready(
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
    var m = $(obj)
      .attr('class')
      .match(new RegExp(prefix + '-([^ ]+)( |$)'));

    return m ? m[1] : null;
  },

  // Return value of variable that is given in comment block: e.g. <!-- 'productid': '100001', 'var': 'value', -->"
  getCommentedData: function(obj, prefix)
  {
    var children = $(obj).get(0).childNodes;
    var re = new RegExp(prefix + '-([^;]+)(;|$)');
    var m = false;
    
    for (var i = 0; i < children.length && !m; i++) {
      if (8 === children[i].nodeType && -1 != children[i].data.search(re)) {
        m = children[i].data.match(re);
      }
    }

    return m ? m[1] : null;
  },

  // Toggle link text and toggle obj visibility
  toggleText : function (link, text, obj)
  {
    if (undefined === link.prevValue) {
      link.prevValue = $(link).html();
    }
    $(link).html($(link).html() === text ? link.prevValue : text);
    $(obj).toggle();
  }

};

// HTTP requester detection
try {

  var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
  core.isRequesterEnabled = xhr ? true : false;

} catch(e) { }

// Common onready event handler
$(document).ready(
  function() {
    core.isReady = true;
    core.trigger('load');
    for (var i = 0; i < core.savedEvents.length; i++) {
        core.trigger(core.savedEvents[i].name, core.savedEvents[i].params);
    }
    core.savedEvents = [];
  }
);
