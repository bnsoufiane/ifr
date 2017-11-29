(function () {
    var User = Backbone.Model.extend({});
    var Module = Backbone.Model.extend({});
    var _new_students_count = $('.add_new_student_table tr').length - 1;
    var _form_modified = 0;

    var Students = Backbone.Collection.extend({
        model: User,
        url: '/admin/students/from-my-school'
    });

    $('body').on('click', '#class-students ul li .remove', function (e) {
        $(this).parent().remove();
    });

    $(function () {
        var selectedUsers = new Students(
            typeof STUDENTS !== 'undefined' ? STUDENTS : null
        );

        var studentsList = new Curotec.ReferenceListView({
            el: '#class-students',
            collection: selectedUsers,
            itemTemplate: JST['admin/classes/user-ref-template'],
            modalView: new Curotec.SelectorModal({
                template: JST['admin/users/modal-template'],
                rowTemplate: JST['admin/users/modal-row-template'],
                collection: new Students(),
                selectedCollection: selectedUsers
            })
        });
        studentsList.render();
        renderDropZones();
    });

    function renderDropZones () {
        var self = this;

        $.each(this.$('.dropzone'), function (num, el) {
            var button = $(el).find('.btn');
            var file = $(el).find('input[type=hidden]');

            new ss.SimpleUpload({
                button: button,
                url: '../../classes/upload',
                name: 'upload',
                responseType: 'json',
                allowedExtensions: $(el).data('uploadExtensions').split(','),
                onSubmit: function () {
                    button.attr('disabled', 'disabled');
                },
                onComplete: function (filename, response) {
                    button.removeAttr('disabled');

                    // Store a filename of the uploaded file.
                    file.val(response.filename).change();

                    var data = {
                        "file": response.filename
                    }

                    $.ajax({
                        url: '../../classes/parse_file',
                        type: 'POST',
                        dataType: 'json',
                        data: data
                    }).success(function (response) {
                        var atLeastOneRowImported = false;
                        for(var i=0; i<response.length; i++){
                            var res = response[i].split(",");
                            if(res.length==4){
                                atLeastOneRowImported = true;
                                var row = '<tr>'+
                                    '<td>'+res[0]+'</td>'+
                                    '<td>'+res[1]+'</td>'+
                                    '<td>'+res[2]+'</td>'+
                                    '<td>'+res[3]+'</td>'+
                                    '</tr>';

                                $('#imported-students table').append($(row));
                            }
                        }

                        if(atLeastOneRowImported){
                            $('#imported-students').css('display','block');
                        }

                    }).error(function (err) {
                    });
                },
                onError: function (e) {
                    button.removeAttr('disabled');
                    alert('Upload has failed. Try again, please.');
                }
            });

        });
    }

    if ($('table#classes').length != 0) {
        $('label[for="students"]').hide();
        $("#class-students ul").css("visibility", "hidden");


        $('table#classes tbody tr .remove_student').on('click', function (e) {
            delete_student($(this));
        });

        var $div = $("#class-students ul");
        var html = $div.html();
        var checking = setInterval(function () {
            var newhtml = $div.html();
            if (html != newhtml) {
                list_changed();
                html = newhtml;
            }
        }, 100);

        function list_changed() {
            if ($("#class-students ul li").length != 0) {
                $('#class-students ul li').each(function () {
                    var full_name = $(this).find("span").html();
                    var _res = full_name.split(", ");
                    full_name = _res[1] + " " + _res[0];
                    var student_id = $(this).find('input[type="hidden"]').val();
                    var students_id = '';
                    $('input[type="hidden"][name="students_id"]').each(function () {
                        students_id += $(this).val() + ',';
                    });

                    if (students_id.indexOf(student_id) < 0) {
                        var _even = ($('table#classes tbody tr').length % 2) ? 'odd' : 'even';
                        $('<tr role="row" class="' + _even + '"><td class="sorting_1">'
                        + full_name + '<a class="remove_student" href="javascript:void(0);" style="color: #b94a48;">'
                        + '<i class="fa fa-minus-circle"></i>'
                        + '</a><input type="hidden" value="' + student_id + '" name="students_id"></td></tr>')
                            .appendTo($('table#classes tbody'));

                        students_id += student_id;
                        var res = window.location.href.split("/");
                        var class_id = res[res.length - 2];
                        //save_students(class_id, students_id);
                    }

                    $(this).find(".remove").trigger("click");
                });
                $('table#classes tbody tr .remove_student').on('click', function (e) {
                    delete_student($(this));
                });

                $('.dataTables_empty').parent().remove();
            }
        }

        function save_students(class_id, students_id) {

            var data = {
                "students_id": students_id
            }

            $.ajax({
                url: '../../classes/' + class_id + '/save_students',
                type: 'POST',
                dataType: 'json',
                data: data
            }).success(function (result) {
            }).error(function (err) {
            });
        }

        function delete_student(_obj) {
            var r = confirm("Important:  Deleting a student will remove all student information from your roster!\r\nAre you sure you want to proceed?");
            if (!r) {
                return false;
            } else {
                var student_id = _obj.parent().find('input[type="hidden"]').val();

                /*var students_id = '';
                $('input[type="hidden"][name="students_id"]').each(function () {
                    students_id += $(this).val() + ',';
                });

                students_id = students_id.replace(student_id + ",", "");*/

                var res = window.location.href.split("/");
                var class_id = res[res.length - 2];

                var data = {
                    "student_id": student_id
                }

                $.ajax({
                    url: '../../classes/' + class_id + '/delete_student',
                    type: 'POST',
                    dataType: 'json',
                    data: data
                }).success(function (result) {
                }).error(function (err) {
                });

                if (typeof mytable != 'undefined') {
                    mytable.row( _obj.parents('tr') ).remove().draw();
                }

                //save_students(class_id, students_id);

                _obj.parents('tr').remove();
            }
        }

    }
    ;

    $('.add_new_student').on('click', function (e) {
        if ($('.add_new_student_table').css("display") == "none") {
            $('.add_new_student_table').show();
            _new_students_count = 1;


            $('<tr><td><input id="last_name_' + _new_students_count + '" class="form-control" type="text" name="last_name_' + _new_students_count + '" ></td>' +
            '<td><input id="first_name_' + _new_students_count + '" class="form-control" type="text" name="first_name_' + _new_students_count + '" ></td>' +
            '<td><input id="username_' + _new_students_count + '" class="form-control" type="text" name="username_' + _new_students_count + '" ></td>' +
                '<td><input id="email_' + _new_students_count + '" class="form-control" type="text" name="email_' + _new_students_count + '" ></td>' +
            '<td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>' +
            '</tr>').appendTo($('.add_new_student_table'));
        } else {
            _new_students_count++;
            $('<tr><td><input id="last_name_' + _new_students_count + '" class="form-control" type="text" name="last_name_' + _new_students_count + '" ></td>' +
            '<td><input id="first_name_' + _new_students_count + '" class="form-control" type="text" name="first_name_' + _new_students_count + '" ></td>' +
            '<td><input id="username_' + _new_students_count + '" class="form-control" type="text" name="username_' + _new_students_count + '" ></td>' +
                '<td><input id="email_' + _new_students_count + '" class="form-control" type="text" name="email_' + _new_students_count + '" ></td>' +
            '<td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>' +
            '</tr>').appendTo($('.add_new_student_table'));
        }

    });

    $('body').on('click', '.remove_row', function (e) {
        var tr = $(this).parents('tr');
        tr.css("background-color", "#b94a48");
        tr.fadeOut(400, function () {
            tr.remove();
            if ($(".add_new_student_table tr").length < 2) {
                $('.add_new_student_table').hide();
            }
        });

    });

})();


