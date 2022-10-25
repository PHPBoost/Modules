<script>
	var RecipeFormFieldIngredient = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	RecipeFormFieldIngredient.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id, class : 'grouped-inputs'}).appendTo('#input_fields_' + this.id_input);

				jQuery('<input/> ', {type : 'text', id : 'field_ingredient_' + id, class : 'grouped-element', name : 'field_ingredient_' + id, placeholder : ${escapejs(@recipe.ingredient)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<input/> ', {type : 'text', id : 'field_amount_' + id, class : 'grouped-element', name : 'field_amount_' + id, placeholder : ${escapejs(@recipe.amount)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {href : 'javascript:RecipeFormFieldIngredient.delete_field('+ this.integer +');', class : 'grouped-element', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

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

	var RecipeFormFieldIngredient = new RecipeFormFieldIngredient();
</script>

<div id="input_fields_${escape(ID)}">
	# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}" class="grouped-inputs">
			<input id="field_ingredient_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_ingredient_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.INGREDIENT}" placeholder="{@recipe.ingredient}"/>
			<input id="field_amount_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_amount_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.AMOUNT}" placeholder="{@recipe.amount}" class="slider-url"/>
			<a class="grouped-element" href="javascript:RecipeFormFieldIngredient.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
		</div>
	# END fieldelements #
</div>
<a href="javascript:RecipeFormFieldIngredient.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>

