class CrashDetailsView

	constructor: () ->

		this.initNavigation()
		this.initCallStack()

	initNavigation: () ->

		$('.crash-navigation a').on 'click', (event) ->
			event.preventDefault()

			$('.crash-navigation a').removeClass 'active'
			$(this).addClass 'active'

			$('.crash-content').addClass 'hidden'
			$('.crash-content' + $(this).attr('href')).removeClass 'hidden'

	initCallStack: () ->

		$('.crash-callStack-item-content-call-arguments').each () ->

			if $(this).text().length

				$(this).data('content', $(this).text())

				$(this).html('<span class="crash-callStack-item-content-call-arguments-show"><i class="fa fa-ellipsis-h"></i></span>')

		$('.crash-callStack-item-content-call-arguments-show').on 'click', () ->

			$args = $(this).parents('.crash-callStack-item-content-call-arguments')

			$args.text($args.data('content'))

		$('.crash-callStack-item-actions-showPreview').on 'click', (event) ->

			event.preventDefault()

			if ($(this).hasClass('crash-callStack-item-actions-showPreview-disabled'))
				return

			$(this).parents('.crash-callStack-item').find('.crash-callStack-item-filePreview').toggleClass('hidden')

module.exports = CrashDetailsView
