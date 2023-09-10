BX.ready(function () {
	const formFeedback = BX('formFeedback');

	const errorBlock = BX.findChild(
		formFeedback,
		{
			class: 'jsErrorMessage'
		},
		true,
		false
	);

	const successBlock = BX.findChild(
		formFeedback,
		{
			class: 'jsFormSuccess'
		},
		true,
		false
	);

	BX.bind(formFeedback, 'submit', function (e) {
		const data = BX.ajax.prepareForm(formFeedback).data;
		const hlId = data['HIGHLOADBLOCK_ID'];

		delete data['HIGHLOADBLOCK_ID'];

		BX.ajax.runComponentAction(
			'custom:custom.form',
			'createEntry',
			{
				mode: 'class',
				data: {
					'dataForm': data,
					'hlId': hlId,
					'mailEvent': 'FEEDBACK_CUSTOM'
				},
				dataType: 'json',
				method: 'POST',
			}
		).then(function () {
			BX.show(successBlock);
			BX.hide(errorBlock);
		}, function (response) {
			response.errors.forEach(function (error) {
				errorBlock.innerHTML = error.message[0];
			});
			BX.show(errorBlock);
			BX.hide(successBlock);
		});

		return BX.PreventDefault(e);
	});
});

