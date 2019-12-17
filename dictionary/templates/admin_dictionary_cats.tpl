<script>
	<!--
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
			alert(i.name + " - {L_VAL_INC}");
			i.focus();
			return false;
		}
	}

	function check_form_conf(o)
	{
		if (!alert_item(o.name_cat)) return false;
		return true;
	}
-->
</script>

<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {L_DICTIONARY_MANAGEMENT}
	</a>
	<ul>
		<li>
			<a href="index.php" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php" class="quick-link">{L_DICTIONARY_CATS}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1" class="quick-link">{L_DICTIONARY_CATS_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php" class="quick-link">{L_LIST_DEF}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1" class="quick-link">{L_DICTIONARY_ADD}</a>
		</li>
		<li>
			<a href="${relative_url(DictionaryUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MSG #
	# START add #
		<form action="admin_dictionary_cats.php?add=1" method="post" enctype="multipart/form-data" onsubmit="return check_form_conf(this);" class="fieldset-content">
			<fieldset>
				<legend>{add.L_CATEGORY}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="title">* {add.L_NAME_CAT}</label>
						<div class="form-field">
							<label><input type="text" size="25" id="name_cat" name="name_cat" value="{add.NAME_CAT}" onchange="check_onchange(this);" /></label>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>{add.L_IMAGE}</legend>
				<div class="fieldset-inset">
					<div class="form-element top-field">
						<label>{add.L_IMAGE_A}</label>
						<div class="form-field">
							<label>{add.IMAGES}</label>
						</div>
					</div>
					<div class="form-element">
						<label>{add.L_IMAGE_UP}</label>
						<div class="form-field">
							<label>
								{add.L_WEIGHT_MAX}: 20 ko
								<br>
								{add.L_HEIGHT_MAX}: 16 px
								<br>
								{add.L_WIDTH_MAX}: 16 px
							</label>
						</div>
					</div>
					<div class="form-element">
						<label for="images">{add.L_IMAGE_UP_ONE}
							<span class="field-description">{add.L_IMAGE_SERV}</span>
						</label>
						<div class="form-field">
							<label>
								<input type="file" name="images" id="images" size="30" class="file" />
								<input type="hidden" name="max_file_size" value="2000000" />
							</label>
						</div>
					</div>
					<div class="form-element">
						<label for="image">{add.L_IMAGE_LINK}
							<span class="field-description">{add.L_IMAGE_ADR} (./dictionary/templates/images/)</span>
						</label>
						<div class="form-field">
							<label><input maxlength="40" size="40" name="image" id="image" type="text" /></label>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{add.L_VALIDATION}</legend>
				<button class="button submit" type="submit" name="valid" value="true">{add.L_SUBMIT}</button>
				<button type="reset" class="button reset" value="true">{add.L_RESET}</button>
				<input type="hidden" value="{add.ID_CAT}" name="id_cat" />
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	# END add #
	# IF LIST_CAT #
		<form action="admin_dictionary_cats.php" method="post" class="fieldset-content">
			<table class="table">
				<thead>
					<tr>
						<th colspan="3">{L_GESTION_CAT}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							# START cat #
								<div class="admin-cat-list">
									<span class="float-left">
										{cat.IMAGES}&nbsp;{cat.NAME}
									</span>
									<span class="float-right">
										<a href="admin_dictionary_cats.php?add=1&id={cat.ID_CAT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
										<a href="admin_dictionary_cats.php?del=1&id={cat.ID_CAT}&token={TOKEN}" aria-label="{ALERT_DEL}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
									</span>
								</div>
							# END cat #
						</td>
					</tr>
				</tbody>
					# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="3">
							# INCLUDE PAGINATION #
						</th>
					</tr>
				</tfoot>
				# ENDIF #
			</table>
		</form>
	# ENDIF #
	# IF DEL_CAT_NOEMPTY #
		<form action="admin_dictionary_cats.php" method="post" class="fieldset-content">
			<fieldset>
				<legend>{L_DEL_CAT}</legend>
					<p>{L_DEL_TEXT} <strong>{L_WARNING_DEL}</strong></p>
				<div class="form-field-radio">
					<input id="action" type="radio" name="action" value="delete">
					<label for="action"></label>
				</div>
				<span class="form-field-radio-span">{L_DEL_CAT_DEF}</span>
				<br><br>
				<div class="form-field-radio">
					<input id="action2" type="radio" name="action" value="move" checked="checked">
					<label for="action2"></label>
				</div>
				<span class="form-field-radio-span">{L_MOVE} :</span>
				<select id="categorie_move" name="categorie_move">
					# START cat_list #
					<option value="{cat_list.ID}">{cat_list.NAME}
					# END cat_list #
				</select>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>${LangLoader::get_message('delete', 'common')}</legend>
				<button class="button submit" type="submit" name="submit" value="true">${LangLoader::get_message('delete', 'common')}</button>
				<input name="id_del_a" value="{ID_DEL}" type="hidden">
				<input name="cat_to_del" value="1" type="hidden">
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	# ENDIF #
</div>
