(function () {
    window.Curotec || (window.Curotec = {});

    $('.cant_remove .remove_series').fancybox({
        maxWidth: 500,
        maxHeight: 800,
        fitToView: true,
        width: '70%',
        height: '70%',
        autoSize: true,
        closeClick: true,
        openEffect: 'none',
        closeEffect: 'none'
    });

    var ItemView = Backbone.View.extend({
        tagName: 'li',

        events: {
            'click .remove': 'removeItem'
        },
        events: {
            'click .remove_series': 'removeItem'
        },

        initialize: function (options) {
            this.template = options.template;
        },

        render: function () {
            var src = this.template({model: this.model.toJSON()});
            this.$el.html(src);
            return this;
        },

        removeItem: function (event) {
            if ($(event.currentTarget).attr("class") == "remove_series") {
                if ($(event.currentTarget).parents('.cant_remove').length > 0) {

                } else {
                    this.collection.remove(this.model);
                    this.remove();
                }
            } else {
                this.collection.remove(this.model);
                this.remove();
            }
        }
    });

    Curotec.ReferenceListView = Backbone.View.extend({
        events: {
            'click .add-new': 'addNewItem'
        },

        initialize: function (options) {
            this.modalView = options.modalView;

            this.itemTemplate = options.itemTemplate;

            this.$list = this.$('ul');

            this.listenTo(this.collection, 'add', this.addItem);
            this.listenTo(this.collection, 'remove', this.removeItem);
            this.listenTo(this.collection, 'reset', this.addAllItems);
        },

        addAllItems: function () {
            this.collection.each(_.bind(this.addItem, this));
        },

        addItem: function (model) {
            var itemView = new ItemView({
                collection: this.collection,
                model: model,
                template: this.itemTemplate
            });

            this.$list.append(itemView.render().el);
        },

        addNewItem: function (ev) {
            this.modalView.render();

            if ($('.modal-content .modal_choose').length > 0) {
                $('.modal-content .modal_choose').attr("student_id", $(ev.target).attr('student_id'));
                var _student_name = $(ev.target).parents('td').siblings('.student_full_name').find('a').html();
                _student_name = _student_name.replace(",", "");

                $(".reset_series .modal-body h5").html($(".reset_series .modal-body h5").html() + _student_name);
            }

        },

        render: function () {
            this.addAllItems();
        }
    });
})();
