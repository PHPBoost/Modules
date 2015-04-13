	# INCLUDE ADMIN_MENU #
	
	<script>
	<!--
	function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	}
	
	function check_item(i)
	{
		i.value = trim(i.value);
		if(i.value == "") {
			return false;
		} else if(isNaN(i.value)) {
			return false;
		} else if (i.value <= 0) {
			return false;
		}
		return true;
	}
	
	function check_onchange(i)
	{
		var j = document.getElementById(i.name+"_error");
		if (check_item(i)) {
			j.style.display = "none";
			return true;
		} else {
			j.style.display = "inline";
			return false;
		}
	}
	
	function alert_item(i)
	{
		if (check_item(i)) {
			return true;
		} else {
			alert(i.name + " - Valeur incorrecte");
			i.focus();
			return false;
		}
	}

	function check_form_conf(o)
	{
		if (!alert_item(o.items_per_page)) return false;
		if (!alert_item(o.cat_cols)) return false;
		return true;
	}	
	-->
	</script>
	
	<div id="admin-contents">
		<form action="admin_quotes.php" method="post" onsubmit="return check_form_conf(this);" class="fieldset-content">
			<p class="center">{L_REQUIRE}</p>
			<fieldset>
				<legend>{L_QUOTES_CONFIG}</legend>
				# START config #
				<div class="form-element">
					<label for="{config.NAME}">*&nbsp;{config.L_LABEL}</label>
					<div class="form-field">
						<label><input type="text" size="{config.SIZE}" maxlength="{config.MAXLENGTH}" name="{config.NAME}" value="{config.VALUE}" onchange="check_onchange(this);" />
						<span id="{config.NAME}_error" style="color:red;display:none">Valeur incorrecte</span></label>
					</div>
				</div>
				# END config #
			</fieldset>
			
			<fieldset>
				<legend>{L_GLOBAL_AUTH}</legend>
				<p>{L_GLOBAL_AUTH_EXPLAIN}</p>
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
			</fieldset>
		</form>
	</div>
		