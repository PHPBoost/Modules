<script>
<!--
var HomeLandingFormFieldSelectCarousel = function(){
	this.integer = ${escapejs(NBR_FIELDS)};
	this.id_input = ${escapejs(ID)};
	this.max_input = ${escapejs(MAX_INPUT)};
};

HomeLandingFormFieldSelectCarousel.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@form.description}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_value_' + id, name : 'field_value_' + id, class : 'field-large', placeholder : '{@form.url} {@form.picture}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<a/> ', {href : 'javascript:HomeLandingFormFieldSelectCarousel.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

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

var HomeLandingFormFieldSelectCarousel = new HomeLandingFormFieldSelectCarousel();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@form.description}"/>
			<input type="text" name="field_value_${escape(ID)}_{fieldelements.ID}" id="field_value_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.VALUE}" placeholder="{@form.url} {@form.picture}" class="field-large"/>
			<a href="javascript:HomeLandingFormFieldSelectCarousel.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:HomeLandingFormFieldSelectCarousel.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a> 