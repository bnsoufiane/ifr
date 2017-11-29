<div class="calculation"
     data-js-handler-file="<?php echo asset('/assets/admin/activity-templates/calculation.js'); ?>"
     data-js-handler-view="CalculationView">
    <form>
        <div class="form-group">
            <label class="col-sm-3 control-label">Name the Company</label>

            <div class="col-sm-6">
                <input class="form-control" rv-value="calculation:name"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>

            <div class="col-sm-6">
                <textarea class="form-control" rows="6"
                          rv-value="calculation:description"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Column text 1</label>

            <div class="col-sm-6">
                <input class="form-control" rv-value="calculation:column_1"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Column text 2</label>

            <div class="col-sm-6">
                <input class="form-control" rv-value="calculation:column_2"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Column text 3</label>

            <div class="col-sm-6">
                <input class="form-control" rv-value="calculation:column_3"/>
            </div>
        </div>
    </form>

    <div class="row">
        <label class="col-sm-3 control-label">Footers</label>

        <div class="col-sm-9 footers">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <a href="javascript:void(0)" class="add-new-footer btn btn-rad">
                <span class="fa fa-plus-circle"></span>
                Add Footer
            </a>
        </div>
    </div>


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

    <script type="text/x-handlebars-template" id="calculation-item-template">
        <div class="col-sm-6">
            <input type="text" rv-value="item:name"
                   placeholder="Item name"
                   class="form-control"/>
        </div>
        <div class="col-sm-1">
            <input type="text" rv-value="item:employer_cost"
                   name="employer-cost" placeholder="$6.50"
                   class="form-control"/>
        </div>
        <div class="col-sm-1">
            <input type="text" rv-value="item:cost_unit"
                   placeholder="per copy"
                   class="form-control"/>
        </div>
        <div class="col-sm-1">
            <a href="javascript:void(0);" class="remove-option"
               data-toggle="tooltip" data-placement="bottom" title="Remove the item">
                <i class="fa fa-minus-square"></i>
            </a>
        </div>
    </script>

    <script type="text/x-handlebars-template" id="calculation-footer-template">
        <div class="col-sm-6">
            <input type="text" rv-value="footer:name"
                   placeholder="Footer name"
                   class="form-control"/>
        </div>
        <div class="col-sm-1">
            <a href="javascript:void(0);" class="remove-footer"
               data-toggle="tooltip" data-placement="bottom" title="Remove the footer">
                <i class="fa fa-minus-square"></i>
            </a>
        </div>
    </script>
</div>
