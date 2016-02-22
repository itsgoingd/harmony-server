class AppSettingsView

	constructor: (options) ->

		this.app = options.app

		this.initUserManagement()

	initUserManagement: () ->

		$('.applicationSettings-form-addUser').on 'click', (event) ->

			event.preventDefault()

			$('.applicationSettings-form-newUser').removeClass('hidden')

			$(this).addClass('button-disabled')

		$('.applicationSettings-form-newUser-add').on 'click', (event) =>

			event.preventDefault()

			request = { email: $('.applicationSettings-form-newUser-emailInput').val() }

			$.post('/apps/' + this.app + '/users', request, (data) ->

				$('.applicationSettings-form-users').html(data)

				$('.applicationSettings-form-newUser-error').addClass('hidden')
				$('.applicationSettings-form-newUser').addClass('hidden')
				$('.applicationSettings-form-addUser').removeClass('button-disabled')
				$('.applicationSettings-form-newUser-emailInput').val('')

			).fail((data) ->

				$('.applicationSettings-form-newUser-error').text(data.responseJSON.message)
				$('.applicationSettings-form-newUser-error').removeClass('hidden')

			)

		self = this

		$('.applicationSettings-form-users').on 'click', '.applicationSettings-form-user-remove', (event) ->

			event.preventDefault()

			request = { _method: 'delete' }

			$.post('/apps/' + self.app + '/users/' + $(this).data('id'), request, (data) ->

				$('.applicationSettings-form-users').html(data)

			)

module.exports = AppSettingsView
