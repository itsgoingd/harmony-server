httpRequest = require('./http-request')

$(() ->
	$('a[data-method]').click((ev) ->

		ev.stopPropagation();
		ev.preventDefault();

		data = {}

		for attribute in this.attributes

			if (! (attribute instanceof Object) || typeof(attribute.name) != 'string')
				continue

			found = attribute.name.match(/data-values-(.+)/i)

			if (! found)
				continue

			data[found[1]] = attribute.value

		return httpRequest($(this).attr('href'), $(this).data('method'), data);
	)
)
