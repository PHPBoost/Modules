<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{L_TITLE}</h5>
	</div>
	<div class="module-mini-contents">
		{L_INFO}
		<br />
		# START item #
		# IF C_LIST_ACCES #
		<fieldset onclick="document.location.href='{PATH_TO_ROOT}/smallads/smallads.php\#smallads_{item.ID}'"
			onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'">
		# ELSE #
		<fieldset>
		# ENDIF #
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
		# IF C_LIST_ACCES #
		<a class="small" href="{U_HREF}">{L_ALL_SMALLADS}</a>
		# ENDIF #
	</div>
	<div class="module-mini-bottom"></div>
</div>
