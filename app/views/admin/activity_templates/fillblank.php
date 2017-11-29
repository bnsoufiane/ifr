<div class="fillblank"
     data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/fillblank.js'); ?>"
     data-js-handler-view="FillblankView">
    <form>
        <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>

            <div class="col-sm-6">
                <textarea class="form-control" rows="6"
                          rv-value="fillblank:description"></textarea>
            </div>
        </div>
    </form>

    <div class="row">
        <label class="col-sm-3 control-label">Items</label>

        <div class="col-sm-9 items">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <a href="javascript:void(0)" class="add-new-item btn btn-rad">
                <span class="fa fa-plus-circle"></span>
                Add Item
            </a>
        </div>
    </div>

    <script type="text/x-handlebars-template" id="fillblank-item-template">
        <div class="col-sm-6">
            <input type="text" rv-value="item:name"
                   placeholder="Item name"
                   class="form-control"/>
        </div>
        <div class="col-sm-1">
            <a href="javascript:void(0);" class="remove-option"
               data-toggle="tooltip" data-placement="bottom" title="Remove the item">
                <i class="fa fa-minus-square"></i>
            </a>
        </div>
    </script>
</div>
