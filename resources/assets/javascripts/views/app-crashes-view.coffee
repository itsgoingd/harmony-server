class AppCrashesView

	constructor: (options) ->

		this.initFilter()

	initFilter: () ->

		$('.application-filterCrashes-order-select-value').on 'click', () ->

			$('.application-filterCrashes-order-options').toggleClass('hidden')

		$('.application-filterCrashes-order-options a').on 'click', () ->

			if ($(this).data('order'))

				$('.application-filterCrashes-orderInput').val($(this).data('order'))

				$('.application-filterCrashes-form').submit()

			else

				showArchived = parseInt($('.application-filterCrashes-showArchivedInput').val(), 10)

				$('.application-filterCrashes-showArchivedInput').val(if showArchived then 0 else 1)

				$('.application-filterCrashes-form').submit()

module.exports = AppCrashesView
