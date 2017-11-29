<div data-js-handler-file="{{ asset('/assets/admin/activity-templates/select.js') }}"
	 data-js-handler-view="SelectView">

	 <div class="row">
         <label class="col-sm-3 control-label">Question</label>
         <div class="col-sm-6">
         <input type="text" class="form-control select_descr" rows="5" placeholder="Enter Description body"
                 rv-value="selectmodel:description"/>
         </div>
     </div>

	<div class="row">
		<label class="col-sm-3 control-label">Description</label>
		<div class="col-sm-6">
			<textarea type="text" class="form-control select_expl" rows="5" placeholder="Enter Description body"
					  rv-value="selectmodel:explanation"></textarea>
		</div>
	</div>
	<div class="row">
		<label class="col-sm-3 control-label">
			Options
		</label>
		<div class="col-sm-9">
			<div class="options"></div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-6 col-sm-offset-3">
			<a href="javascript:void(0)" class="add-option btn btn-rad">
				<span class="fa fa-plus-circle"></span>
				Add a New Option
			</a>
		</div>
	</div>
</div>
