<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<thead>
			<tr>
				<th style="width:100px">{LANG.position}</th>
				<th>{LANG.topic_name}</th>
				<th>{LANG.topic_parent}</th>
				<th style="width:150px;text-align:center">{LANG.is_active}</th>
				<th style="width:100px;white-space:nowrap;text-align:center">{LANG.feature}</th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr>
				<td>
					<select name="weight" id="weight{ROW.id}" onchange="nv_chang_weight({ROW.id});" class="form-control ajvd-input">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
						<!-- END: weight -->
					</select>
				</td>
				<td>
					<strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub}
				</td>
				<td>
					{ROW.parentid}
				</td>
				<td style="white-space:nowrap;text-align:center">
					<input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_status({ROW.id});" />
				</td>
				<td style="white-space:nowrap;text-align:center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a>
					&nbsp;&nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_topic_del({ROW.id});">{GLANG.delete}</a>
				</td>
			</tr>
		<!-- END: row -->
		<tbody>
	</table>
</div>
<div style="margin-top:8px;">
	<a class="btn btn-success" href="{ADD_NEW_TOPIC}"><em class="fa fa-plus">&nbsp;</em>{LANG.addtopic_titlebox}</a>
</div>
<!-- END: main -->