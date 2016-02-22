window.AppCrashesView = require('./views/app-crashes-view')
window.AppSettingsView = require('./views/app-settings-view')
window.CrashDetailsView = require('./views/crash-details-view')

require('./helpers/link-methods')

$ ->
	$('.topMenu-application-menu').on 'click', (event) ->
		event.preventDefault()

		$('.topMenu-applications').toggleClass 'hidden'

	$.ajaxSetup({
		headers : {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	Prism.plugins.NormalizeWhitespace.setDefaults({
		'remove-trailing': true,
		'remove-indent': true,
		'left-trim': false,
		'right-trim': false
	})
