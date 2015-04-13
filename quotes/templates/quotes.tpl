# IF C_LIST #
	<section>
		<header>
			<h1>{L_CAT_NAME}</h1>
		</header>
		<div class="content">
			# IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #
		# IF C_DESCRIPTION #
			{DESCRIPTION}
			<hr style="margin-top:15px;" />
		# ENDIF #
		# IF C_SUB_CATS #
			# START list_cats #
				<div style="float:left;width:{list_cats.WIDTH}%;text-align:center;margin:10px 0px;">
					# IF list_cats.C_CAT_IMG #
						<a href="{list_cats.U_CAT}" title="{list_cats.IMG_NAME}"><img src="{list_cats.SRC}" alt="{list_cats.IMG_NAME}" /></a>
						<br />
					# ENDIF #
					<a href="{list_cats.U_CAT}">{list_cats.NAME}</a>
					<br />
					<span class="small">{list_cats.DESC}</span> 
					<br />
					# IF C_ADMIN #
					<a href="{list_cats.U_ADMIN_CAT}" class="fa fa-edit" title="${LangLoader::get_message('edit', 'main')}"></a>
					# ENDIF #
					<span class="small">{list_cats.L_NBR_QUOTES}</span> 
				</div>
			# END list_cats #
			<div class="spacer">&nbsp;</div>
			<hr style="margin-bottom:5px;" />
		# ENDIF #
		
		# START quotes #
			<article class="block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h1>
						<span class="actions">
							# IF quotes.C_EDIT #
							<a href="../quotes/quotes{quotes.URL_EDIT}&token={TOKEN}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
							# ENDIF #
							# IF quotes.C_DELETE #
							<a href="../quotes/quotes{quotes.URL_DELETE}&token={TOKEN}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
							# ENDIF #
						</span>
					</h1>
				</header>
				<div class="content">
					<span class="smaller">{quotes.DATE} - \#{quotes.ID} - {L_IN_MINI}&nbsp;? <strong>{quotes.IN_MINI}</strong></span>
					<div class="spacer">&nbsp;</div>
					<p itemprop="text">
					{quotes.CONTENTS}
					</p>
					<strong>{quotes.AUTHOR}</strong>
				</div>
				<footer></footer>
			</article>
		# END quotes #
		
		# IF C_NO_ITEMS #
			<div class="center">
				{L_NO_ITEMS}
			</div>
		# ENDIF #
		</div>
		<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
	</section>
# ENDIF #

# IF C_EDIT #
	<script>
	<!--
	function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	}
	
	function check_item(i)
	{
		i.value = trim(i.value);
		if(i.value == "") {
			alert("{L_ALERT_TEXT}");
			i.focus();
			return false;
		}
		return true;
	}
	
	function check_form(o){
		if (!check_item(o.quotes_contents)) return false;
		if (!check_item(o.quotes_author)) return false;
		return true;
	}
	-->
	</script>
	<div id="add"></div>
	<form action="{PATH_TO_ROOT}/quotes/quotes.php" method="post" onsubmit="return check_form(this);" class="fieldset-content">
		<p clas="center">{L_REQUIRE}</p>
		<fieldset>
			<legend>{L_ADD_QUOTE}{L_UPDATE_QUOTE}</legend>
			<div class="form-element">
				<label for="idcat">{L_CATEGORY}</label>
				
				<div class="form-field">
					<label>{CATEGORIES_TREE}</label>
				</div>
			</div>
			<div class="form-element">
				<label>* {L_CONTENTS}</label>
				<div class="form-field">
					<label><textarea rows="6" cols="25" id="quotes_contents" name="quotes_contents">{CONTENTS}</textarea></label>
				</div>
			</div>
			<div class="form-element">
				<label>* {L_AUTHOR}</label>
				<div class="form-field">
					<label><input type="text" size="25" maxlength="25" id="quotes_author" name="quotes_author" value="{AUTHOR}" /></label>
				</div>
			</div>
			<div class="form-element">
				<label>{L_IN_MINI}</label>
				<div class="form-field">
					<label><input type="checkbox" name="quotes_in_mini" id="quotes_in_mini" {IN_MINI} /></label>
				</div>
			</div>
			# IF NOT C_APPROVED #
			<div class="form-element">
				<label>{L_APPROVED}</label>
				<div class="form-field">
					<label><input type="checkbox" name="quotes_approved" id="quotes_approved" {APPROVED} /></label>
				</div>
			</div>
			# ENDIF #
		</fieldset>
		
		# IF C_CONTRIBUTION #
		<fieldset>
			<legend>{L_CONTRIBUTION}</legend>
			<div class="message-helper notice">
				<i class="fa fa-notice"></i>
				<div class="message-helper-content">{L_CONTRIBUTION_NOTICE}</div>
			</div>
			<div class="form-element-textarea">
				<label for="contribution_counterpart">{L_CONTRIBUTION_COUNTERPART}</label>
				<span class="field-description">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</span>
				{CONTRIBUTION_COUNTERPART_EDITOR}
				<label><textarea rows="20" cols="40" id="contribution_counterpart" name="contribution_counterpart">{CONTRIBUTION_COUNTERPART}</textarea></label>
			</div>
		</fieldset>
		# ENDIF #
		
		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
			<button type="reset" name="reset" value="true">{L_RESET}</button>
			<input type="hidden" name="id" value="{ID}" />
			<input type="hidden" name="token" value="{TOKEN}" />
		</fieldset>
	</form>
# ENDIF #