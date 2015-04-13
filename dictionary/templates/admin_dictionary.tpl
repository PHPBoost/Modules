<script type="text/javascript">
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
			alert(i.name + " - {L_VAL_INC}");
			i.focus();
			return false;
		}
	}

	function check_form_conf(o)
	{
		if (!alert_item(o.pagination_nb)) return false;
		if (!alert_item(o.max_links)) return false;
		if (!alert_item(o.quotes_list_size)) return false;
		return true;
	}
	
	function check_select_multiple(id, status)
	{
		var i;
		for(i = 0; i < {NBR_TAGS}; i++)
		{
			if( document.getElementById(id + i) )
				document.getElementById(id + i).selected = status;
		}
	}
-->
</script>

<div id="admin-quick-menu">
	<ul>
		<li class="title-menu">{TITLE}</li>
		<li>
			<a href="admin_dictionary_cats.php"><img src="dictionary.png" alt="{L_DICTIONARY_CATS}" /></a>
			<br />
			<a href="admin_dictionary_cats.php" class="quick-link">{L_DICTIONARY_CATS}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1&token={TOKEN}"><img src="dictionary.png" alt="{L_DICTIONARY_CATS_ADD}" /></a>
			<br />
			<a href="admin_dictionary_cats.php?add=1&token={TOKEN}" class="quick-link">{L_DICTIONARY_CATS_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php"><img src="dictionary.png" alt="{L_LIST_DEF}" /></a>
			<br />
			<a href="admin_dictionary_list.php" class="quick-link">{L_LIST_DEF}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1&token={TOKEN}"><img src="dictionary.png" alt="{L_DICTIONARY_ADD}" /></a>
			<br />
			<a href="dictionary.php?add=1&token={TOKEN}" class="quick-link">{L_DICTIONARY_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary.php"><img src="dictionary.png" alt="{L_DICTIONARY_CONFIG}" /></a>
			<br />
			<a href="admin_dictionary.php" class="quick-link">{L_DICTIONARY_CONFIG}</a>
		</li>
	</ul>
</div>

<div id="admin-contents">
	<form action="admin_dictionary.php?token={TOKEN}" method="post" onsubmit="return check_form_conf(this);" class="fieldset-content">
		<fieldset>
			<legend>{L_CONFIGURATION}</legend>
			<div class="form-element">
				<label for="pagination_nb">* {L_PAGINATION_NB}</label>
				<div class="form-field">
					<label><input type="text" size="3" id="pagination_nb" name="pagination_nb" value="{PAGINATION_NB}"  onchange="check_onchange(this);" /></label>
					<label id="pagination_nb_error" style="display:none;color:red">{L_VAL_INC}</label>
				</div>
			</div>
			<div class="form-element">
				<label for="dictionary_forbidden_tags">* {L_FORBIDDEN_TAGS}</label>
				<div class="form-field">
					<label>
						<span class="small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
						<br />
						<select name="dictionary_forbidden_tags[]" id="dictionary_forbidden_tags" size="10" multiple="multiple">
							# START tag #
								<option id="tag{tag.I}" value="{tag.VALUE}" {tag.SELECTED}>{tag.NAME}</option>
							# END tag #
						</select>
						<br />
						<a class="small" href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
					</label>
				</div>
			</div>
			<div class="form-element">
				<label for="dictionary_max_link">* {L_MAX_LINK}
					<span class="field-description">{L_MAX_LINK_EXPLAIN}</span>
				</label>
				<div class="form-field">
					<label><input type="text" size="3" name="dictionary_max_link" id="dictionary_max_link" value="{MAX_LINK}" /></label>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>{L_AUTH}</legend>
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
			<legend>{L_SUBMIT}</legend>
			<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
			<button type="reset" value="true">{L_RESET}</button>
		</fieldset>
	</form>
</div>
