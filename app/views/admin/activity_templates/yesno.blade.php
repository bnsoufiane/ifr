<div data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/yesno.js'); ?>"
	 data-js-handler-view="@yield('view-handler', 'YesNoView')">
	@section('entity-form')
	<form>
		<div class="form-group">
			<label class="col-sm-3 control-label">Description</label>
			<div class="col-sm-6">
				<textarea class="form-control" rows="6"
						  rv-value="yesno:description"></textarea>
			</div>
		</div>

        <?php $yesNoLetters = ['A','B','C','D','E','F','G','I','K','L','M','N','O','P','R','S','U', 'X','Y']; ?>

        <div class="form-group">
            <label class="col-sm-3 control-label">Yes Letter</label>
            <div class="col-sm-6">
                <select class="form-control" name="yes_letter" rv-value="yesno:yes_letter" value="X">
                    @foreach($yesNoLetters as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">No Letter</label>
            <div class="col-sm-6">
                <select class="form-control" name="no_letter" rv-value="yesno:no_letter" value="O">
                    @foreach($yesNoLetters as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
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
