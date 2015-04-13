		<script>
		<!--
		function trim(stringToTrim) {
			return stringToTrim.replace(/^\s+|\s+$/g,"");
		}
		
		function check_item(i, min, max)
		{
			i.value = trim(i.value);
			if(i.value == "") {
				return false;
			}
			i.value = parseInt(i.value, 10);
			if(isNaN(i.value)) {
				return false;
			} else if (i.value < min) {
				return false;
			} else if (i.value > max) {
				return false;
			}
			return true;
		}
			
		function alert_item(i, min, max)
		{
			if (check_item(i, min, max)) {
				return true;
			} else {
				alert(i.name + " - Valeur incorrecte [" + min +"-"+max+"]");
				i.focus();
				return false;
			}
		}
		
		function check_cgu(f)
		{
			tmp = trim(f.cgu_contents.value);
			if (f.usage_terms.checked && tmp == "")
			{
				alert("{L_CGU_INVALID}");
				f.cgu_contents.focus();
				return false;
			}
			return true;
		}

		function check_form_conf(o)
		{
			# START config #
			if (!alert_item(o.{config.NAME}, {config.MIN}, {config.MAX})) return false;
			# END config #

			if (!check_cgu(o)) return false;

			return true;
		}	
		-->
		</script>
		
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_SMALLADS}</li>
				<li>
					<a href="admin_smallads.php"><img src="smallads.png" alt="" /></a>
					<br />
					<a href="admin_smallads.php" class="quick-link">{L_SMALLADS_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			<form action="admin_smallads.php" method="post" onsubmit="return check_form_conf(this);" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					# START config #
					<div class="form-element">
						<label for="{config.NAME}">* {config.L_LABEL}</label>
						<div class="form-field">
							<label><input type="text" name="{config.NAME}" value="{config.VALUE}" /></label>
						</div>
					</div>
					# END config #
					# START config_checkbox #
					<div class="form-element">
						<label for="{config_checkbox.NAME}">* {config_checkbox.L_LABEL}</label>
						<div class="form-field">
							<label><input type="checkbox" name="{config_checkbox.NAME}" value="{config_checkbox.VALUE}" {config_checkbox.CHECKED} /></label>
						</div>
					</div>
					# END config_checkbox #
					
					<label for="cgu_contents">{L_CGU_CONTENTS}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="25" cols="50" id="cgu_contents" name="cgu_contents">{CGU_CONTENTS}</textarea></label>
					<div class="center"><button type="button" class="small" onclick="XMLHttpRequest_preview('cgu_contents');">{L_PREVIEW}</button></div>
				</fieldset>
				
				<fieldset>
					<legend>{L_GLOBAL_AUTH}</legend>
					<p style="color:red">{L_AUTH_MESSAGE}</p>
					# START auth #
					<div class="form-element">
						<label>{auth.L_SELECT}</label>
						
						<div class="form-field">
							{auth.SELECT}
						</div>
					</div>
					# END auth #
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}" />
				</fieldset>
			</form>
		</div>
		