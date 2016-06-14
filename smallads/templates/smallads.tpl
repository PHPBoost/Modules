# IF C_VIEW #
# START item #
<article id="article-smallads-{item.ID}">
	<header>
		<h1>
			<span>{item.TYPE} - {item.TITLE}</span>
			<span class="actions">
				# IF item.C_EDIT #
					<a href="{PATH_TO_ROOT}/smallads/smallads{item.URL_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
				# ENDIF #
				# IF item.C_DELETE #
					<a href="{PATH_TO_ROOT}/smallads/smallads{item.URL_DELETE}&token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				# ENDIF #
			</span>
		</h1>
	</header>
	<div class="content">
		# IF NOT item.C_DB_APPROVED #
		<p style="font-weight:bold; font-size:large; color:red">{L_NOT_APPROVED}</p>
		# ENDIF #
		# IF item.C_PICTURE #
		<div style="float:left;margin:10px">
			<a href="{item.PICTURE}" data-lightbox="formatter"><img src="{item.PICTURE}" /></a>
		</div>
		# ENDIF #
		<div>{item.CONTENTS}</div>
		<br />
		<div class="small">
			<p>{L_PRICE}&nbsp;: {item.PRICE}&nbsp;{L_PRICE_UNIT}</p>
			# IF item.C_SHIPPING #<p>{L_SHIPPING}&nbsp;: {item.SHIPPING}&nbsp;{L_SHIPPING_UNIT}</p># ENDIF #
			<p>{item.DB_CREATED}<br />{item.DB_UPDATED}</p>
			<p>id \#{item.ID}</p>
			# IF item.VID #
			<p>Contribution de modification de \#{item.VID}</p>
			# ENDIF #
		</div>
		<br />
		<span style="float:left;">
			{item.USER} {item.USER_PM} {item.USER_MAIL}
		</span>
	</div>
	<footer></footer>
</article>
# END item #
# ENDIF #


# IF C_LIST #
<script>
	<!--
	function radioClicked(Nom)
	{
		var r = false;
		var d = document.getElementsByName(Nom);
		for(var i=0; i<d.length; i++)
		{
			if(d[i].type=='radio' && d[i].checked)
			{
				r = d[i].value;
				break;
			}
		}
		return r ? r : 0;
	}

	function change_order()
	{
		window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value + "&type=" + radioClicked("radio_type");
	}

	function change_filter()
	{
		window.location = "{TARGET_ON_CHANGE_ORDER}type=" + radioClicked("radio_type");
	}

	function view_not_approved()
	{
		window.location = "{TARGET_ON_CHANGE_ORDER}ViewNotApproved=1";
	}
	-->
</script>

<section id="module-smallads">
	<header>
		<h1>
			{L_TITLE}
		</h1>
	</header>
	<div class="content">
		# IF C_DESCRIPTION #
			{DESCRIPTION}
			<hr style="margin-top:25px;margin-bottom:10px;" />
		# ENDIF #

		<p>
		# START type_options #
		&nbsp;&nbsp;<input type="radio" name="radio_type" value="{type_options.VALUE}" {type_options.CHECKED}>&nbsp;{type_options.NAME}</input>
		# END type_options #
		&nbsp;&nbsp;<button class="button" onclick="change_filter()" value="true">OK</button>
		</p>
		# IF C_DISPLAY_NOT_APPROVED #
		<p style="margin-top:1.5em"><button class="button" onclick="view_not_approved()" value="true">{L_LIST_NOT_APPROVED}</button></p>
		# ENDIF #
		<hr style="margin-top:10px;" />
			<div class="spacer">&nbsp;</div>
		
		# IF NOT C_NB_SMALLADS #
			<div class="center">
				{L_NO_SMALLADS}
			</div>
		# ELSE #
			<div style="float:right;" id="form">
				{L_ORDER_BY}
				<select name="sort" id="sort" class="nav" onchange="change_order()">
					# START sort_options #
					<option value="{sort_options.VALUE}" {sort_options.SELECTED}>{sort_options.NAME}</option>
					# END sort_options #
				</select>
				<select name="mode" id="mode" class="nav" onchange="change_order()">
					# START mode_options #
					<option value="{mode_options.VALUE}" {mode_options.SELECTED}>{mode_options.NAME}</option>
					# END mode_options #
				</select>
			</div>
			<div class="spacer">&nbsp;</div>

			# START item #
				<div id="smallads_{item.ID}" class="block-container" style="margin-bottom:20px;">
					<div class="block_contents">
						<p style="margin-bottom:10px">
							<a href="{PATH_TO_ROOT}/smallads/smallads{item.URL_VIEW}" style="font-size:large">{item.TYPE} - {item.TITLE}</a>
							# IF item.C_EDIT #
								&nbsp;&nbsp;
								<a href="{PATH_TO_ROOT}/smallads/smallads{item.URL_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							# ENDIF #
							# IF item.C_DELETE #
								<a href="{PATH_TO_ROOT}/smallads/smallads{item.URL_DELETE}&token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
							# ENDIF #
						</p>
						# IF NOT item.C_DB_APPROVED #
						<p style="font-weight:bold; font-size:large; color:red">{item.L_NOT_APPROVED}</p>
						# ENDIF #
						# IF item.C_PICTURE #
						<div style="float:left;margin:10px">
							<a href="{item.PICTURE}" data-lightbox="formatter"><img src="{item.PICTURE}" /></a>
						</div>
						# ENDIF #
						<div>{item.CONTENTS}</div>
						<br />
						<div class="small">
							<p>{L_PRICE}&nbsp;: {item.PRICE}&nbsp;{L_PRICE_UNIT}</p>
							# IF item.C_SHIPPING #<p>{L_SHIPPING}&nbsp;: {item.SHIPPING}&nbsp;{L_SHIPPING_UNIT}</p># ENDIF #
							<p>{item.DB_CREATED}<br />{item.DB_UPDATED}</p>
							<p>id \#{item.ID}</p>
							# IF item.VID #
							<p>Contribution de modification de \#{item.VID}</p>
							# ENDIF #
						</div>
						<br />
						<span style="float:left;">
							{item.USER} {item.USER_PM} {item.USER_MAIL}
						</span>
						<div class="spacer"></div>
					</div>
				</div>
			# END item #
		# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
