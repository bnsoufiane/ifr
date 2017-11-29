<div data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/truefalse.js'); ?>"
     data-js-handler-view="@yield('view-handler', 'TrueFalseView')">
    @section('entity-form')
        <form>
            <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-6">
				<textarea class="form-control" rows="6"
                          rv-value="yesno:description"></textarea>
                </div>
            </div>
        </form>
    @show

    <div class="sections">
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <a href="javascript:void(0);" class="add-section">
                <h3 class="title-header">
                    <span rv-text="section:title">Add Section</span>
                    &nbsp;<i class="fa fa-plus-square-o"></i>
                </h3>
            </a>
        </div>
    </div>
</div>





