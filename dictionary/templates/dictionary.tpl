<script>
	function toggleDisplay(id)
	{
		if (jQuery('#' + id).is(':hidden')) {
			jQuery('#' + id).show();
		} else {
			jQuery('#' + id).hide();
		}
	}

	function check_form_or()
	{
		if(document.getElementById('word').value == "")
		{
			alert("{@warning.title}");
			return false;
		}
		else if(document.getElementById('description').value == "")
		{
			alert("{@warning.text}");
			return false;
		}
		return true;
	}

	function Duplicated(TabInit)
	{
		newTab = new Array();
		var q = 0;
		var LnChaine = TabInit.length;
		for(x = 0; x < LnChaine; x++)
		{
			for(i = 0; i < LnChaine; i++)
			{
				if(TabInit[x] == TabInit[i] && x != i)
					TabInit[i] = 'faux';
			}
			if(TabInit[x] != 'faux') {
				newTab[q] = TabInit[x];
				q++;
			}
		}
		return newTab;
	}

	function getParam(name)
	{
		var str_location = String(location);
		if(str_location.search('/dictionary-') != -1 )
		{
			url = String(location).substr(str_location.search('/dictionary-') + 12);
			url = url.substr(0, url.length - 4);
			tab = url.split('-');
			return tab[1] ? tab[1] : 'ALL';
		}
		else
		{
			var start=location.search.indexOf("?" + name + "=" );
			if (start<0) start=location.search.indexOf("&" + name + "=" );
			if (start<0) return '';
			start += name.length+2;
			var end=location.search.indexOf("&",start)-1;
			if (end<0) end=location.search.length;
			var result='';
			for(var i=start;i<=end;i++)
			{
				var c=location.search.charAt(i);
				result=result+(c=='+'?' ':c);
			}
			return unescape(result);
		}
	}

	function toggleAll(ii,jj)
	{
		var i = ii;
		var j = jj;

		clearTimeout()
		if(j == i)
		{
			document.getElementById('category').value="ALL";
			jQuery('#category').show();
			# START items #
				id = '{items.CATEGORY_ID}_cat_{items.ITEM_ID}_{items.REWRITED_NAME}';
				jQuery('#' + id).show();
			# END items #
			return;
		}
	}

	function toggleCategory(pr5)
	{
		if(pr5 == " ")
			var category = document.getElementById('category-selector').value;
		else
			var category = pr5;

		# START cat #
			if (category == "ALL" && document.getElementById('ALL').style.display == "none")
			{
				toggleDisplay(category);
				cat_id = '{cat.CATEGORY_ID}';
				if(jQuery('#' + cat_id).is(':visible'))
					jQuery('#' + cat_id).hide();
			}
			else if(category != "ALL" && category != "")
			{
				toggleDisplay(category);
				jQuery('#ALL').hide();
			}
		# END cat #

		if (category != "ALL")
		{
			jQuery("article").hide();
			categories_number_displayed = 0;
			# START cat #
				cat_id = '{cat.CATEGORY_ID}';
				if(jQuery('#' + cat_id).is(':visible')) {
					jQuery("article[id^=" + cat_id + "]").show();
					categories_number_displayed++;
				}
			# END cat #
			if(categories_number_displayed == 0) {
				jQuery("article").show();
				jQuery('#ALL').show();
			}
			else
				jQuery('#ALL').hide();
		}
		else
		{
			jQuery("article").show();
			jQuery('#ALL').show();
		}
	}

	function InitCategoriesDisplay(TabCat)
	{
		tab_cat_list = TabCat.split(',');
		for(var i = 0; i < tab_cat_list.length; i++)
		{
			toggleCategory(tab_cat_list[i]);
		}
	}

	function redirection_letter(letter)
	{
		tab_cat_list1 = document.getElementById("category_list").value.split('-');
		document.getElementById("category_list").value = Duplicated(tab_cat_list1);
		str_cat = document.getElementById("category_list").value;
		letter = letter.toLowerCase();

		if (letter == '')
		{
			location.href="{PATH_TO_ROOT}/dictionary/dictionary.php";
		}
		else
		{
			if({REWRITE})
			{
				location.href="{PATH_TO_ROOT}/dictionary/dictionary-" + letter + "-" + str_cat.substring(1) + ".php";
			}
			else
			{
				location.href="{PATH_TO_ROOT}/dictionary/dictionary.php?l=" + letter + "&cat=" + str_cat.substring(1);
			}
		}
	}

	// Current letter
	jQuery('.letters-list').each(function(){
		var currentLetter = jQuery(this).text().toLowerCase();
		if (window.location.href.indexOf('-' + currentLetter + '-') > -1) {
			jQuery(this).addClass('submit').attr('aria-label', '{@common.pagination.current}');
			jQuery('.all-letters').removeClass('submit');
		}
	});
