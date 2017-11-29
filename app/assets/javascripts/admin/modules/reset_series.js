(function () {

    var Module = Backbone.Model.extend({});

    var Modules = Backbone.Collection.extend({
        model: Module,
        url: '/admin/classes/available_from_school'
    });

    $(function () {

        var selectedModules = new Modules(
            typeof MODULES !== 'undefined' ? MODULES : null
        );

        var modulesList = new Curotec.ReferenceListView({
            el: '.series_to_reset',

            collection: selectedModules,

            itemTemplate: JST['admin/modules/module-ref-template'],

            modalView: new Curotec.SelectorModal({
                template: JST['admin/modules/modal-template-reset'],
                rowTemplate: JST['admin/modules/modal-row-template'],

                collection: new Modules(),
                selectedCollection: selectedModules
            })
        });

        modulesList.render();

    });


    $('body').on('click', '.modal_choose', function () {
        var series  = {};
        var i=0;
        $('.modal-content .users-table input[type="checkbox"]').each(function () {
            if ($(this).is(':checked')) {
                series[i++]= $(this).val();
            }
        });

        var data = {
            "student_id": $('.modal-content .modal_choose').attr("student_id"),
            "series": series
        }

        $.ajax({
            url: 'reset_serie',
            type: 'POST',
            dataType: 'json',
            data: data
        }).success(function (result) {
            window.location = document.URL;
        }).error(function (err) {
            window.location = document.URL;
        });

    });

})();

