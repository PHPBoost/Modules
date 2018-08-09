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

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_description_' + id, name : 'field_description_' + id, placeholder : '{@smallads.form.image.description}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_picture_url_' + id, name : 'field_picture_url_' + id, class : 'slider-url', placeholder : '{@smallads.form.image.url}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : '', title : '${LangLoader::get_message('files_management', 'main')}', class : 'fa fa-cloud-upload fa-2x', onclick : "window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_url_" + id + "&parse=true&no_path=true', '', 'height=500,width=780,resizable=yes,scrollbars=yes');return false;"}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:SmalladsFormFieldCarousel.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

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
	<div id="${escape(ID)}_{fieldelements.ID}">
		<input type="text" name="field_description_${escape(ID)}_{fieldelements.ID}" id="field_description_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.DESCRIPTION}" placeholder="{@smallads.form.image.description}"/>
		<input type="text" name="field_picture_url_${escape(ID)}_{fieldelements.ID}" id="field_picture_url_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.PICTURE_URL}" placeholder="{@smallads.form.image.url}" class="slider-url"/>
		<a title="${LangLoader::get_message('files_management', 'main')}" href="" class="fa fa-cloud-upload fa-2x" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_picture_url_${escape(ID)}_{fieldelements.ID}&parse=true&no_path=true', '', 'height=500,width=780,resizable=yes,scrollbars=yes');return false;"></a>
		<a href="javascript:SmalladsFormFieldCarousel.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:SmalladsFormFieldCarousel.add_field();" id="add-${escape(ID)}" class="field-source-more-value" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a>
