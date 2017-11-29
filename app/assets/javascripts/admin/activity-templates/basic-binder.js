(function () {

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	/**
	 * A view just to bind Rivets handlers to a Backbone model.
	 * See http://rivetsjs.com/docs/guide/ for reference.
	 */
	ActivityTemplates.BasicBinder = Backbone.View.extend({
		render: function () {
			rivets.bind(this.$el, { model: this.model });
		}
	});

})();
