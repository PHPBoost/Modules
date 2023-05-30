<script>
	var RecipeFormFieldStep = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	RecipeFormFieldStep.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

				jQuery('<textarea /> ', {id : 'field_step_content_' + id, name : 'field_step_content_' + id, class : 'grouped-area', placeholder : ${escapejs(@recipe.step.content)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<div />', {'id' : 'inline_inputs_' + id, class : 'grouped-inputs'}).appendTo('#' + id);

				jQuery('<input /> ', {type : 'number', min : '1', id : 'field_step_number_' + id, class : 'grouped-element', name : 'field_step_number_' + id, placeholder : '{@recipe.step}'}).appendTo('#inline_inputs_' + id);
				jQuery('#' + id).append(' ');

				jQuery('<a /> ', {href : 'javascript:RecipeFormFieldStep.delete_field('+ this.integer +');', class : 'grouped-element bgc-full error', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#inline_inputs_' + id);
				jQuery('#' + id).append(' ');

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

	var RecipeFormFieldStep = new RecipeFormFieldStep();
</script>

<div id="input_fields_${escape(ID)}">
	# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<textarea id="field_step_content_${escape(ID)}_{fieldelements.ID}" class="grouped-area" name="field_step_content_${escape(ID)}_{fieldelements.ID}" placeholder="{@recipe.step.content}">{fieldelements.STEP_CONTENT}</textarea>
			<div class="grouped-inputs">		
				<input id="field_step_number_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="number" min="1" name="field_step_number_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.STEP_NUMBER}" placeholder="{@recipe.step}"/>
				<a class="grouped-element bgc-full error" href="javascript:RecipeFormFieldStep.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			</div>
		</div>
	# END fieldelements #
</div>
<a href="javascript:RecipeFormFieldStep.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>

