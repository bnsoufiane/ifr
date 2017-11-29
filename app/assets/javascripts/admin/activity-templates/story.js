(function () {
	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	rivets.formatters.assetPath = function (value, path) {
		return '/' + (path ? path + '/' : '') + value;
	};

	rivets.formatters.ifEmptyShowNone = function (value) {
		return (value && value.length > 0 ? value : '<span class="text-muted">None</span>');
	};

	// Models
	var StoryItem = Backbone.Model.extend({
		defaults: {
			is_right_side: false
		},

		toJSON: function () {
			var data = _.extend({}, this.attributes);
			delete data.character;
			return data;
		}
	});

	var StoryCharacter = Backbone.Model.extend({});

	// Collections
	var StoryItems = Backbone.Collection.extend({
		model: StoryItem,
		comparator: 'order'
	});

	var StoryCharacters = Backbone.Collection.extend({
		url: '/admin/activity-templates/story-characters',
		model: StoryCharacter
	});

	// Views
	var StoryCharacterView = Backbone.View.extend({
		className: 'character-profile',

		tagName: 'li',

		events: {
			'click .edit-character': 'editCharacter',
			'click .remove-character': 'removeCharacter',
			'click': 'selectCharacter'
		},

		initialize: function (options) {
			this.template = options.template;

			this.listenTo(this.model, 'destroy', this.remove);
		},

		render: function () {
			this.$el.html(this.template({
				character: this.model.toJSON()
			}));
			this.bindings = rivets.bind(this.$el, { character: this.model });
			return this;
		},

		editCharacter: function () {
			this.trigger('edit', this.model);
			return false;
		},

		selectCharacter: function () {
			this.trigger('selected', this.model);
		},

		removeCharacter: function () {
			if (confirm('Are you sure that you want to remove this character?')) {
				this.model.destroy();
			}
			return false;
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.apply(this, arguments);
		}
	});

	var StoryCharactersModal = Backbone.View.extend({
		className: 'modal fade',

		events: {
			'click .create-char': 'createCharacter',
			'click .close-editor': 'cancelChanges',
			'click .save-changes': 'saveChanges',
			'click .upload-picture': 'uploadPicture'
		},

		initialize: function (options) {
			options || (options = {});

			this.characterViews = [];
			this.itemTemplate = options.itemTemplate;
			this.template = Handlebars.compile(options.template || $('#story-characters-modal').html());

			this.collection = new StoryCharacters();
			this.listenTo(this.collection, 'reset', this.addAllCharacters);
			this.listenTo(this.collection, 'add', this.addCharacter);

			this.initializeStateMachine();
		},

		initializeStateMachine: function () {
			this.editorState = new machina.Fsm({
				initialState: 'default',

				states: {
					'default': {},

					'editing': {
						_onEnter: _.bind(function () {
							this.$('.character-form').removeClass('hidden');
							this.$('.create-char').hide();
						}, this),

						_onExit: _.bind(function () {
							this.$('.character-form').addClass('hidden');
							this.$('.create-char').show();
						}, this)
					}
				}
			});
		},

		closeEditor: function () {
			if (this.editorState.state != 'editing') {
				return;
			}

			this.editorState.transition('default');

			this.editorBinding.unbind();
			this.editorModel = null;
			this.editorInitialModel = null;
		},

		cancelChanges: function () {
			if (this.editorState.state != 'editing') {
				return;
			}

			if (this.editorModel.id) {
				this.editorModel.set(this.editorInitialModel);
			} else {
				this.editorModel.destroy();
			}
			this.closeEditor();
		},

		saveChanges: function () {
			if (this.editorState.state != 'editing') {
				return;
			}

			this.editorModel.save({
				success: _.bind(this.closeEditor, this),
				error: _.bind(this.closeEditor, this)
			});

			this.closeEditor();
			$("div.modal-body").animate({ scrollTop: $('div.modal-body')[0].scrollHeight}, 1000);
		},

		/**
		 * Changes the modal window to the editor mode.
		 * @param {StoryCharacter} model A model to edit
		 */
		setEditor: function (model) {
			this.editorState.transition('editing');

			this.editorBinding = rivets.bind(this.$('.character-form'), {
				character: model
			});

			this.editorModel = model;

			// Save an initial model state to restore it if a user closes the editor.
			this.editorInitialModel = _.extend({}, model.attributes);
		},

		createCharacter: function () {
			var char = new StoryCharacter();
			this.collection.add(char);
			this.setEditor(char);
		},

		addCharacter: function (character) {
			var view = new StoryCharacterView({
				model: character,
				template: this.itemTemplate
			});

			this.listenTo(view, 'selected', this.selectCharacter);
			this.listenTo(view, 'edit', this.setEditor);

			character.view = view;

			this.$charactersList.append(view.render().el);
			this.characterViews.push(view);
		},

		cleanAllCharacters: function () {
			_.invoke(this.characterViews, 'remove');
		},

		addAllCharacters: function () {
			this.collection.each(_.bind(this.addCharacter, this));
		},

		selectCharacter: function (model) {
			var self = this;

			function select() {
				self.trigger('selected', model);
				self.$el.modal('hide');
			}

			if (this.editorState.state == 'editing') {
				if (confirm('Do you want to save your changes before selecting a character?')) {
					this.editorModel.save({}, {
						success: select
					});
				} else {
					if (model == this.editorModel && this.editorModel.isNew()) {
						// If we're selecting unsaved new model - cancel selection.
						return;
					}
					select();
				}
			} else {
				select();
			}
		},

		renderModal: function () {
			this.$el.html(this.template())
				.appendTo($('body'))
				.modal()
				.on('hidden.bs.modal', _.bind(function () {
					this.remove();
				}, this));
		},

		/**
		 * Initializes sub-templates.
		 */
		initializeTemplates: function () {
			this.itemTemplate = Handlebars.compile(
				this.itemTemplate ? this.itemTemplate :
				this.$('#story-characters-modal-item-template').html()
			);
		},

		/**
		 * Initialize character editor functions
		 */
		renderEditor: function () {
			var button = this.$('.upload-picture');

			new ss.SimpleUpload({
				button: button,
				name: 'upload',
				url: '/admin/activity-templates/story-characters/upload',
				responseType: 'json',
				allowedExtensions: ['jpg', 'png', 'jpeg', 'webp'],

				onSubmit: function () {
					button.attr('disabled', 'disabled');
				},

				onComplete: _.bind(function (filename, response) {
					button.removeAttr('disabled');
					this.updateCharacterPicture(response);

					this.editorModel.save({
						success: _.bind(this.closeEditor, this),
						error: _.bind(this.closeEditor, this)
					});
				}, this),

				onError: function () {
					button.removeAttr('disabled');
					alert('Upload has failed. Try again, please.');
				}
			});
		},

		updateCharacterPicture: function (response) {
			if (this.editorState.state != 'editing') {
				return;
			}
			this.editorModel.set('picture', response.filename);
		},

		render: function () {
			this.renderModal();
			this.initializeTemplates();
			this.renderEditor();

			this.$charactersList = this.$('.story-characters');
			this.collection.fetch();

			return this;
		},

		remove: function () {
			this.cleanAllCharacters();
			this.closeEditor();
			Backbone.View.prototype.remove.apply(this, arguments);
		}
	});
	
	var StoryItemView = Backbone.View.extend({
		className: 'story-item',

		events: {
			'click .select-char': 'showCharactersModal',
			'click .remove-story': 'removeItem'
		},

		initialize: function (options) {
			this.template = options.template;
			this.characters = options.characters;

			if (this.model.get('character_id')) {
				// Retrieve a stored character from the repository.
				var characterId = this.model.get('character_id');
				this.model.set('character', this.characters.get(characterId));
			}

			this.listenTo(this.model, 'change:character', this.renderCharacter);
		},

		renderCharacter: function () {
			var character = this.model.get('character');

			var charEl = this.$('.character-profile');
			var noCharEl = this.$('.no-character');

			charEl[character ? 'removeClass' : 'addClass']('hidden');
			noCharEl[character ? 'addClass' : 'removeClass']('hidden');
		},

		render: function () {
			this.$el.html(this.template());
			this.bindings = rivets.bind(this.$el, { story: this.model });
			this.renderCharacter();
			return this;
		},

		showCharactersModal: function () {
			var modalView = new StoryCharactersModal({
				characters: this.characters
			});

			modalView.once('selected', _.bind(function (newChar) {
				this.model.set('character', newChar);
				this.model.set('character_id', newChar.id);
			}, this));

			modalView.render();
		},

		removeItem: function () {
			if (!this.model.has('text') || confirm('Are you sure that you want to remove this item?')) {
				this.model.collection.remove(this.model);
				this.remove();
			}
		},

		remove: function () {
			this.bindings.unbind(this.bindings);
			Backbone.View.prototype.remove.call(this);
		}
	});

	ActivityTemplates.StoryView = Backbone.View.extend({
		events: {
			'click .add-new-item': 'createStoryItem'
		},
		
		initialize: function () {
			this.views = [];

			this.storyItemTemplate = Handlebars.compile($('#story-item-template').html());
			this.$storyItems = this.$('.story-items');

			if (this.model.has('items')) {
				this.collection = new StoryItems(this.model.get('items'));
			} else {
				this.collection = new StoryItems();
				this.createStoryItem();
			}

			this.model.set('items', this.collection);

			this.storyCharacters = new StoryCharacters();

			this.listenTo(this.collection, 'add', this.addStoryItem);
		},

		createStoryItem: function () {
			// Get the last item alignment to inverse it.
			var lastItem = this.collection.last(), alignOnRight;

			if (lastItem) {
				alignOnRight = !lastItem.get('is_right_side');
			} else {
				alignOnRight = false;
			}
			
			this.collection.add(new StoryItem({
				is_right_side: +alignOnRight
			}));
			
			    setTimeout(function() {

            $('input[rv-checked]').each(function() {

                if ($(this).val() == "1" && $(this).is(':checked')) {
                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').insertAfter(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3').not('.div_profile').insertBefore(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('.remove-story').css("left", "auto").css("right", "0");

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').removeClass('text-right').addClass('text-left');
                }

            });

            $('body').on('change', 'input[rv-checked]' ,function() {

                if ($(this).val() == "0") {
                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3').not('.div_profile').insertAfter(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').insertBefore(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('.remove-story').css("right", "auto").css("left", "0");

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').removeClass('text-left').addClass('text-right');
                } else {
                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').insertAfter(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3').not('.div_profile').insertBefore(
                            $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                    $(this).parents('.story-control').siblings('.story-description').find('.remove-story').css("left", "auto").css("right", "0");

                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').removeClass('text-right').addClass('text-left');
                }
            });
        }, 1000)
			
		},

		addStoryItem: function (model) {
			var storyItemView = new StoryItemView({
				template: this.storyItemTemplate,
				model: model,
				characters: this.storyCharacters
			});

			this.$storyItems.append(storyItemView.render().el);

			this.views.push(storyItemView);
		},

		initSortables: function () {
			this.$el.sortable({
				items: '.story-item',
				placeholder: 'activity-placeholder',
				forcePlaceholderSize: true,

				update: _.bind(function (event, ui) {
					this.updateSortingOrder();
				}, this)
			});
		},

		updateSortingOrder: function () {
			_.each(this.views, function (view) {
				view.model.set('order', view.$el.index());
			});
		},

		addAllStoryItems: function () {
			this.collection.each(_.bind(this.addStoryItem, this));
			this.initSortables();
		},

		render: function () {
			this.storyCharacters.fetch({
				success: _.bind(function () {
					this.addAllStoryItems();
				}, this)
			});
		}
	});
})();
