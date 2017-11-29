tinymce.PluginManager.add('input', function (editor, url) {
	function showDialog() {
		var selection = editor.selection.getNode();
		var data = {};

		if (selection && typeof selection.attributes['data-input-field'] !== 'undefined') {
			data.label = selection.attributes.placeholder.value;
		}

		var win = editor.windowManager.open({
			title: 'Insert/edit Field',
			data: data,

            body: [
                {type: 'textbox', name: 'label', label: 'Field Label'}
            ],

            onsubmit: function(e) {
				var input = editor.dom.createHTML('input', {
					type: 'text',
					placeholder: e.data.label,
					readonly: 'readonly',
					'data-input-field': true
				});

                editor.insertContent(input);
				editor.nodeChanged();
            }
		});
	}

    // Add a button that opens a window
    editor.addButton('input', {
        tooltip: 'Empty Field',
        icon: 'input',

        onclick: showDialog
    });
});
