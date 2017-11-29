<div class="form-group"
     data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/story.js'); ?>"
     data-js-handler-view="StoryView">

    <div class="story-items">
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <a href="javascript:void(0)" class="add-new-item btn btn-rad">
                <span class="fa fa-plus-circle"></span>
                Add a New Item
            </a>
        </div>
    </div>

    <script type="text/x-handlebars-template" id="story-characters-modal">
        @include('admin/activity_templates/_story_characters_modal')
    </script>

    <script type="text/x-handlebars-template" id="story-item-template">
        <div class="form-group story-description">
        <div class="col-sm-3 text-right div_profile">
        <div class="character-profile no-character select-char">
        <div class="profile-picture">
        <img src="{{ asset('/admin-static/images/no-character.jpg') }}" />
        </div>
        <div class="character-name">Select Character</div>
        </div>

        <div class="character-profile select-char hidden">
        <div class="profile-picture">
        <img rv-src="story:character:picture | assetPath uploads" />
        </div>
        <div class="character-name" rv-text="story:character:name"></div>
        </div>
        </div>

        <div class="col-sm-6">
        <textarea class="form-control"
        placeholder="Enter the story item text"
        rows="4"
        rv-value="story:text"></textarea>
        </div>

        <div class="col-sm-3">
        <a href="javascript:void(0);" class="remove-story"
        data-toggle="tooltip" data-placement="bottom"
        title="Remove the item">
        <i class="fa fa-minus-square"></i>
        </a>
        </div>
        </div>

        <div class="form-group story-control">
        <div class="col-sm-offset-3 col-sm-6">
        <span class="sorting-handle"><i class="fa fa-bars"></i></span>
        &nbsp;
        Show on the
        <label>
        <input type="radio" value="0" rv-checked="story:is_right_side" />
        &nbsp;left
        </label>
        <label>
        <input type="radio" value="1" rv-checked="story:is_right_side" />
        &nbsp;right
        </label>
        </div>
        </div>
    </script>

    <script type="text/javascript">
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
        }, 3000)
    </script>

</div>
