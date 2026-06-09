<script>
    //sortable
    jQuery(document).ready(function() {
		if (jQuery("#input_fields_${escape(ID)}")[0]) {
			Sortable.create(document.getElementById('input_fields_${escape(ID)}'), {
				handle: '.sortable-selector',
				animation: 150,
				onEnd: function() {
					change_ids();
				}
			});
		}
    });

    function change_ids()
    {
        let li = jQuery("#input_fields_${escape(ID)} li");
        li.each(function() {
            jQuery(this).attr('id', '${escape(ID)}_' + jQuery(this).index());
            jQuery(this).find('.input-ingredient').attr('name', 'field_name_${escape(ID)}_' + jQuery(this).index()).attr('id', 'field_name_${escape(ID)}_' + jQuery(this).index());
            jQuery(this).find('.input-amount').attr('name', 'field_value_${escape(ID)}_' + jQuery(this).index()).attr('id', 'field_value_${escape(ID)}_' + jQuery(this).index());
            jQuery(this).find('.move-up').attr('id', 'move-up-${escape(ID)}_' + jQuery(this).index());
            jQuery(this).find('.move-down').attr('id', 'move-down-${escape(ID)}_' + jQuery(this).index());
            jQuery(this).find('.delete-item').attr('href', 'javascript:FormFieldPossibleValues.delete_field(' + jQuery(this).index() + ')').attr('id', 'delete_${escape(ID)}' + jQuery(this).index());
            # IF C_DISPLAY_DEFAULT_RADIO #
                jQuery(this).find('.input-radio').attr('id', 'field_is_default_${escape(ID)}_' + jQuery(this).index());
            # ENDIF #
        })
    }

    function move(id, direction)
    {
        var li = jQuery('#' + id).closest('li');
        if(direction === 'up')
            li.insertBefore( li.prev() );
        if(direction === 'down')
            li.insertAfter( li.next() );
        change_ids();
    }

    var RecipeFormFieldIngredient = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	RecipeFormFieldIngredient.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<li/>', {'id' : id, class : 'grouped-inputs'}).appendTo('#input_fields_' + this.id_input);

				jQuery('<span/>', {class : 'sortable-selector grouped-element', 'aria-label' : ${escapejs(@common.move)}}).html('&nbsp;').appendTo('#' + id);

				jQuery('<input/> ', {type : 'text', id : 'field_ingredient_' + id, class : 'input-ingredient grouped-element', name : 'field_ingredient_' + id, placeholder : ${escapejs(@recipe.ingredient)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<input/> ', {type : 'text', id : 'field_amount_' + id, class : 'input-amount grouped-element', name : 'field_amount_' + id, placeholder : ${escapejs(@recipe.amount)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {href : 'javascript:RecipeFormFieldIngredient.delete_field('+ this.integer +');', class : 'grouped-element bgc-full error', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);
                jQuery('<a/>', {class : 'move-up grouped-element', href : '#', id : 'move-up-' + id, 'aria-label' : ${escapejs(@common.move.up)}, onclick : "move(this.id, 'up');return false;"}).html('<i class="fa fa-arrow-up" aria-hidden="true"></i>').appendTo('#' + id);
				jQuery('<a/>', {class : 'move-down grouped-element', href : '#', id : 'move-down-' + id, 'aria-label' : ${escapejs(@common.move.down)}, onclick : "move(this.id, 'down');return false;"}).html('<i class="fa fa-arrow-down" aria-hidden="true"></i>').appendTo('#' + id);

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

<ul id="input_fields_${escape(ID)}" class="sortable-block">
	# START fieldelements #
		<li id="${escape(ID)}_{fieldelements.ID}" class="grouped-inputs">
			<span class="sortable-selector grouped-element" aria-label="{@common.move}">&nbsp;</span>
            <input id="field_ingredient_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_ingredient_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.INGREDIENT}" placeholder="{@recipe.ingredient}"/>
			<input id="field_amount_${escape(ID)}_{fieldelements.ID}" class="grouped-element" type="text" name="field_amount_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.AMOUNT}" placeholder="{@recipe.amount}" class="slider-url"/>
			<a class="grouped-element bgc-full error" href="javascript:RecipeFormFieldIngredient.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
            <a href="#" class="move-up grouped-element" aria-label="{@common.move.up}" id="move-up-${escape(ID)}_{fieldelements.ID}" onclick="move(this.id, 'up');return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
            <a href="#" class="move-down grouped-element" aria-label="{@common.move.down}" id="move-down-${escape(ID)}_{fieldelements.ID}" onclick="move(this.id, 'down');return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
        </li>
	# END fieldelements #
</ul>
<a href="javascript:RecipeFormFieldIngredient.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>

