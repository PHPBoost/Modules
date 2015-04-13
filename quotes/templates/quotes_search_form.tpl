<div class="form-element">
	<label for="QuotesWhere">{L_WHERE}</label>
	<div class="form-field">
		<label>
		<select id="QuotesWhere" name="QuotesWhere">
			<option value="all" {IS_ALL_SELECTED}>{L_AUTHOR} / {L_CONTENTS}</option>
			<option value="contents" {IS_CONTENTS_SELECTED}>{L_CONTENTS}</option>
			<option value="author" {IS_AUTHOR_SELECTED}>{L_AUTHOR}</option>
		</select>
		</label>
	</div>
</div>