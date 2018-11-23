<script>
<!--
	function affCache(id)
	{
		if (jQuery('#' + id).is(':hidden')) {
			jQuery('#' + id).show();
		} else {
			jQuery('#' + id).hide();
		}
	}

	function check_form_or(){
		if(document.getElementById('word').value == "")
		{
			alert("{L_ALERT_TEXT_WORD}");
			return false;
		}
		else if(document.getElementById('contents').value == "")
		{
			alert("{L_ALERT_TEXT_DESC}");
			return false;
		}
		return true;
	}

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

	function Doublons(TabInit){
		NvTab= new Array();
		var q=0;
		var LnChaine= TabInit.length;
		 for(x=0;x<LnChaine;x++)
			{
			for(i=0;i<LnChaine;i++)
				{
				if(TabInit[x]==  TabInit[i] && x!=i) TabInit[i]='faux';
				}
			if(TabInit[x]!='faux'){  NvTab[q] = TabInit[x]; q++}
			}
		return NvTab;
	}

	function getParam(name)
	{
		var str_location = String(location);
		if(str_location.search('/dictionary-') != -1 )
		{
			url=String(location).substr(str_location.search('/dictionary-') + 12);
			url=url.substr(0,url.length - 4);
			tab=url.split('-');
			return tab[1] ? tab[1] : 'ALL';
		}
		else
		{
			var start=location.search.indexOf("?"+name+"=" );
			if (start<0) start=location.search.indexOf("&"+name+"=" );
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
	 function check_onchange(i)
	{
		i.value= FormatStr(i.value);
		return true;
	}

	function aff_cache_all(ii,jj)
	{
		var i = ii;
		var j = jj;

		clearTimeout()
		if(j==i)
			{
				document.getElementById('category').value="ALL";
				jQuery('#category').show();
				# START dictionary #
					jQuery('#{dictionary.CAT}_cat_{dictionary.ID}').show();
				# END dictionary #
				return;
			}
	}

	function AffCachWord()
	{
		var i = 0;
		var j = 0;
		clearTimeout()

		# START dictionary #
		if ('{dictionary.CAT}' !='ALL' )
		{
			jQuery('#{dictionary.CAT}_cat_{dictionary.ID}').show();
			document.getElementById("category_list").value=document.getElementById("category_list").value+"-"+{dictionary.CAT};
		}
		else if(jQuery('#{dictionary.CAT}').is(':hidden') && jQuery('#ALL').is(':hidden') && '{dictionary.CAT}' != 'ALL')
		{
			jQuery('#{dictionary.CAT}_cat_{dictionary.ID}').hide();
			tab_cat_list=document.getElementById("category_list").value.split('-');
			tab_cat_list=Doublons(tab_cat_list);
			list_cat="";
			for(i=0;i<tab_cat_list.length;i++)
			{
				if(tab_cat_list[i] != {dictionary.CAT})
				{
					if(i!=0)
					{
						list_cat=list_cat+'-';
					}
					list_cat=list_cat+tab_cat_list[i];
				}
			}
			document.getElementById("category_list").value=list_cat;
		}
		j=j+1;
		if(jQuery('#{dictionary.CAT}').is(':hidden') && jQuery('#ALL').is(':hidden'))
		{
			i=i+1;
		}
		# END dictionary #
		var func = "aff_cache_all("+i+","+j+");";
		setTimeout (func, 2000);
	}

	function affCacheCat(pr5)
	{
		if(pr5==" ")
		{
			var cat = document.getElementById('category').value;
		}
		else
		{
			var cat = pr5;
		}
		# START cat #

			if (cat=="ALL" && document.getElementById('ALL').style.display == "none")
			{
				affCache(document.getElementById('category').value=cat);
				if(jQuery('#{cat.ID}').is(':visible'))
					jQuery('#{cat.ID}').hide();
			}
			else if(cat!="ALL")
			{
				affCache(document.getElementById('category').value=cat);
				jQuery('#ALL').hide();
			}
		# END cat #

		setTimeout ('AffCachWord();', 400);
	}

	function AffCacheCatLancement(TabCat)
	{
		tab_cat_list=TabCat.split(',');
		for(var i=0;i<tab_cat_list.length;i++)
		{
			affCacheCat(tab_cat_list[i]);
		}
	}

	function redirection_letter(letter)
	{
		tab_cat_list1=document.getElementById("category_list").value.split('-');
		document.getElementById("category_list").value=Doublons(tab_cat_list1);
		str_cat=document.getElementById("category_list").value;
		letter=letter.toLowerCase();

		if({REWRITE})
		{
			location.href="{PATH_TO_ROOT}/dictionary/dictionary-"+letter+"-"+str_cat.substring(1)+".php";
		}
		else
		{
			location.href="{PATH_TO_ROOT}/dictionary/dictionary.php?l="+letter+"&cat="+str_cat.substring(1);
		}
	}
 -->
</script>

<section id="module-dictionary">
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div class="content">
	# INCLUDE MSG #

	# IF NOT C_EDIT #
		<div class="dictionary-letter-selector center">
			<div class="dictionary-letter">
				<a href="javascript:redirection_letter('tous');">{L_ALL_DEFINITIONS}</a>
				# START letter #
					<a href="javascript:redirection_letter('{letter.LETTER}');">{letter.LETTER}</a>
				# END letter #
			</div>
			<div class="filter-selector">
				<span><b>{L_CATEGORY} : </b></span>
				# START cat #
					<span id="{cat.ID}" style="display:none;"><a href="javascript:affCacheCat('{cat.ID}');" title="{cat.ID}">{cat.NAME}</a> / </span>
				# END cat #
				<span id="ALL"><a href="javascript:affCacheCat('ALL');" alt="{L_ALL_CAT}">{L_ALL_CAT} &nbsp;<img src="{PATH_TO_ROOT}/dictionary/templates/images/plus.png" alt="{L_ALL_CAT}" /> </a></span>
				<select id ="category" name="category">
					<option value="ALL">{L_ALL_CAT}
					# START cat_list #
						<option value='{cat_list.ID}'>{cat_list.NAME}
					# END cat_list #
				</select>
				<a href="javascript:affCacheCat(' ');" aria-label="Filter"><i class="fa fa-forward" aria-hidden="true" title="Filter"></i></a>
			</div>
		</div>
		<noscript>
			<div class="no-script"><b>{L_NO_SCRIPT}</b></div>
		</noscript>
		# START dictionary #
			<article class="dictionary-word" id="{dictionary.CAT}_cat_{dictionary.ID}" name="{dictionary.CAT}_cat_{dictionary.ID}">
				<header>
					<span class="actions">
						# IF dictionary.EDIT_CODE #
							<a href="{PATH_TO_ROOT}/dictionary/dictionary.php?edit={dictionary.ID_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a>
						# ENDIF #
						# IF dictionary.DEL_CODE #
							<a href="{PATH_TO_ROOT}/dictionary/dictionary.php?del={dictionary.ID_DEL}&token={TOKEN}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
						# ENDIF #
					</span>
					<h6 title="{SWITCH_DEF}">
						<a href="javascript:affCacheCat('{dictionary.CAT}');">{dictionary.CAT_IMG}</a> <a href="javascript:affCache('{dictionary.ID}');">{dictionary.PROPER_NAME}</a>
					</h6>
				</header>
				<div id="{dictionary.ID}" style="# IF dictionary.C_AFF #display:block;# ELSE #display:none;# ENDIF #" class="conten dictionary-definition">
					{dictionary.DESC}
				</div>
				<noscript>
					<div id="{dictionary.ID}"  class="dictionary-definition">
						{dictionary.DESC}
					</div>
				</noscript>
			</article>
		# END dictionary #
		 <script>
		<!--
			cat=getParam('cat');
			AffCacheCatLancement(cat);
		-->
		</script>
		<input type="hidden" value="" id="category_list"name="category_list" />
	# ENDIF #
	# IF C_EDIT #
		# IF C_ARTICLES_PREVIEW #
			<span class="text-strong">{L_PREVISUALIATION}</span>
			<div class="spacer">&nbsp;</div>
			<div class="dictionary-word">
				<div>
					<span class="actions">
						<a href="" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a>
						<a href="" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
					</span>
					<h6>
						<a href=""><i class="fa fa-folder" aria-hidden="true"></i></a> <a href="">{WORD}</a>
					</h6>
				</div>
				<div class="dictionary-definition">
					{CONTENTS_PRW}
				</div>
			</div>
			<div class="spacer">&nbsp;</div>
		# ENDIF #
		<form action="dictionary.php" name="form" method="post" onsubmit="return check_form_or();"  class="fieldset-content">
			<fieldset>
				<legend>{L_ADD_DICTIONARY}</legend>
				<div class="form-element">
					<label for="word">* {L_WORD}</label>
					<div class="form-field">
						<label><input type="text" maxlength="100" id="word" name="word" value="{WORD}" onchange="check_onchange(this);" /></label>
					</div>
				</div>
				<div class="form-element">
					<label for="category">{L_CATEGORY}</label>
					<div class="form-field">
						<label>
							<select id ="category_add" name='category_add' style="width:150px;">
								<option selected="selected" value="{ID_CAT_SELECT}">{NAME_CAT_SELECT}
								# START cat_list_add #
									<option value='{cat_list_add.VALUE}'>{cat_list_add.NAME}
								# END cat_list_add #
							</select>
						</label>
					</div>
				</div>
				<div class="form-element-textarea">
					<label for="contents">* {L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<div class="form-field-textarea">
						<textarea type="text" rows="20" cols="50" id="contents" name="contents">{CONTENTS}</textarea>
					</div>
				</div>
			</fieldset>
			# IF C_CONTRIBUTION #
			<fieldset>
				<legend>{L_CONTRIBUTION}</legend>
				<div class="message-helper notice">
					<i class="fa fa-notice" aria-hidden="true"></i>
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
				<legend>{L_VALIDATION}</legend>
					<input type="hidden" value="{ID}" name="dictionary_id" />
					<button type="submit" id="valid" name="valid" value="true">{L_SUBMIT}</button>
					<button type="submit" name="previs" value="true">{L_PREVIS}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
