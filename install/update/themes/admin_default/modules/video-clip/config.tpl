<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" role="form" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="form-group">
            <label for="otherClipsNum" class="control-label col-sm-8">{LANG.NumberOfLinks}:</label>
            <div class="col-sm-16 col-lg-6">
                <select name="otherClipsNum" id="otherClipsNum" class="form-control">
                    <!-- BEGIN: otherClipsNum -->
                    <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
                    <!-- END: otherClipsNum -->
                </select>
                <i class="help-block">{LANG.NumberOfLinks1}</i>    
            </div>
        </div>
        <div class="form-group">
            <label for="commNum" class="control-label col-sm-8">{LANG.NumberOfLinks}:</label>
            <div class="col-sm-16 col-lg-6">
                <select name="commNum" id="commNum" class="form-control">
                    <!-- BEGIN: commNum -->
                    <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
                    <!-- END: commNum -->
                </select>
                <i class="help-block">{LANG.NumberOfLinksCom}</i>    
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="playerAutostart" id="playerAutostart" value="1"{CONFIGMODULE.playerAutostart} /> {LANG.playerAutostart}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="playerSkin" class="control-label col-sm-8">{LANG.playerSkin}:</label>
            <div class="col-sm-16 col-lg-6">
                <select name="playerSkin" id="playerSkin" class="form-control">
                    <option value="">{LANG.noSkin}</option>
                    <!-- BEGIN: playerSkin -->
                    <option value="{SKIN.value}"{SKIN.select}>{SKIN.value}</option>
                    <!-- END: playerSkin -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="aspectratio" class="control-label col-sm-8">{LANG.cfg_aspectratio}:</label>
            <div class="col-sm-16 col-lg-6">
                <select name="aspectratio" id="aspectratio" class="form-control">
                    <!-- BEGIN: aspectratio -->
                    <option value="{ASPECTRATIO.value}"{ASPECTRATIO.select}>{ASPECTRATIO.value}</option>
                    <!-- END: aspectratio -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="1" name="folderStructureEnable" id="folderStructureEnable"{CONFIGMODULE.folderStructureEnable} /> {LANG.folderStructureEnable}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="titleLength" class="control-label col-sm-8">{LANG.titleLength}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control required" type="text" name="titleLength" id="titleLength" value="{CONFIGMODULE.titleLength}"/>
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