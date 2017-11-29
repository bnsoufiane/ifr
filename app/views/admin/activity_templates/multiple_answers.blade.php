<div data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/multiple-answers.js'); ?>"
	 data-js-handler-view="MultipleAnswersView">
	<form>
		<div class="form-group">
			<label class="col-sm-3 control-label">Description</label>
			<div class="col-sm-6">
				<textarea class="form-control" rows="3" rv-value="model:description"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Number of possible answers</label>
			<div class="col-sm-6">
				<select class="form-control" rv-value="model:number_of_fields">
					<?php for ($i = 1; $i <= 10; $i++): ?>
					<option><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Example answers</label>
			<div class="col-sm-6 fields-presets">
			</div>
		</div>
	</form>
</div>
