/* Handles users CRUD */

(function () {
    var Group = Backbone.Model.extend({});
    var Groups = Backbone.Collection.extend({ model: Group });

    var GroupView = Backbone.View.extend({
        template: null,

        className: 'row',

        events: {
            'click .remove-option': 'removeGroup'
        },

		initialize: function () {
            this.template = JST['admin/users/group-template'];
		},

        removeGroup: function () {
            this.remove();
            this.model.collection.remove(this.model);
        },

        render: function () {
			if(typeof AVAILABLE_GROUPS == 'undefined') {
				AVAILABLE_GROUPS = null;
			}
		
            this.$el.html(this.template({
                groups: AVAILABLE_GROUPS
            }));

            if (this.model.id) {
                this.$('option[value=' + this.model.id + ']')
                    .prop('selected', true);
            }

            return this;
        }
    });

    var UserGroupsListView = Backbone.View.extend({
        el: '#user-groups-list',

        events: {
            'click .add-option': 'addGroup'
        },

        initialize: function () {
            this.groups = new Groups();

            // Check if we have a list of groups for the current user we're editing.
            if (typeof USER_GROUPS !== 'undefined') {
                this.groups.reset(USER_GROUPS);
            } else {
                this.addGroup();
            }

            this.listenTo(this.groups, 'add', this.renderGroup);
            this.listenTo(this.groups, 'reset', this.render);
            this.listenTo(this.groups, 'remove', this.removeGroup);
        },

        addGroup: function () {
            this.groups.add(new Group());
        },

        render: function () {
            this.groups.forEach(_.bind(this.renderGroup, this));
        },

        renderGroup: function (group) {
            var view = new GroupView({ model: group });

            this.$el.append(view.render().el);
        }
    });

    $(function () {
        var view = new UserGroupsListView();
        view.render();
    });
})();

