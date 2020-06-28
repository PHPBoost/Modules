<script>
<!--
var SmalladsFormFieldBrand = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

SmalladsFormFieldBrand.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id, class : 'grouped-inputs'}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, class : 'grouped-element', placeholder : "{@smallads.brand.placeholder}"}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:SmalladsFormFieldBrand.delete_field('+ this.integer +');', class : 'grouped-element', 'aria-label' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i> <span class="sr-only">' + ${escapejs(LangLoader::get_message('delete', 'common'))} + '</span>').appendTo('#' + id);

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

var SmalladsFormFieldBrand = new SmalladsFormFieldBrand();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}" class="grouped-inputs">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" class="grouped-element" value="{fieldelements.NAME}" placeholder="{@smallads.brand.placeholder}"/>
			<a href="javascript:SmalladsFormFieldBrand.delete_field({fieldelements.ID});" data-confirmation="delete-element" class="grouped-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i><span class="sr-only">${escapejs(LangLoader::get_message('delete', 'common'))}</span></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:SmalladsFormFieldBrand.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="${LangLoader::get_message('add', 'common')}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i><span class="sr-only">${escapejs(LangLoader::get_message('add', 'common'))}</span></a>
