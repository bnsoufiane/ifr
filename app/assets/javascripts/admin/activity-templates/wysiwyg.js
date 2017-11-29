(function () {
	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	ActivityTemplates.WysiwygView = Backbone.View.extend({
		initialize: function () {
			this.$editor = this.$('.wysiwyg-editor-container > textarea:first');

			this.listenTo(Backbone, 'lesson.before-save', function () {
				// Save a page's content to the model when lesson save is initiated.
				this.model.set('content', this.$editor.html());
			});
		},

		render: function () {
			if (this.model.has('content')) {
				this.$editor.text(this.model.get('content'));
			}

			this.$editor.tinymce({
				theme_url: '/assets/lib/tinymce/themes/modern/theme.min.js',
				skin_url: '/assets/lib/tinymce/skins/lightgray',

				height: 400,

				external_plugins: {
					'table': '/assets/lib/tinymce/plugins/table/plugin.min.js',
					'input': '/assets/admin/activity-templates/wysiwyg-input-plugin.js'
				},

				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | input"
			});
		}
	});
})();
