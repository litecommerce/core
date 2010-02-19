// SVN $Id$

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
