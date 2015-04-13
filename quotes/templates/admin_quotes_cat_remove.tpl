	<div id="admin-contents">
		<form action="{U_FORM_TARGET}" method="post" onsubmit="return check_form();" class="fieldset-content">
			<fieldset>
				<legend>{L_REMOVING_CATEGORY}</legend>
				<p>{L_EXPLAIN_REMOVING}</p>
				<div class="form-element">
					<label>
						<input type="radio" name="action" value="delete" /> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
				</div>
				<div class="form-element">
					<label>
						<input type="radio" name="action" value="move" checked="checked" /> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					{CATEGORY_TREE}
				</div>
			</fieldset>
			
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="cat_to_del" value="{IDCAT}" />
				<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>
		</form>
	</div>