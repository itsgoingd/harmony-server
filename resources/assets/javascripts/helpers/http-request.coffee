httpRequest = (url, method, data) ->

	data = data || {}

	$form = $('<form style="display:none"></form>')
		.attr('action', url)
		.attr('method', method)

	if method != 'get' && method != 'post'
		$form.attr('method', 'post')
			.append('<input type="hidden" name="_method" value="' + method + '">')

	if method != 'get'
		$form.append('<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">')

	for key in data
		$input = $('<input type="hidden" name="" value="">')
			.attr('name', key)
			.val(data[key])

		$form.append($input)

	$form.appendTo('body').submit()

module.exports = httpRequest