(function () {

    var User = Backbone.Model.extend({});
    var Module = Backbone.Model.extend({});

    var SchoolAdmins = Backbone.Collection.extend({
        model: User,
        url: '/admin/school-admins/free'
    });

    var Modules = Backbone.Collection.extend({
        model: Module,
        url: '/admin/classes/available_from_school'
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


    $('form *').change(function(){
        _form_modified=1;
    });
    $('body').on('click', '.add_new_student,.import_students, .remove_row', function (e) {
        _form_modified = 1;
    });
    $('body').on('click', "a[name='cancel'], button[type='submit']", function (e) {
        _form_modified = 0;
    });

    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (_form_modified == 1) {
            return "New information not saved. Do you wish to leave the page?";
        }
    }

    $('.import_students').on('click', function (e) {
        $.ajax({
            url: '../../classes/'+$(this).attr('class_id')+'/import_students',
            method: 'GET',
            //data: data_preview,
            dataType: 'html'
        }).done(function (response) {
            var modalContent = JST['admin/classes/import-students-modal']();
            var iframe = $(modalContent)
                .appendTo(document.body)
                .modal()
                .find('iframe')[0];

            iframe.contentWindow.contents = response;
            iframe.src = 'javascript:window["contents"]';

        });
    });


    $('.add_imported_students').on('click', function (e) {
        var filename = $('.dropzone').find('input[type=hidden]').val();

        var data = {
            "file": filename,
            "class": $(this).attr('class_id')
        }

        $.ajax({
            url: '../../classes/store_imported_students',
            type: 'POST',
            dataType: 'json',
            data: data
        }).success(function (response) {
            if(response.ok !== true){
                var message = 'These students are duplicated: \r\r';

                var duplicatedStudents = JSON.parse(response.ok);
                for(var i = 0; i<duplicatedStudents.length; i++){
                    message+=(duplicatedStudents[i]['last_name']+', '+duplicatedStudents[i]['first_name']+ ', '+ duplicatedStudents[i]['username']+'\r\n');
                }

                alert(message);

            }else{
            alert('Students have been successfully saved.');
            }

            window.parent.$('iframe').each(function () {
                $(this).parents('.modal-content').find('button.close').trigger('click');
                window.parent.location.reload();
            });

        }).error(function (err) {
            alert('An error has occurred.');
        });

    });

    $('.cancel_importing_students').on('click', function (e) {
        window.parent.$('iframe').each(function () {
            $(this).parents('.modal-content').find('button.close').trigger('click');
        });
    });

})();

