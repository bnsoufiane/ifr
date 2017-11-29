/* Lessons front-end handling */

var Curotec = window.Curotec || (window.Curotec = {});
var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});
var _activities_to_remove = [];
var _sub_activities_to_remove = [];
var lessonView = {};

(function () {

    var ACTIVITY_COUNTER = 0;

    Curotec.Model = Backbone.Model.extend({
        // Make toJSON function recursive.
        toJSON: function () {
            var json = _.clone(this.attributes);
            _.each(json, function (value, name) {
                if (value === null || typeof value === 'undefined') {
                    return;
                }
                _.isFunction(value.toJSON) && (json[name] = value.toJSON());
            });
            return json;
        }
    });

    var Lesson = Curotec.Model.extend({
        urlRoot: '/admin/lessons',
        defaults: {
            activities: null
        },
        parse: function (data) {
            data.activities = new Activities(data.activities, {parse: true});
            return data;
        }
    });

    var Activity = Curotec.Model.extend({
        defaults: {
            title: '',
            feedback: ''
        },
        parse: function (data) {
            data.template_type = data.template_type.replace('ActivityTemplates\\', '');

            if (data.sub_activities !== undefined) {
                for (var i = 0; i < data.sub_activities.length; i++) {
                    data.sub_activities[i].template = new Curotec.Model(data.sub_activities[i].template);
                }
            }

            data.template = new Curotec.Model(data.template);

            return data;
        }
    });

    var Activities = Backbone.Collection.extend({
        model: Activity,
        comparator: 'order'
    });

    // A view for a single activity.
    var ActivityView = Backbone.View.extend({
        className: 'panel panel-default',
        template: (($('#add_assessment_page').length < 1) ? JST['admin/lessons/activity-template'] : JST['admin/lessons/activity-template_assessment']),
        events: {
            'click .panel-title': 'toggleAccordion',
            'change select[name="activity-type"]': 'changeActivityType',
            'change select[name="sub-activity-type"]': 'changeSubActivityType',
            'click .show-preview': 'showPreview'
        },
        initialize: function (options) {
            this.id = options.model.id;

            this.lesson = options.lesson;

            this.imageTemplate = JST['admin/lessons/upload-image-preview'];
            this.fileTemplate = JST['admin/lessons/upload-file-preview'];
        },
        showPreview: function (ev) {
            var data2 = this.model.toJSON();

            if ((data2.template_type == "QnA" || data2.template_type == "Story")) {
                var i = 0;
                for (i = 0; i < data2.template.items.length; i++) {
                    data2.template.items[i].text = data2.template.items[i].text.replace(new RegExp('\n', 'g'), '<br>');
                }
            }

            if ((data2.template_type == "QnA" || data2.template_type == "Story") && (data2.template.items[0].character_id === undefined)) {
                alert('Please select at least one character to generate a preview.')
                return false;
            }

            data2.series_id = this.lesson.get('series_id');

            if (data2.template_type == "Wysiwyg") {
                data2.template.content = '<html>' + $(ev.currentTarget).parents(".panel-body").find('.wysiwyg-editor-container div.mce-edit-area.mce-container iframe').contents().find('html').html() + '</html>';
            }

            var data_preview = jQuery.extend(true, {}, data2);

            if (data2.sub_activities !== undefined) {
                for (var i = 0; i < data2.sub_activities.length; i++) {
                    if (data_preview.sub_activities[i].model !== undefined) {
                        data_preview.sub_activities[i] = data_preview.sub_activities[i].model.toJSON();
                        if (data_preview.sub_activities[i].template_type == "Wysiwyg") {
                            data_preview.sub_activities[i].template.content = '<html>' + $(ev.currentTarget).parents(".panel-body").find('.wysiwyg-editor-container div.mce-edit-area.mce-container iframe').contents().find('html').html() + '</html>';
                        }
                    } else if (data_preview.sub_activities[i].template.attributes !== undefined) {
                        data_preview.sub_activities[i].template = data_preview.sub_activities[i].template.toJSON();
                        if (data_preview.sub_activities[i].template_type == "Wysiwyg") {
                            data_preview.sub_activities[i].template.content = '<html>' + $(ev.currentTarget).parents(".panel-body").find('.wysiwyg-editor-container div.mce-edit-area.mce-container iframe').contents().find('html').html() + '</html>';
                        }
                    }
                }
            }

            // Remove all "id"s from the data
            (function removeIds(data_preview) {
                _.each(data_preview, function (d, key) {
                    if (_.isObject(d) && key != "sub_activities") {
                        removeIds(d);
                    } else if (key == 'id') {
                        delete data_preview.id;
                    }
                });
            })(data_preview);

            var _select_subactvities_count = 0;
            if (data2.sub_activities !== undefined) {
                for (var i = 0; i < data2.sub_activities.length; i++) {

                    if (_sub_activities_to_remove.indexOf(data_preview.sub_activities[i].template.unique_id) != -1) {
                        data2.sub_activities.splice(i, 1);
                        data_preview.sub_activities.splice(i, 1);
                        i--;
                        continue;
                    }
                }
            }

            $.ajax({
                url: '/admin/lessons/preview',
                method: 'POST',
                data: data_preview,
                dataType: 'html'
            }).done(function (response) {
                var modalContent = JST['admin/lessons/activity-preview-modal']();
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    .modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });

            return false;
        },
        render: function () {

            var data = _.extend(this.model.toJSON(), {
                id: this.id,
                activity_templates: ACTIVITY_TEMPLATES
            });

            if (($('#add_assessment_page').length < 1) || data.template_type == "Assessment" ||
                (($('#add_assessment_page').length > 0) && ($('#assessment_already_created').val() != "1"))) {
                this.$el.html(this.template(data));

                if (!this.model.has('template_type')) {
                    this.model.set('template_type', this.$('select[name=activity-type] > option:first').val());
                }

                rivets.bind(this.$el, {model: this.model});
                this.changeActivityType();

                this.renderDropZones();

                this.initAccordion();
            }

            return this;
        },
        renderSubActivity: function (ev) {

            var data = _.extend(this.model.toJSON(), {
                id: this.id,
                activity_templates: ACTIVITY_TEMPLATES
            });

            if (($('#add_assessment_page').length < 1) || data.template_type == "Assessment" ||
                (($('#add_assessment_page').length > 0) && ($('#assessment_already_created').val() != "1"))) {
                //this.$el.html(this.template(data));

                if (!this.model.has('template_type')) {
                    this.model.set('template_type', this.$('select[name=activity-type] > option:first').val());
                }

                rivets.bind(this.$el, {model: this.model});
                this.changeSubActivityType(ev);

                //this.renderDropZones();

                //this.initAccordion();
            }

            return this;
        },
        initAccordion: function () {
            this.$('.panel-collapse').collapse({
                toggle: false,
                parent: '#activities'
            });
        },
        showAccordion: function () {
            this.$('.panel-collapse').collapse('show');
        },
        toggleAccordion: function () {
            this.$('.panel-collapse').collapse('toggle');
        },
        /**
         * @private
         */
        renderFilePreview: function (el, filename, url) {
            el = $(el);

            var fileData = {
                filename: filename,
                url: url
            };
            var extension = filename.substr(-4).toLowerCase();

            if (['.png', '.jpg', 'jpeg', '.gif', 'webp'].indexOf(extension) > -1) {
                // Display a preview for the image.
                el.slideUp('fast', _.bind(function () {
                    el.html(this.imageTemplate(fileData))
                        .slideDown('fast');
                }, this));
            } else {
                // Display a link to the generic file.
                el.slideUp('fast', _.bind(function () {
                    el.html(this.fileTemplate(fileData))
                        .slideDown('fast');
                }, this));
            }
        },
        /**
         * Renders file fields.
         */
        renderDropZones: function () {
            var self = this;

            $.each(this.$('.dropzone'), function (num, el) {
                var button = $(el).find('.btn');
                var preview = $(el).find('.preview');
                var file = $(el).find('input[type=hidden]');

                new ss.SimpleUpload({
                    button: button,
                    url: '/admin/lessons/upload',
                    name: 'upload',
                    responseType: 'json',
                    allowedExtensions: $(el).data('uploadExtensions').split(','),
                    onSubmit: function () {
                        button.attr('disabled', 'disabled');
                    },
                    onComplete: function (filename, response) {
                        button.removeAttr('disabled');

                        self.renderFilePreview(preview, response.filename, response.url);

                        // Store a filename of the uploaded file.
                        file.val(response.filename).change();
                    },
                    onError: function () {
                        button.removeAttr('disabled');
                        alert('Upload has failed. Try again, please.');
                    }
                });

                if (file.val().length > 0) {
                    self.renderFilePreview(preview, file.val(), '/uploads/' + file.val());
                }
            });
        },
        /**
         * @private
         */
        changeActivityType: function (ev_button) {
            if ($('#add_assessment_page').length > 0) {
                var template = 'Assessment';
            } else {
                var template = this.model.get('template_type');
            }
            var placeholder = this.$('.activity-template').first();

            placeholder.addClass('loading');
            if (ev_button === undefined) {
                this.renderActivityTemplate(template, placeholder)
                    .always(function () {
                        // Remove loading overlay
                        placeholder.removeClass('loading');
                    });
            } else {
                this.renderActivityTemplate(template, placeholder, false, ev_button)
                    .always(function () {
                        // Remove loading overlay
                        placeholder.removeClass('loading');
                    });
            }

        },
        changeSubActivityType: function (ev_button, ev) {

            var template = $(ev_button.currentTarget).val();
            template = (template == "") ? "FreeFormAnswer" : template;
            $(ev_button.currentTarget).parents(".form-group").siblings(".sub_activities_list").find(".top_border_dashed").first().clone()
                .appendTo($(ev_button.currentTarget).parents(".form-group").siblings(".sub_activities_list")).find("select").attr("name", "sub-activity-type")
                .parents(".sub_activities_list").append('<div class="activity-template"></div>');
            //var placeholder = $(ev_button.currentTarget).parents(".top_border_dashed").next();

            if ($(ev_button.currentTarget).val() == "") {
                var placeholder = $(ev_button.currentTarget).parents(".form-group").siblings(".sub_activities_list").find(".activity-template").last();
            } else {
                var placeholder = $(ev_button.currentTarget).parents(".top_border_dashed").next();
            }

            placeholder.addClass('loading');
            this.renderActivityTemplate(template, placeholder, true, ev_button)
                .always(function () {
                    // Remove loading overlay
                    placeholder.removeClass('loading');
                });
        },
        /**
         * @private
         */
        renderActivityTemplate: function (template, placeholder, is_sub_activity, ev_button) {
            var model = this.model;
            var dfd = new $.Deferred();
            var renderSubActivityTemplate = function (sub_activity) {
                var template = sub_activity.template_type.replace('ActivityTemplates\\', '');

                $('div[activity_id="' + model.attributes.id + '"]').find('.add_sub_activity').parents(".form-group").siblings(".sub_activities_list").find(".top_border_dashed").first().clone()
                    .appendTo($('div[activity_id="' + model.attributes.id + '"]').find('.add_sub_activity').parents(".form-group").siblings(".sub_activities_list")).find("select").attr("name", "sub-activity-type").val(template)
                    .parents(".sub_activities_list").append('<div class="activity-template"></div>');
                //var placeholder = $(ev_button.currentTarget).parents(".top_border_dashed").next();

                var placeholder = $('div[activity_id="' + model.attributes.id + '"]').find('.add_sub_activity').parents(".form-group").siblings(".sub_activities_list").find(".activity-template").last();


                var dfd = new $.Deferred();

                $.get('/admin/activity-templates/render/' + template)
                    .fail(function () {
                        dfd.reject();
                    })
                    .done(function (html) {
                        var $el = $(html);
                        var jsHandlerView = $el.attr('data-js-handler-view');

                        if (jsHandlerView) {
                            // Load an extra JS handler for the activity template.

                            if (sub_activity.template === undefined) {
                                // Create a new template model if we're adding a new lesson.
                                sub_activity.template = new Curotec.Model();
                            }

                            var _model_obj = sub_activity.template;
                            if (!ActivityTemplates[jsHandlerView]) {
                                throw new Error('Unknown handler: ' + jsHandlerView);
                            }


                            if (jsHandlerView == "SelectView") {
                                //$el.find("textarea.select_descr").val(_model_obj.get('description'));
                            }
                            placeholder.html($el);

                            var unique_id = guid();
                            _model_obj.unique_id = unique_id;
                            _model_obj.attributes.unique_id = unique_id;
                            placeholder.prev().attr("unique_id", unique_id);

                            var view = new ActivityTemplates[jsHandlerView]({
                                el: $el,
                                model: _model_obj
                            });

                            view.render();
                            dfd.resolve();

                        } else {
                            placeholder.html($el);
                            dfd.resolve();
                        }

                        placeholder.prev().find('label').prepend($('<a href="javascript:void(0);" class="remove-sub-activity" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Remove the sub activity">		<i class="fa fa-minus-square"></i>	</a>'));

                        if ($('#add_assessment_page').length < 1) {
                            if ($('div[data-js-handler-view="AssessmentView"]').length != 0) {
                                $('div[data-js-handler-view="AssessmentView"]').parents('.panel-default').hide();
                                $('a.show_assessment').show();
                            }
                        }

                    });

                return dfd;
                //*/

            }

            $.get('/admin/activity-templates/render/' + template)
                .fail(function () {
                    dfd.reject();
                })
                .done(function (html) {
                    var $el = $(html);
                    var jsHandlerView = $el.attr('data-js-handler-view');

                    if (jsHandlerView) {
                        // Load an extra JS handler for the activity template.

                        if (!model.has('template')) {
                            // Create a new template model if we're adding a new lesson.
                            model.set('template', new Curotec.Model());
                        }

                        if (is_sub_activity && model.attributes.sub_activities !== undefined) {
                            var index = 0;
                            $('div[activity_id="' + model.attributes.id + '"] select[name="sub-activity-type"]').each(function (select_index, domObject) {
                                if ($(domObject)[0] === $(ev_button.currentTarget)[0]) {
                                    index = select_index;
                                }
                            });

                            if (model.attributes.sub_activities[index].id === undefined) {
                                model.attributes.sub_activities[index].model.attributes.template = new Curotec.Model();
                                var _model_obj = model.attributes.sub_activities[index].model.attributes.template;
                                model.attributes.sub_activities[index].model.attributes.template_type = template;
                            } else {
                                var _model_obj = model.attributes.sub_activities[index].template;
                                model.attributes.sub_activities[index].template_type = template;
                            }
                        } else {
                            var _model_obj = model.get('template');
                        }

                        if (!ActivityTemplates[jsHandlerView]) {
                            throw new Error('Unknown handler: ' + jsHandlerView);
                        }

                        placeholder.html($el);
                        if (jsHandlerView == "SelectView") {
                            //placeholder.find("textarea.select_descr").val(_model_obj.get('description'))
                        }
                        var view = new ActivityTemplates[jsHandlerView]({
                            el: $el,
                            model: _model_obj
                        });

                        //if(!is_sub_activity){
                        view.render();
                        //}

                        if (is_sub_activity !== false) {
                            if (model.has('sub_activities') && !is_sub_activity) {
                                for (var i = 0; i < model.attributes.sub_activities.length; i++) {
                                    renderSubActivityTemplate(model.attributes.sub_activities[i]);
                                }
                            }
                        }

                        dfd.resolve();
                        if (is_sub_activity !== true) {
                            if (jsHandlerView == "SelectView") {
                                var _title = model.get('title');
                                $('h4.panel-title a span[rv-text="model:title"]').each(function () {
                                    if ($(this).html() == _title) {
                                        //$(this).parents('div.panel-default').find('div.panel-collapse .select_descr').val(_model_obj.get('description'));
                                    }
                                });
                            }
                        }

                    } else {
                        placeholder.html($el);
                        dfd.resolve();
                    }

                    if (is_sub_activity) {
                        var unique_id = guid();
                        _model_obj.unique_id = unique_id;
                        _model_obj.attributes.unique_id = unique_id;
                        placeholder.prev().attr("unique_id", unique_id);
                        if ((placeholder.prev().find('label .remove-sub-activity').length == 0)) {
                            placeholder.prev().find('label').prepend($('<a href="javascript:void(0);" class="remove-sub-activity" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Remove the sub activity">		<i class="fa fa-minus-square"></i>	</a>'));
                        }
                    }

                    if ($('#add_assessment_page').length < 1) {
                        if ($('div[data-js-handler-view="AssessmentView"]').length != 0) {
                            $('div[data-js-handler-view="AssessmentView"]').parents('.panel-default').hide();
                            $('a.show_assessment').show();
                        }
                    }

                    var _txt_area = placeholder.parents('form').find('.feedback-editor-container > textarea');
                    //$(_txt_area).val($(_txt_area).parent().siblings("input").val());

                    $(_txt_area).tinymce({
                        theme_url: '/assets/lib/tinymce/themes/modern/theme.min.js',
                        skin_url: '/assets/lib/tinymce/skins/lightgray',

                        height: 200,

                        external_plugins: {
                            'input': '/assets/admin/activity-templates/wysiwyg-input-plugin.js'
                        },
                        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | input",
                        setup: function (editor) {
                            editor.on('keyup change', function (e) {
                                $(_txt_area).parent().siblings("input").val($(_txt_area).parents(".panel-body").find('.feedback-editor-container div.mce-edit-area.mce-container iframe').contents().find('html body').html());
                                $(_txt_area).parent().siblings("input").trigger("change");
                            });
                        },
                        init_instance_callback : function(editor) {
                            $(_txt_area).parents(".panel-body").find('.feedback-editor-container div.mce-edit-area.mce-container iframe').contents().find('html body').html($(_txt_area).parent().siblings("input").val());
                        }
                    });

                });

            return dfd;
        }
    });

    // A view for a list of activities.
    var ActivitiesView = Backbone.View.extend({
        el: '#activities',
        initialize: function (options) {
            _.bindAll(this, 'addOne');

            this.lesson = options.lesson;

            this.activityViews = [];

            this.listenTo(this.collection, 'add', this.addOne);
            this.listenTo(this.collection, 'reset', this.addAll);
            this.listenTo(this, 'create', this.showAccordion);
            this.listenTo(this, 'create', this.refreshSortables);
        },
        render: function () {
            this.initSortables();
            this.addAll();
        },
        refreshSortables: function () {
            this.$el.sortable('refresh');
        },
        showAccordion: function (model) {
            _.each(this.activityViews, function (view) {
                if (view.model === model) {
                    // Show required collapsable group
                    view.showAccordion();
                    $('html, body').animate({
                        scrollTop: view.$el.offset().top
                    }, 300);
                    return false;
                }
            });
        },
        initSortables: function () {
            this.$el.sortable({
                handle: '.panel-heading',
                items: '.panel',
                placeholder: 'activity-placeholder',
                forcePlaceholderSize: true,
                update: _.bind(function () {
                    this.updateOrder();
                }, this),
                start: _.bind(function () {
                    $('.feedback-editor-container > textarea').each(function (item) {
                        $(this).val($(this).parent().siblings("input").val());
                        try {
                        $(this).tinymce().remove();
                        }catch(err) {}
                    });
                    $('.wysiwyg-editor-container > textarea').each(function (item) {
                        $(this).val($(this).parents(".panel-body").find('.wysiwyg-editor-container div.mce-edit-area.mce-container iframe').contents().find('html body').html());
                        try {
                        $(this).tinymce().remove();
                        }catch(err) {}
                    });
                }, this),
                stop: _.bind(function () {
                    //*
                    setTimeout(function () {
                        $('.feedback-editor-container > textarea').each(function (item) {

                            $(this).tinymce({
                                theme_url: '/assets/lib/tinymce/themes/modern/theme.min.js',
                                skin_url: '/assets/lib/tinymce/skins/lightgray',

                                height: 200,

                                external_plugins: {
                                    'input': '/assets/admin/activity-templates/wysiwyg-input-plugin.js'
                                },
                                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | input",
                                setup: function (editor) {
                                    editor.on('keyup change', function (e) {
                                        $(this).parent().siblings("input").val($(this).parents(".panel-body").find('.feedback-editor-container div.mce-edit-area.mce-container iframe').contents().find('html body').html());
                                        $(this).parent().siblings("input").trigger("change");
                                    });
                                }
                            });

                        });
                        $('.wysiwyg-editor-container > textarea').each(function (item) {
                            $(this).tinymce({
                                theme_url: '/assets/lib/tinymce/themes/modern/theme.min.js',
                                skin_url: '/assets/lib/tinymce/skins/lightgray',

                                height: 400,

                                external_plugins: {
                                    'input': '/assets/admin/activity-templates/wysiwyg-input-plugin.js'
                                },
                                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | input",
                            });

                        });//*/
                    }, 800);
                }, this)
            });
        },
        addAll: function () {
            this.collection.each(this.addOne);
            this.$el.sortable('refresh');
        },
        addOne: function (model) {
            var view = new ActivityView({
                model: model,
                lesson: this.lesson
            });


            if (model.is_sub_activity_flag) {
                view.renderSubActivity(model.ev);
                //this.$el.append(view.renderSubActivity(model.ev).el);

                var _mother_activity_title = $(model.ev.currentTarget).parents(".panel-collapse").siblings(".panel-heading").find('span[rv-text="model:title"]').html();
                for (var i = 0; i < this.activityViews.length; i++) {
                    if (_mother_activity_title == this.activityViews[i].model.attributes.title) {
                        if (this.activityViews[i].model.attributes.sub_activities === undefined) {
                            this.activityViews[i].model.attributes.sub_activities = [];
                        }
                        view.lesson = null;
                        this.activityViews[i].model.attributes.sub_activities.push(view);
                        break;
                    }
                }

            } else {
                this.$el.append(view.render().el);
                this.activityViews.push(view);
            }
        },
        createActivity: function () {
            ACTIVITY_COUNTER += 1;

            var model = new Activity({title: 'Activity #' + ACTIVITY_COUNTER});
            this.collection.add(model);

            this.trigger('create', model);
        },
        createSubActivity: function (ev) {
            //ACTIVITY_COUNTER += 1;

            var model = new Activity({title: 'SubActivity'});
            model.attributes.template_type = "FreeFormAnswer";

            model.is_sub_activity_flag = true;
            model.ev = ev;
            this.collection.add(model);

            //this.trigger('create', model);
        },
        // Updates items numerical order values.
        updateOrder: function () {
            _.each(this.activityViews, function (view) {
                view.model.set('order', view.$el.index());
            });
        },
        cleanSubViews: function () {
            _.invoke(this.activityViews, 'remove');
            this.activityViews = [];
        },
        remove: function () {
            this.cleanSubViews();
            Backbone.View.prototype.remove.call(this);
        }
    });

    /**
     * The base lesson view
     */
    var LessonView = Backbone.View.extend({
        el: '#lesson',
        events: {
            'click .add-activity': 'addActivity',
            'click .add_sub_activity': 'addSubActivity',
            'click .save-lesson': 'save'
        },
        initialize: function () {
            if (typeof LESSON_ID === 'undefined') {
                // Create a new lesson if we have no lesson ID.
                this.model = new Lesson();
                this.model.set('activities', new Activities());
            } else {
                // Retrieve a lesson's activities from the server if we're editing it.
                this.model = new Lesson({id: LESSON_ID});
            }
        },
        initTooltips: function () {
            this.$el.tooltip({selector: '[data-toggle=tooltip]'});
            this.$('[data-toggle=tooltip]').tooltip({container: 'body'});
        },
        render: function () {

            function doRender() {
                this.model.set('series_id', this.$('input[name="series_id"]').val());
                rivets.bind(this.$el, {model: this.model});

                this.initTooltips();

                this.activities = new ActivitiesView({
                    collection: this.model.get('activities'),
                    lesson: this.model
                });

                if ($('#add_assessment_page').length < 1) {
                    this.activities.render();
                } else {
                    if ($('#assessment_already_created').val() == "1") {
                        this.activities.render();
                        setTimeout(function () {
                            $('#activities div.panel-default').each(function () {
                                if ($(this).html() == "") {
                                    $(this).remove();
                                } else {
                                    $(this).find('.panel-collapse').removeClass('collapse').addClass('in');
                                }
                            })
                        }, 100);
                    } else {
                        this.addActivity();
                        this.changeActivityType();
                    }
                }
            }

            if (this.model.id) {
                // If we're editing a lesson, wait for a model to load.
                this.model.fetch({
                    success: _.bind(doRender, this)
                });
            } else {
                doRender.call(this);
            }

        },
        addActivity: function () {
            this.activities.createActivity();
        },
        addSubActivity: function (ev) {
            this.activities.createSubActivity(ev);
        },
        save: function () {

            $('.feedback-editor-container > textarea').each(function (item) {
                $(this).parent().siblings("input").val($(this).parents(".panel-body").find('.feedback-editor-container div.mce-edit-area.mce-container iframe').contents().find('html body').html());
                $(this).parent().siblings("input").trigger("change");

            });

            var button = this.$('.save-lesson');

            button.attr('disabled', 'disabled');

            this.activities.updateOrder();

            var activity_to_remove_index = 0;
            var activities_to_remove_length = _activities_to_remove.length;
            for (activity_to_remove_index = 0; activity_to_remove_index < activities_to_remove_length; activity_to_remove_index++) {
                var _activity_to_remove_title = _activities_to_remove[activity_to_remove_index];

                var activity_index = 0;
                var activities_length = this.activities.lesson.attributes.activities.models.length;

                for (activity_index = 0; activity_index < activities_length; activity_index++) {
                    var _this_title = this.activities.lesson.attributes.activities.models[activity_index].attributes.title;
                    if (_this_title == _activity_to_remove_title) {
                        this.activities.lesson.attributes.activities.models.splice(activity_index, 1);
                        activity_index--;
                        break;
                    }
                }
            }

            try {
                Backbone.trigger('lesson.before-save');
            } catch (err) {
            }

            var i = 0;
            var _array = this.activities.lesson.attributes.activities.models;

            for (i = 0; i < _array.length; i++) {
                var illustration_image = $('div[activity_id="' + _array[i].id + '"]').find('input[name="illustration_image"]').val();
                var background_image = $('div[activity_id="' + _array[i].id + '"]').find('input[name="background_image"]').val();
                if (illustration_image == "" && _array[i].attributes.illustration_image != "") {
                    _array[i].attributes.illustration_image = null;
                }
                if (background_image == "" && _array[i].attributes.background_image != "") {
                    _array[i].attributes.background_image = null;
                }
            }


            for (i = 0; i < this.model.attributes.activities.models.length; i++) {
                if (this.model.attributes.activities.models[i].attributes.title == "SubActivity") {
                    this.model.attributes.activities.models.splice(i, 1);
                    i--;
                }
            }

            for (i = 0; i < this.model.attributes.activities.models.length; i++) {
                if (this.model.attributes.activities.models[i].attributes.sub_activities !== undefined) {
                    for (j = 0; j < this.model.attributes.activities.models[i].attributes.sub_activities.length; j++) {
                        if (this.model.attributes.activities.models[i].attributes.sub_activities[j].model !== undefined) {
                            this.model.attributes.activities.models[i].attributes.sub_activities[j] =
                                this.model.attributes.activities.models[i].attributes.sub_activities[j].model.toJSON();
                        }
                    }
                }
            }

            for (i = 0; i < _array.length; i++) {
                if (_array[i].get('sub_activities') !== undefined) {
                    _select_subactivities_count = 0;
                    for (var j = 0; j < _array[i].get('sub_activities').length; j++) {
                        if (_sub_activities_to_remove.indexOf(_array[i].get('sub_activities')[j].template.unique_id) != -1) {
                            _array[i].get('sub_activities').splice(j, 1);
                            j--;
                            continue;
                        }
                    }
                }
            }

            this.model.save({}, {
                success: function () {
                    button.removeAttr('disabled');
                    window.location = document.URL;
                },
                error: function () {
                    button.removeAttr('disabled');
                }
            });
            button.removeAttr('disabled');
        }
    });

    $('body').on('click', '#activities a.activity-title', function () {
        $('input[rv-checked]').each(function () {

            if ($(this).val() == "1" && $(this).is(':checked')) {
                $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').insertAfter(
                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3').not('.div_profile').insertBefore(
                    $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-6'));

                $(this).parents('.story-control').siblings('.story-description').find('.remove-story').css("left", "auto").css("right", "0");

                $(this).parents('.story-control').siblings('.story-description').find('div.col-sm-3.div_profile').removeClass('text-right').addClass('text-left');
            }

        });

    });

    $('body').on('click', 'a.remove-activity', function (event) {
        event.preventDefault();
        if (confirm('Are you sure that you want to remove this activity?')) {
            var _activity_to_remove_title = $(this).parents('.panel-default').find('span[rv-text="model:title"]').html();

            _activities_to_remove.push(_activity_to_remove_title);

            //$(this).parents('.panel-default').remove();
            $(this).parents('.panel-default').hide();
        }
    });

    $('body').on('click', 'a.remove-sub-activity', function (event) {
        event.preventDefault();
        if (confirm('Are you sure that you want to remove this sub-activity?')) {
            //var _activity_to_remove_title = $(this).parents('.panel-default').find('span[rv-text="model:title"]').html();
            //_activities_to_remove.push(_activity_to_remove_title);

            $(this).parents('.top_border_dashed').next().hide();
            $(this).parents('.top_border_dashed').hide();

            _sub_activities_to_remove.push($(this).parents('.top_border_dashed').attr("unique_id"));
        }
    });


    $('body').on('click', 'a.remove_bg_image', function (event) {
        event.preventDefault();
        if (confirm('Are you sure that you want to remove this image?')) {
            $(this).parents(".preview").siblings('input[type="hidden"]').attr("value", "");
            $(this).parents('.preview').empty();
        }
    });

    $('body').on('click', '.add_sub_activity', function (event) {
        event.preventDefault();
    });


    $(function () {
        lessonView = new LessonView();
        lessonView.render();
    });

    var guid = (function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }

        return function () {
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
        };
    })();

})();