# ENDIF #


# IF C_FORM #
	<script>
		<!--
		function trim(stringToTrim) {
			return stringToTrim.replace(/^\s+|\s+$/g,"");
		}

		function check_item(i, n)
		{
			i.value = trim(i.value);
			if(i.value == "") {
				alert(n + " : "+"{L_ALERT_TEXT}");
				i.focus();
				return false;
			}
			return true;
		}

		function check_num(i, n)
		{
			i.value = trim(i.value);
			if(i.value != "" && isNaN(i.value)) {
				alert(n + " : " + "{L_ALERT_FLOAT}");
				i.focus();
				return false;
			}
			return true;
		}

		function check_upload(i, n)
		{
			fname = trim(i.value);
			var recherche = /\.(jpeg|jpg|gif|png)$/i;
			if(fname != "" && recherche.test(fname)==false) {
				alert(n + " : " + "{L_ALERT_UPLOAD}");
				i.value = '';
				i.focus();
				return false;
			}
			return true;
		}

		function check_checkbox(i, msg)
		{
			if( i.checked == false )
			{
				alert(msg);
				return false;
			}
			return true;
		}

		function check_form(o){
			if (!check_item(o.smallads_title, "{L_DB_TITLE}")) return false;
			if (!check_item(o.smallads_contents, "{L_DB_CONTENTS}")) return false;
			if (!check_num(o.smallads_price, "{L_DB_PRICE}")) return false;
			if (!check_num(o.smallads_shipping, "{L_DB_SHIPPING}")) return false;
			if (!check_upload(o.smallads_picture, "{L_DB_PICTURE}")) return false;
			if (!check_checkbox(o.usage_agreement, "{L_CGU_NOT_AGREED}")) return false;
			return true;
		}

		// Original:  Ronnie T. Moore
		// Dynamic 'fix' by: Nannette Thacker
		function textCounter(field, countfield, maxlimit) {
			field = document.getElementById(field);
			countfield = document.getElementById(countfield);
			var reg = new RegExp("\r\n", "g");
			
			value = field.value;
			length = value.replace(reg,"").length;
			
			if (length > maxlimit) // if too long...trim it!
				field.value = field.value.substring(0, maxlimit);
			// otherwise, update 'characters left' counter
			else {
				new_value = maxlimit - (length + 1);
				if (new_value > 0)
					countfield.value = new_value;
				else
					countfield.value = 0;
			}
		}

		-->
	</script>

	# INCLUDE MSG #

	<div>
	<form action="smallads.php?token={TOKEN}" method="post" onsubmit="return check_form(this);" class="fieldset-content" enctype="multipart/form-data" >
		<p class="center">{L_REQUIRE}</p>
		
		# IF C_USAGE_TERMS #
		<fieldset>
			<legend>
				{L_USAGE_LEGEND}
			</legend>
			<br />
			<div style="width:auto;height:250px;overflow-y:scroll;border:1px solid \#DFDFDF;background-color:\#F1F4F1">
				{CGU_CONTENTS}
			</div>
			<div style="text-align:center;margin:1.5em;">
				<label style="cursor:pointer;">
					<input type="checkbox" name="usage_agreement" id="usage_agreement" class="valign-middle" />
					{L_AGREE_TERMS}
				</label>
			</div>
		</fieldset>
		# ENDIF #

		<fieldset>
			<legend>{L_LEGEND}</legend>
			<div class="form-element">
				<label for="smallads_type">{L_DB_TYPE}</label>
				<div class="form-field">
					<label>
					<select id="smallads_type" name="smallads_type">
					# START type_options_edit #
						<option value="{type_options_edit.VALUE}" {type_options_edit.SELECTED}>{type_options_edit.NAME}</option>
					# END type_options_edit #
					</select>
					</label>
				</div>
			</div>
			<div class="form-element">
				<label for="smallads_title">*&nbsp;{L_DB_TITLE}</label>
				<div class="form-field">
					<label><input type="text" id="smallads_title" name="smallads_title" value="{DB_TITLE}" class="field-large" /></label>
				</div>
			</div>
			
			<div class="form-element-textarea">
				<label for="smallads_contents">*&nbsp;{L_DB_CONTENTS}</label>
				{KERNEL_EDITOR}
				<div class="form-field-textarea">
					<textarea rows="10" cols="50" id="smallads_contents" name="smallads_contents" onKeyDown="textCounter('smallads_contents','remLen',{DB_MAXLEN});">{DB_CONTENTS}</textarea>
				</div>
				<br />
				<center>
				<font size="1">car. restants : <input readonly="readonly" type=text name="remLen" id="remLen" size="4" maxlength="3" value="{DB_CONTENTS_REMAIN}"></font>
				</center>
				<br />
			</div>

			<div class="form-element">
				<label for="smallads_picture">{L_DB_PICTURE}
				<span class="field-description">{L_MAX_PICTURE_WEIGHT}</span>
				</label>
				<div class="form-field">
					# IF C_PICTURE #
					<div style="float:left">
						<a href="{DB_PICTURE}" data-lightbox="formatter"><img src="{DB_PICTURE}" /></a>
						<a href="{PATH_TO_ROOT}/smallads/smallads.php?delete_picture={ID}&token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
					</div>
					# ENDIF #
					<label><input type="file" id="smallads_picture" name="smallads_picture" value="" accept="image/jpeg,image/png,image/gif" /></label>
				</div>
			</div>
			<div class="form-element">
				<label for="smallads_price">{L_DB_PRICE}</label>
				<div class="form-field">
					<label><input type="text" maxlength="10" size="10" id="smallads_price" name="smallads_price" value="{DB_PRICE}" />&nbsp{L_PRICE_UNIT}</label>
				</div>
			</div>
			<div class="form-element">
				<label for="smallads_shipping">{L_DB_SHIPPING}</label>
				<div class="form-field">
					<label><input type="text" maxlength="10" size="10" id="smallads_shipping" name="smallads_shipping" value="{DB_SHIPPING}" />&nbsp{L_SHIPPING_UNIT}</label>
				</div>
			</div>
			<div class="form-element">
				<label for="view_mail">{L_VIEW_MAIL_ENABLED}</label>
				<div class="form-field">
					<label><input type="checkbox" name="view_mail" id="view_mail" # IF C_VIEW_MAIL_CHECKED # checked="checked"# ENDIF #></label>
				</div>
			</div>
			<div class="form-element">
				<label for="view_pm">{L_VIEW_PM_ENABLED}</label>
				<div class="form-field">
					<label><input type="checkbox" name="view_pm" id="view_pm" # IF C_VIEW_PM_CHECKED # checked="checked"# ENDIF #></label>
				</div>
			</div>
			# IF C_MAX_WEEKS #
			<div class="form-element">
				<label for="smallads_max_weeks">{L_DB_MAX_WEEKS}</label>
				<div class="form-field">
					<label><input type="text" size="3" maxlength="2" id="smallads_max_weeks" name="smallads_max_weeks" value="{DB_MAX_WEEKS}" />&nbsp{L_MAX_WEEKS_DEFAULT}</label>
				</div>
			</div>
			# ENDIF #

			# IF C_CAN_APPROVE #
			<div class="form-element">
				<label for="smallads_approved">{L_DB_APPROVED}</label>
				<div class="form-field">
					<label><input type="checkbox" name="smallads_approved" id="smallads_approved" {DB_APPROVED} /></label>
				</div>
			</div>
			# ENDIF #
			<div class="small">
				<p>{DB_CREATED}<br />{DB_UPDATED}</p>
			</div>
		</fieldset>

		# IF C_CONTRIBUTION #
		<fieldset>
			<legend>{L_CONTRIBUTION}</legend>
			<div class="message-helper notice">
				<div class="message-helper-content">{L_CONTRIBUTION_NOTICE}</div>
			</div>
			<div class="form-element-textarea">
				<label for="contribution_counterpart">{L_CONTRIBUTION_COUNTERPART}</label>
				<span class="field-description">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</span>
				{CONTRIBUTION_COUNTERPART_EDITOR}
				<div class="form-field-textarea">
					<textarea rows="20" cols="40" id="contribution_counterpart" name="contribution_counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
				</div>
			</div>
		</fieldset>
		# ENDIF #

		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
			<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
			<button type="reset" value="true">{L_RESET}</button>
			<input type="hidden" name="id" value="{ID}" />
			<input type="hidden" name="token" value="{TOKEN}" />
		</fieldset>
	</form>
	</div>
# ENDIF #