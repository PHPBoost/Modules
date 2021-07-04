<script>
	function str_replace2(SRs, SRt, SRu) {
		  SRRi = SRs.indexOf(SRt);
		  SRRr = '';
		  if (SRRi == -1) return SRs;
		  SRRr += SRs.substring(0,SRRi) + SRu;
		  if ( SRRi + SRt.length < SRs.length)
		    SRRr += str_replace2(SRs.substring(SRRi + SRt.length, SRs.length), SRt, SRu);
		  return SRRr;
	}

	 function FormatStr(Str){
		var replace = new Array("'", '"', ".",",",'\\','/','^',':');
		Str=str_replace2(Str,replace[0],"");
		Str=str_replace2(Str,replace[1],"");
		Str=str_replace2(Str,replace[2],"");
		Str=str_replace2(Str,replace[3],"");
		Str=str_replace2(Str,replace[4],"");
		Str=str_replace2(Str,replace[5],"");
		Str=str_replace2(Str,replace[6],"");
		Str=str_replace2(Str,replace[7],"");

	    StrNewStr="";
		 for(i=0;i<=Str.length;i++){
			 StrChar=Str.substring(i,i+1);
			 if(StrChar!=" " || Str.substring(i-1,i)!=" "){
				StrNewStr=StrNewStr+StrChar;
			 }
		 }
		 j=1;
		 for(i=0;i<=Str.length;i++){
			StrChar=Str.substring(i,i+1);
			if(StrChar == " "){
				j++;
			}
		 }
		 if(i==j){
			StrNewStr='';
		 }
		 return StrNewStr;
	 }


	function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	}

	function check_item(i)
	{
		i.value = trim(i.value);
		if(i.value == "") {
			return false;
		}
		return true;
	}

	function check_onchange(i)
	{
		i.value= FormatStr(i.value);
		return true;
	}

	function alert_item(i)
	{
		if (check_item(i)) {
			return true;
		} else {
			alert(i.name + " - {@warning.title}");
			i.focus();
			return false;
		}
	}

	function check_form_conf(o)
	{
		if (!alert_item(o.name_cat)) return false;
		return true;
	}

	function img_change(url)
	{
		if( document.images && url != '' )
			document.getElementById('cat_img_change').innerHTML = '<img src="{PATH_TO_ROOT}/dictionary/templates/images/' + url + '" alt="{@form.thumbnail}" />';
		else
			document.getElementById('cat_img_change').innerHTML = '<span aria-label="{@form.thumbnail}"></span><i class="fa fa-folder" aria-hidden="true"></i>';
	}
