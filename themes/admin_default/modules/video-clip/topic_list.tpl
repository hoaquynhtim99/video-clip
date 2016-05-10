<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>{TABLE_CAPTION}</h2>
    </div>
    <div class="panel-body items">
        <div class="loop-item item-header">
            <div class="item-order">
                {LANG.position}
            </div>
            <div class="item-title">
                {LANG.topic_name}
            </div>
            <div class="item-description">
                {LANG.topic_parent}
            </div>
            <div class="item-tools">
                {LANG.feature}
            </div>
            <div class="item-status">
                {LANG.is_active}
            </div>
        </div>
        <!-- BEGIN: row -->
        <div class="loop-item">
            <div class="item-order">
                <select name="weight" id="weight{ROW.id}" onchange="nv_chang_weight({ROW.id});" class="form-control ajvd-input">
                    <!-- BEGIN: weight -->
                    <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                    <!-- END: weight -->
                </select>
            </div>
            <div class="item-title">
                <strong><a href="{ROW.titlelink}">{ROW.title}</a></strong> {ROW.numsub}
            </div>
            <div class="item-description">
                {ROW.parentid}
            </div>
            <div class="item-tools">
                <a href="{EDIT_URL}" title="{GLANG.edit}"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" onclick="nv_topic_del({ROW.id});" title="{GLANG.delete}"><i class="fa fa-trash-o"></i></a>
            </div>
            <div class="item-status">
                <input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_status({ROW.id});" />
            </div>
        </div>
        <!-- END: row -->
    </div>
    <div class="panel-footer">
        <a class="btn btn-sm btn-success" href="{ADD_NEW_TOPIC}"><i class="fa fa-plus"></i> {LANG.addtopic_titlebox}</a>
    </div>
</div>
<!-- END: main -->