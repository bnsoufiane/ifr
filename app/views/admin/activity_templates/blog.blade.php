<div data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/basic-binder.js'); ?>"
    data-js-handler-view="BasicBinder">
	<div class="form-group">
		<label class="col-sm-3 control-label">Title</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" placeholder="Enter a title for the page"
				   rv-value="model:title" />

		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Explanation</label>
		<div class="col-sm-6">
			<textarea type="text" class="form-control" rows="5" placeholder="Enter explanation body"
					  rv-value="model:explanation"></textarea>
		</div>
	</div>
</div>