</script>

# INCLUDE MESSAGE_HELPER #

# IF NOT C_EDIT #
	<section id="module-dictionary" class="several-items">
		<header class="section-header">
			<h1>{@dictionary.module.title}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content dictionary-letter-selector align-center">
					<div class="dictionary-letter">
						<a class="all-letters submit offload" href="javascript:redirection_letter('');">${TextHelper::strtoupper(@common.all.alt)}</a>
						# START letter #
							<a class="letters-list offload" href="javascript:redirection_letter('{letter.LETTER}');">{letter.LETTER}</a>
						# END letter #
					</div>
					<div class="grouped-inputs">
						<span class="grouped-element">{@dictionary.filter.by.category}</span>
						<select id="category-selector" name="category" class="grouped-element">
							<option value="ALL">{@common.all.alt}
							# START cat_list #
								<option value='{cat_list.CATEGORY_ID}'>{cat_list.CATEGORY_NAME}
							# END cat_list #
						</select>
						<a class="grouped-element button" href="javascript:toggleCategory(' ');">{@form.apply}</a>
					</div>
					<div class="cat-selector">
						{@common.filters} :
						# START cat #
							<span id="{cat.CATEGORY_ID}" style="display:none;" aria-label="{@dictionary.remove.filter}"><a href="javascript:toggleCategory('{cat.CATEGORY_ID}');" class="dictionary-selected-cat">{cat.CATEGORY_NAME}</a></span>
						# END cat #
						<span id="ALL"><a href="javascript:toggleCategory('ALL');">{@common.all.alt}</a></span>
					</div>
				</div>
				<noscript>
					<div class="message-helper bgc warning no-script">{@common.no.script}</div>
				</noscript>
			</div>
		</div>
		<div class="sub-section">
			<div class="content-container">
				# IF C_RESULTS #<p class="content align-center small">{@dictionary.switch.content}</p># ENDIF #
				<div class="content">
					# START items #
						<div id="{items.ITEM_ID}-definition"></div>
						<article class="items-item category-{items.CATEGORY_ID}" id="{items.CATEGORY_ID}_cat_{items.ITEM_ID}_{items.REWRITED_NAME}" name="{items.CATEGORY_ID}_cat_{items.ITEM_ID}_{items.REWRITED_NAME}">
							<header>
								<h2>
									<a href="javascript:toggleDisplay('{items.ITEM_ID}_{items.REWRITED_NAME}');">{items.CATEGORY_ICON} {items.WORD}</a>
								</h2>
							</header>
							<div id="{items.ITEM_ID}_{items.REWRITED_NAME}" style="display:none;" class="dictionary-definition">
								# IF items.C_CONTROLS #
									<div class="controls align-right">
										# IF items.C_EDIT #
											<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE}&token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
										# ENDIF #
									</div>
								# ENDIF #
								{items.DEFINITION}
							</div>
							<noscript>
								<div id="{items.ITEM_ID}"  class="items-definition">
									# IF items.C_CONTROLS #
										<div class="controls align-right">
											# IF items.C_EDIT #
												<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE}&token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</div>
									# ENDIF #
									{items.DEFINITION}
								</div>
							</noscript>
						</article>
						<script>
							jQuery(document).ready(function() {
								if ({items.C_DISPLAY} && '{items.ITEM_ID}' == '{C_ITEMS}')
									toggleDisplay('{items.ITEM_ID}');
							});
						</script>
					# END items #

				</div>
				<script>
					cat = getParam('cat');
					InitCategoriesDisplay(cat);
				</script>
				<input type="hidden" value="" id="category_list" name="category_list" />
				# IF NOT C_RESULTS #
					<div class="content align-center">
						# IF C_ITEMS #
							<span class="message-helper bgc notice">{L_NO_WORD_LETTER}</span>
						# ELSE #
							<span class="message-helper bgc notice">{@common.no.item.now}</span>
						# ENDIF #
					</div>
				# ENDIF #
			</div>
		</div>
		<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
	</section>
