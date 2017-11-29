(function () {

	$('body').on('click', 'a.remove-cartoon' ,function() {
		$(this).parents('div.cartoon-part').remove();
	});

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	var CartoonPicture = Backbone.Model.extend({
	});
	var CartoonPictures = Backbone.Collection.extend({
		model: CartoonPicture,
		comparator: 'order'
	});

	var CartoonPartView = Backbone.View.extend({
		className: 'cartoon-part',

		events: {
			'click .remove-cartoon': 'removeCartoon'
		},
		
		initialize: function (options) {
			this.template = options.template;
		},

		removeCartoon: function () {
			this.model.collection.remove(this.model);
		},

		render: function () {
			this.$el.html(this.template({ picture: this.model.toJSON() }));
			return this;
		}
	});

	ActivityTemplates.CartoonView = Backbone.View.extend({
		events: {
			'click .add-new-item': 'uploadNewComic'
		},
		
		initialize: function () {
			this.views = [];

			if (this.model.has('pictures')) {
				this.collection = new CartoonPictures(this.model.get('pictures'));
			} else {
				this.collection = new CartoonPictures();
			}

			this.model.set('pictures', this.collection);

			this.listenTo(this.collection, 'add', this.addNewCartoon);
			this.listenTo(this.collection, 'reset', this.addAllCartoons);
			this.listenTo(this.collection, 'remove', this.removeCartoon);

			this.cartoonPartTemplate = JST['admin/activity-templates/cartoon-item-template'];

			this.$container = this.$('.cartoon-items-container');
			this.$addNewButton = this.$('.cartoon-part-new');
		},

		addNewCartoon: function (model) {
			var view = new CartoonPartView({
				model: model,
				template: this.cartoonPartTemplate
			});

			$(view.render().el)
				.insertBefore(this.$addNewButton);

			this.views.push(view);
		},

		addAllCartoons: function () {
			this.collection.each(_.bind(this.addNewCartoon, this));
		},

		removeCartoon: function (model) {
			
			var view = this.views.find(function (view) {
				return view.model == model;
			});
			
			view.remove();

			this.views.splice(this.views.indexOf(view), 1);

			this.updateSortingOrder();
		},

		render: function () {
			this.initUploader();
			this.addAllCartoons();
			this.initSorting();
		},

		initSorting: function () {
			this.$el.sortable({
				items: '.cartoon-part:not(.cartoon-part-new)',

				update: _.bind(function () {
					this.updateSortingOrder();
				}, this)
			});
		},

		updateSortingOrder: function () {
			_.each(this.views, function (view) {
				view.model.set('order', view.$el.index());
			});
		},

		initUploader: function () {
			var button = this.$('.add-new-item');

			// Initialize the comic uploader
			new ss.SimpleUpload({
				button: button,
				url: '/admin/activity-templates/cartoon/upload',
				name: 'picture',
				responseType: 'json',
				allowedExtensions: ['jpg', 'jpeg', 'webp', 'png', 'gif', 'tga', 'bmp', 'tif', 'tiff'],

				onComplete: _.bind(function (filename, response) {
					this.createNewPicture(response);
				}, this)
			});
		},

		createNewPicture: function (data) {
			this.collection.add(new CartoonPicture(data));
		}
	});
})();
