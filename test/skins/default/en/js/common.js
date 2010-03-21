/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: common.js 2190 2010-03-09 23:59:26Z vvs $
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function parseUri (str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};

parseUri.options = {
	strictMode: false,
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};

function formModify(obj, url)
{
	var form = obj.form;
	if (form) {
		var parsed = parseUri(url);

		for (var key in parsed.queryKey) {
			if (form[key]) {
				form[key].value = parsed.queryKey[key];

			} else {
				var input = document.createElement('INPUT');
				input.type = 'hidden';
				input.name = key;
				input.value = parsed.queryKey[key];

				form.appendChild(input);
			}
		}

		if (
			form.getAttribute('method')
			&& form.getAttribute('method').toUpperCase() == 'POST'
			&& (parsed.query || parsed.path || parsed.host)
		) {
			form.setAttribute('action', url);
		}
	}

	return true;
}

function eventBind(obj, e, func)
{
	if ($) {
		$(obj).bind(e, func);

	} else if (window.addEventListener) {
		obj.addEventListener(e, func, false);

	} else if (window.attachEvent) {
		window.attachEvent('on' + e, func);
	}
}


var URLHandler = {

  mainParams: {target: true, action: true},

  baseURLPart: 'cart.php?',
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


// Check for the AJAX support
var ajaxSupport = null;

function hasAJAXSupport()
{
  if (null == ajaxSupport) {
    var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
    ajaxSupport = xhr ? true : false;
  }

  return ajaxSupport;
}