# ELSE #
	<section id="module-dictionary">
		<header class="section-header">
			<div class="controls align-right">{@dictionary.module.title}</div>
			# IF C_ITEM_PREVIEW #<h2># ELSE #<h1># ENDIF ## IF C_ADD_ITEM #{@dictionary.add.item}# ELSE #{@dictionary.edit.item}# ENDIF ## IF C_ITEM_PREVIEW #</h2># ELSE #</h1># ENDIF #
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="dictionary.php" name="form" method="post" onsubmit="return check_form_or();"  class="fieldset-content">
						<fieldset>
							<legend>{@form.parameters}</legend>
							<div class="form-element">
								<label for="word">* {@dictionary.item}</label>
								<div class="form-field">
									<label><input type="text" id="word" name="word" value="{WORD}" /></label>
								</div>
							</div>
							<div class="form-element">
								<label for="category">{@form.category}</label>
								<div class="form-field">
									<label>
										<select id ="category_add" name="category_add">
											<option selected="selected" value="{CATEGORY_ID}">{CATEGORY_NAME}</option>
											# START cat_list_add #
												<option value="{cat_list_add.VALUE}">{cat_list_add.NAME}</option>
											# END cat_list_add #
										</select>
									</label>
								</div>
							</div>
							<div class="form-element form-element-textarea">
								<label for="description">* {@form.content}</label>
								<div class="form-field form-field-textarea bbcode-sidebar">
									{KERNEL_EDITOR}
									<textarea type="text" class="auto-resize" id="description" name="description">{CONTENT}</textarea>
								</div>
								<button class="button preview-button" type="submit" id="preview" name="preview" value="true">{@form.preview}</button>
							</div>
						</fieldset>
						# IF C_CONTRIBUTION #
							<fieldset>
								<legend>{@contribution.contribution}</legend>
								<div class="message-helper bgc warning">{@H|contribution.warning}</div>
								<div class="form-element form-element-textarea">
									<label for="contribution_counterpart">{@H|contribution.description}</label>
									<span class="field-description">{@H|contribution.description.clue}</span>
									<div class="form-field form-field-textarea bbcode-sidebar">
										{CONTRIBUTION_EDITOR}
										<textarea class="auto-resize" id="contribution_counterpart" name="contribution_counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
									</div>
								</div>
							</fieldset>
						# ENDIF #
						# IF C_ITEM_PREVIEW #
							<div class="auto-resize xmlhttprequest-preview">
								<header><h1><a href="#">{WORD}</a></h1></header>
								<div class="sub-section">
									<div class="content-container">
										<div class="content">
											{CONTENT_PREVIEW}
										</div>
									</div>
								</div>
							</div>
						# ENDIF #
						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
								<button class="button submit" type="submit" id="valid" name="valid" value="true">{@form.submit}</button>
								<input type="hidden" value="{ITEM_ID}" name="dictionary_id" />
								<input type="hidden" name="token" value="{TOKEN}" />
								<button class="button reset-button" type="reset" value="true">{@form.reset}</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #
