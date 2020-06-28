<script>
<!--
var SmalladsFormFieldCarousel = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

SmalladsFormFieldCarousel.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id, class : 'grouped-inputs'}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_description_' + id, class : 'grouped-element', name : 'field_description_' + id, placeholder : '{@smallads.form.image.description}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_picture_url_' + id, class : 'grouped-element', name : 'field_picture_url_' + id, placeholder : '{@smallads.form.image.url}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : '', class : 'grouped-element', 'aria-label' : '${LangLoader::get_message('files_management', 'main')}', onclick : "window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_picture_url_" + id + "&parse=true&no_path=true', '', 'height=500,width=780,resizable=yes,scrollbars=yes');return false;"}).html('<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>').appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:SmalladsFormFieldCarousel.delete_field('+ this.integer +');', class : 'grouped-element', 'aria-label' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

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

var SmalladsFormFieldCarousel = new SmalladsFormFieldCarousel();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}" class="grouped-inputs">
		<input id="field_description_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_description_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.DESCRIPTION}" placeholder="{@smallads.form.image.description}"/>
		<input id="field_picture_url_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_picture_url_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.PICTURE_URL}" placeholder="{@smallads.form.image.url}" class="slider-url"/>
		<a class="grouped-element" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_picture_url_${escape(ID)}_{fieldelements.ID}&parse=true&no_path=true', '', 'height=500,width=780,resizable=yes,scrollbars=yes');return false;" aria-label="${LangLoader::get_message('files_management', 'main')}" href="#"><i class="fa fa-cloud-upload-alt" aria-hidden="true"></i></a>
		<a class="grouped-element" href="javascript:SmalladsFormFieldCarousel.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:SmalladsFormFieldCarousel.add_field();" id="add-${escape(ID)}" class="field-source-more-value" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a>
