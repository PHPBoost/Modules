<script>
	var SmalladsFormFieldSmalladType = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	SmalladsFormFieldSmalladType.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id, class : 'cell'}).appendTo('#input_fields_' + this.id_input);

				jQuery('<div/>', {'id' : 'smallad_type_' + id, class : 'grouped-inputs'}).appendTo('#' + id);

				jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, class : 'grouped-element', placeholder : "{@smallads.type.placeholder}"}).appendTo('#smallad_type_' + id);
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {href : 'javascript:SmalladsFormFieldSmalladType.delete_field('+ this.integer +');', class : 'grouped-element', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i><span class="sr-only">' + ${escapejs(@common.delete)} + '</span>').appendTo('#smallad_type_' + id);

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
</script>

<div id="input_fields_${escape(ID)}" class="cell-flex cell-columns-4">
	# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}" class="cell">
			<div id="smallad_type_{fieldelements.ID}" class="grouped-inputs">
				<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" class="grouped-element" value="{fieldelements.NAME}" placeholder="{@smallads.type.placeholder}"/>
				<a href="javascript:SmalladsFormFieldSmalladType.delete_field({fieldelements.ID});" data-confirmation="delete-element" class="grouped-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			</div>
		</div>
	# END fieldelements #
</div>
<a href="javascript:SmalladsFormFieldSmalladType.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
