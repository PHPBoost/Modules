<script>
<!--
var SmalladsFormFieldSmalladType = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

SmalladsFormFieldSmalladType.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, class : 'field-large', placeholder : "{@smallads.type.placeholder}"}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:SmalladsFormFieldSmalladType.delete_field('+ this.integer +');', 'aria-label' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-delete" aria-hidden="true" title="' + ${escapejs(LangLoader::get_message('delete', 'common'))} + '"></i>').appendTo('#' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	}
};

var SmalladsFormFieldSmalladType = new SmalladsFormFieldSmalladType();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" class="field-large" value="{fieldelements.NAME}" placeholder="{@smallads.type.placeholder}"/>
			<a href="javascript:SmalladsFormFieldSmalladType.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:SmalladsFormFieldSmalladType.add_field();" id="add-${escape(ID)}" class="" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true" title="${LangLoader::get_message('add', 'common')}"></i></a>
