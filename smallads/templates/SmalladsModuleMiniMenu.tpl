{L_INFO}
<br />
# START item #
<fieldset onclick="document.location.href='{PATH_TO_ROOT}/smallads/smallads.php\#smallads_{item.ID}'"
	onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'">
<legend>{item.TYPE}</legend>
# IF item.C_PICTURE #
<img src="{item.PICTURE}" height="50" alt="" />
# ENDIF #
<br />{item.TITLE}
<div class="small">
	<br />{item.PRICE}&nbsp;{L_PRICE_UNIT}
	<br />{item.DATE}
</div>
</fieldset>
# END item #
<a class="small" href="{U_HREF}">{L_ALL_SMALLADS}</a>
