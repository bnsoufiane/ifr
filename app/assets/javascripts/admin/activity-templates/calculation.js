(function () {

    var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

    var CalculationItem = Backbone.Model.extend({
        toJSON: function () {
            var data = _.extend({}, this.attributes);

            if (data.employer_cost) {
                data.employer_cost = data.employer_cost.replace('$', '');
            }

            return data;
        }
    });

    var CalculationItems = Backbone.Collection.extend({model: CalculationItem});

    var CalculationItemView = Backbone.View.extend({
        className: 'form-group',

        events: {
            'click .remove-option': 'removeItem'
        },

        initialize: function (options) {
            this.template = options.template;
        },

        render: function () {
            this.$el.html(this.template());

            this.bindings = rivets.bind(this.$el, {item: this.model});

            this.renderMaskedInput();

            return this;
        },

        renderMaskedInput: function () {
            this.$('input[name=employer-cost]')
                .inputmask({
                    'alias': 'numeric',
                    'groupSeparator': ',',
                    'autoGroup': true,
                    'digits': 2,
                    'digitsOptional': false,
                    'prefix': '$',
                    'placeholder': '0'
                });
        },

        removeItem: function () {
            this.model.collection.remove(this.model);
            this.remove();
        },

        remove: function () {
            this.bindings.unbind();
            Backbone.View.prototype.remove.call(this);
        }
    });

    var CalculationFooter = Backbone.Model.extend({
        toJSON: function () {
            var data = _.extend({}, this.attributes);
            return data;
        }
    });

    var CalculationFooters = Backbone.Collection.extend({model: CalculationFooter});

    var CalculationFooterView = Backbone.View.extend({
        className: 'form-group',

        events: {
            'click .remove-footer': 'removeFooter'
        },

        initialize: function (options) {
            this.template = options.template;
        },

        render: function () {
            this.$el.html(this.template());

            this.bindings = rivets.bind(this.$el, {footer: this.model});

            return this;
        },

        removeFooter: function () {
            this.model.collection.remove(this.model);
            this.remove();
        },

        remove: function () {
            this.bindings.unbind();
            Backbone.View.prototype.remove.call(this);
        }
    });

    ActivityTemplates.CalculationView = Backbone.View.extend({
        events: {
            'click .add-new-item': 'createItem',
            'click .add-new-footer': 'createFooter'
        },

        initialize: function () {
            this.itemTemplate = Handlebars.compile(this.$('#calculation-item-template').html());

            if (this.model.has('items')) {
                this.collectionItems = new CalculationItems(this.model.get('items'));
            } else {
                this.collectionItems = new CalculationItems();
                this.createItem();
            }

            this.model.set('items', this.collectionItems);

            this.listenTo(this.collectionItems, 'add', this.renderItem);

            this.$items = this.$('.items');


            this.footerTemplate = Handlebars.compile(this.$('#calculation-footer-template').html());

            if (this.model.has('footers')) {
                this.collectionFooters = new CalculationFooters(this.model.get('footers'));
            } else {
                this.collectionFooters = new CalculationFooters();
                this.createFooter();
            }

            this.model.set('footers', this.collectionFooters);

            this.listenTo(this.collectionFooters, 'add', this.renderFooter);

            this.$footers = this.$('.footers');
        },

        createItem: function () {
            this.collectionItems.add(new CalculationItem());
        },
        createFooter: function () {
            this.collectionFooters.add(new CalculationFooter());
        },

        renderItem: function (model) {
            var view = new CalculationItemView({
                template: this.itemTemplate,
                model: model
            });

            this.$items.append(view.render().el);
        },
        renderFooter: function (model) {
            var view = new CalculationFooterView({
                template: this.footerTemplate,
                model: model
            });

            this.$footers.append(view.render().el);
        },

        addAllItems: function () {
            this.collectionItems.each(_.bind(this.renderItem, this));
        },
        addAllFooters: function () {
            this.collectionFooters.each(_.bind(this.renderFooter, this));
        },

        render: function () {
            this.addAllItems();
            this.addAllFooters();

            this.bindings = rivets.bind(this.$('form:first'), {calculation: this.model});
        },

        remove: function () {
            this.bindings.unbind();
            Backbone.View.prototype.remove.call(this);
        }
    });
})();
