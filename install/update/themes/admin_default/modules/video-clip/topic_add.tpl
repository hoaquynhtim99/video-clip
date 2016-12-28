<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" role="form" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="form-group">
            <label for="title" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.topic_name}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control required" type="text" name="title" id="title" value="{DATA.title}" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="control-label col-sm-8">{LANG.description}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="description" id="description" value="{DATA.description}" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="keywords" id="keywords" value="{DATA.keywords}" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="parentid" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.topic_parent}:</label>
            <div class="col-sm-16 col-lg-6">
                <select name="parentid" id="parentid" class="form-control required">
                    <!-- BEGIN: parentid -->
                    <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                    <!-- END: parentid -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
                <input type="hidden" name="submit" value="1"/>
                <input type="submit" value="{LANG.save}" class="btn btn-primary"/>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->