</script>

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@form.configuration}
	</a>
	<ul>
		<li>
			<a href="index.php" class="quick-link">{@form.home}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php" class="quick-link">{@category.categories.management}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1" class="quick-link">{@category.add}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php" class="quick-link">{@dictionary.items.management}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1" class="quick-link">{@dictionary.add.item}</a>
		</li>
		<li>
			<a href="${relative_url(DictionaryUrlBuilder::configuration())}" class="quick-link">{@form.configuration}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MESSAGE_HELPER #
	# START add #
		<form action="admin_dictionary_cats.php?add=1" method="post" enctype="multipart/form-data" onsubmit="return check_form_conf(this);" class="fieldset-content">
			<fieldset>
				<legend>{@category.add}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="name_cat">* {@form.name}</label>
						<div class="form-field form-field-text">
							<input type="text" size="25" id="name_cat" name="name_cat" value="{add.CATEGORY_NAME}" onchange="check_onchange(this);" />
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>{@common.image}</legend>
				<div class="fieldset-inset">
					<div class="form-element top-field">
						<label>{@dictionary.current.image}</label>
						<div class="form-field" id="cat_img_change">
							# IF add.C_IS_PICTURE #
								<img src="{add.U_CATEGORY_IMAGE}" alt="{@form.thumbnail}" />
							# ELSE #
								<span aria-label="{@form.thumbnail}"></span><i class="fa fa-folder" aria-hidden="true"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{@dictionary.auth.images}</label>
						<div class="form-field">
							<span class="d-block">{@dictionary.max.weight}</span>
							<span class="d-block">{@dictionary.max.height}</span>
							<span class="d-block">{@dictionary.max.width}</span>
							<span class="d-block">{@dictionary.auth.files}</span>
						</div>
					</div>
					<div class="form-element top-field">
						<label for="images">{@dictionary.upload.file}
							<span class="field-description">{@dictionary.upload.link}</span>
						</label>
						<div class="form-field">
							<input type="file" name="images" id="images" size="30" class="file" />
							<input type="hidden" name="max_file_size" value="2000000" />
						</div>
					</div>
					<div class="form-element top-field">
						<label for="image">{@dictionary.server.link}
							<span class="field-description">{@dictionary.upload.link}</span>
						</label>
						<div class="form-field">
							<select name="image" id="image" onchange="img_change(this.options[selectedIndex].value)">
								{add.CATEGORY_IMAGES_LIST}
							</select>
							<!-- <input maxlength="40" size="40" name="image" id="image" type="text" /> -->
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<button class="button submit" type="submit" name="valid" value="true">{@form.submit}</button>
				<button class="button reset-button" type="reset" value="true">{@form.reset}</button>
				<input type="hidden" value="{add.CATEGORY_ID}" name="id_cat" />
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	# END add #
	# IF CATEGORIES_LIST #
		<form action="admin_dictionary_cats.php" method="post" class="fieldset-content">
			<table class="table table-no-header">
				<caption>{@category.categories.management}</caption>
				<thead>
					<tr>
						<th>{@common.name}</th>
						<th><span class="sr-only">{@common.moderation}</span></th>
					</tr>
				</thead>
				<tbody>
					# START cat #
						<tr>
							<td class="align-left">
								{cat.CATEGORY_IMAGE}&nbsp;{cat.CATEGORY_NAME}
							</td>
							<td class="col-small controls">
								<a href="admin_dictionary_cats.php?add=1&id={cat.CATEGORY_ID}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
								<a href="admin_dictionary_cats.php?del=1&id={cat.CATEGORY_ID}&token={TOKEN}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
							</td>
						</tr>
					# END cat #
				</tbody>
				# IF C_PAGINATION #
					<tfoot>
						<tr>
							<th colspan="2">
								# INCLUDE PAGINATION #
							</th>
						</tr>
					</tfoot>
				# ENDIF #
			</table>
		</form>
	# ENDIF #
	# IF C_DELETE_CATEGORY #
		<form action="admin_dictionary_cats.php" method="post" class="fieldset-content">
			<fieldset>
				<legend>{@category.delete}</legend>
				<div class="fielset-inset">
					<div class="fieldset-description">
						<div class="message-helper bgc notice">{@H|category.delete.description}</div>
					</div>
					<div class="form-element custom-radio half-field">
						<label for="action">{@category.content.management} {CATEGORY_NAME}</label>
						<div class="form-field form-field-radio-button">
							<div class="form-field-radio">
								<label class="radio" for="action1">
									<input id="action1" type="radio" name="action" value="delete">
									<span>{@category.delete.all.content}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="action2">
									<input id="action2" type="radio" name="action" value="move" checked="checked">
									<span>{@category.move.to}</span>
								</label>
							</div>
							<select id="category-move" name="category-move">
								# START cat_list #
									<option value="{cat_list.CATEGORY_ID}">{cat_list.CATEGORY_NAME}
								# END cat_list #
							</select>
						</div>
					</div>
				</div>
			</fieldset>
			<script>
				$('[name="action"]').on('click', function(){
					if($(this).val() == 'move')
						$('#category-move').removeClass('hidden');
					else
						$('#category-move').addClass('hidden');
				});
			</script>
			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<button class="button submit" type="submit" name="submit" value="true">{@form.submit}</button>
				<input name="id_del_a" value="{CATEGORY_ID}" type="hidden">
				<input name="cat_to_del" value="1" type="hidden">
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	# ENDIF #
</div>
