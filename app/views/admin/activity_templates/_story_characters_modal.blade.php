<div class="modal-dialog story-characters-modal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
				<span class="sr-only">Close</span>
			</button>
			<h4 class="modal-title">Select a Story Character</h4>
		</div>
		<div class="modal-body">
			<ul class="story-characters">
			</ul>
		</div>
		<div class="modal-footer text-left">
			<div class="btn btn-lg btn-rad btn-success create-char">Create a Story Character</div>
			<div class="hidden character-form">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="characterName">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control"
								   id="characterName" placeholder="Enter character's name"
								   rv-value="character:name">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="btn btn-rad btn-success upload-picture">Upload Portrait</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default save-changes">Save</button>
							<button type="submit" class="btn close-editor">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/x-handlebars-template" id="story-characters-modal-item-template">
		<div class="actions">
			<a class="fa fa-pencil-square-o edit-character" title=""
			   data-toggle="tooltip" data-placement="bottom"
			   data-original-title="Edit Character"></a>
			<a class="fa fa-minus-square-o remove-character" title=""
			   data-toggle="tooltip" data-placement="bottom"
			   data-original-title="Remove Character"></a>
		</div>
		<div class="profile-picture">
			<img rv-src="character:picture | assetPath uploads" />
		</div>
		<div class="character-name" rv-html="character:name | ifEmptyShowNone">
		</div>
	</script>
</div>
