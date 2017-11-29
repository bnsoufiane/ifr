<div class="form-group"
	 data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/basic-binder.js'); ?>"
	 data-js-handler-view="BasicBinder">

    <div class="form-group">
        <label class="col-sm-3 control-label">Question</label>
        <div class="col-sm-6">
            <input type="text" class="form-control freeform_input" placeholder="Enter a question to be answered"
                rv-value="model:description" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Description</label>
        <div class="col-sm-6">
            <textarea type="text" class="form-control" rows="5" placeholder="Enter explanation body"
                      rv-value="model:explanation"></textarea>
        </div>
    </div>
</div>
