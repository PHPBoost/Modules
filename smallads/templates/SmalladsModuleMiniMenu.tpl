{L_INFO}
<br />
# START item #
<fieldset onclick="document.location.href='{PATH_TO_ROOT}/smallads/smallads.php\#smallads_{item.ID}'" onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'"# IF item.C_NEW_CONTENT #class="new-content"# ENDIF #>
	<legend>{item.TYPE}</legend>
	# IF item.C_PICTURE #<img src="{item.PICTURE}" height="50" alt="" /># ENDIF #
	<div class="smallads-item-title">{item.TITLE}</div>
	<div class="smallads-item-infos small">
		<br />
		<div class="smallads-item-price">{item.PRICE}&nbsp;<span class="smallads-item-unit">{L_PRICE_UNIT}</span></div>
		# IF C_DATE #<div class="smallads-item-date">{item.L_CREATED}{item.DATE_CREATED}</div># ENDIF #
	</div>
</fieldset>
# END item #
<a class="small" href="{U_HREF}">{L_ALL_SMALLADS}</a>
