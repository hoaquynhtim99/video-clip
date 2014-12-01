<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post" id="modConf">
    <table class="table table-striped table-bordered table-hover">
		<col style="width:50%"/>
	    <tbody>
	        <tr>
	            <td><strong>{LANG.NumberOfLinks}</strong>
	            </td>
	            <td>
	                <select name="otherClipsNum" id="otherClipsNum" class="form-control ajvd-input">
	                    <!-- BEGIN: otherClipsNum -->
	                    <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
	                    <!-- END: otherClipsNum -->
	                </select>
	            </td>
	        </tr>
	        <tr>
	            <td><strong>{LANG.playerAutostart}</strong></td>
	            <td><input type="checkbox" value="1" name="playerAutostart" id="playerAutostart"{CONFIGMODULE.playerAutostart} /></td>
	        </tr>
	        <tr>
	            <td><strong>{LANG.playerSkin}</strong></td>
	            <td>
	                <select name="playerSkin" id="playerSkin" class="form-control ajvd-input">
	                    <option value="">{LANG.noSkin}</option>
	                    <!-- BEGIN: playerSkin -->
	                    <option value="{SKIN.value}"{SKIN.select}>{SKIN.value}</option>
	                    <!-- END: playerSkin -->
	                </select>
	            </td>
	        </tr>
	    </tbody>
	    <!--tbody>
	        <tr>
	            <td><strong>{LANG.commnum}</strong></td>
	            <td>
	                <select class="form-control" name="commNum" id="commNum">
	                    <!-- BEGIN: commNum -->
	                    <option value="{COMMNUM.value}"{COMMNUM.select}>{COMMNUM.value}</option>
	                    <!-- END: commNum -->
	                </select>
	            </td>
	        </tr>
	    </tbody-->
	    <tbody>
	        <tr>
	            <td><strong>{LANG.embedMaxWidth}</strong>
	            </td>
	            <td><input style="width: 50px;" type="text" name="playerMaxWidth" id="playerMaxWidth" value="{CONFIGMODULE.playerMaxWidth}" class="form-control ajvd-input"/></td>
	        </tr>
	    </tbody>
	    <!--tbody>
	        <tr>
	            <td><strong>{LANG.allowUpload}</strong></td>
	            <td><input type="checkbox" value="1" name="allowUpload" id="allowUpload"{CONFIGMODULE.allowUpload} /></td>
	        </tr>
	    </tbody-->
	    <tbody>
	        <tr>
	            <td><strong>{LANG.folderStructureEnable}</strong></td>
	            <td><input type="checkbox" value="1" name="folderStructureEnable" id="folderStructureEnable"{CONFIGMODULE.folderStructureEnable} /></td>
	        </tr>
	        <tr>
	            <td><strong>{LANG.titleLength}</strong></td>
	            <td><input class="form-control ajvd-input" type="text" value="{CONFIGMODULE.titleLength}" name="titleLength" id="titleLength"/></td>
	        </tr>
	    </tbody>
	</table>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
	    <input type="submit" name="submit" value="{LANG.save}" class="ajvd-button"/>
	</div>
</form>
<script type="text/javascript">
$("#modConf").submit(function(){
    var a = "submit=1&playerMaxWidth=" + intval($("#playerMaxWidth").val()) + "&allowUpload=" + (1 == $("#allowUpload:checked").length ? 1 : 0) + "&folderStructureEnable=" + (1 == $("#folderStructureEnable:checked").length ? 1 : 0) + "&commNum=" + $("#commNum").val() + "&otherClipsNum=" + $("#otherClipsNum").val() + "&playerAutostart=" + (1 == $("#playerAutostart:checked").length ? 1 : 0) + "&playerSkin=" + $("#playerSkin").val() + '&titleLength=' + $('#titleLength').val();
    return $.ajax({
        type: "POST",
        url: window.location.href,
        data: a,
        success: function(){
            return alert("{LANG.successfullySaved}"), !1
        }
    }), !1
});
</script>
<!-- END: main -->