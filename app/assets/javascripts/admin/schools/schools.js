(function () {

	var User = Backbone.Model.extend({});
	var Module = Backbone.Model.extend({});

	var SchoolAdmins = Backbone.Collection.extend({
		model: User,
		url: '/admin/school-admins/free'
	});

	var Modules = Backbone.Collection.extend({
		model: Module,
		url: '/admin/modules/available'
	});

	$(function () {
		var selectedUsers = new SchoolAdmins(
			typeof ADMINISTRATORS !== 'undefined' ? ADMINISTRATORS : null
		);
		var selectedModules = new Modules(
			typeof MODULES !== 'undefined' ? MODULES : null
		);

		var modulesList = new Curotec.ReferenceListView({
			el: '#school-modules',

			collection: selectedModules,

			itemTemplate: JST['admin/modules/module-ref-template'],

			modalView: new Curotec.SelectorModal({
				template: JST['admin/modules/modal-template'],
				rowTemplate: JST['admin/modules/modal-row-template'],

				collection: new Modules(),
				selectedCollection: selectedModules
			})
		});

		modulesList.render();
		
		var usersList = new Curotec.ReferenceListView({
			el: '#school-administrators',

			collection: selectedUsers,

			itemTemplate: JST['admin/schools/user-ref-template'],

			modalView: new Curotec.SelectorModal({
				template: JST['admin/users/modal-template'],
				rowTemplate: JST['admin/users/modal-row-template'],

				collection: new SchoolAdmins(),
				selectedCollection: selectedUsers
			})
		});

		usersList.render();
	});
})();

