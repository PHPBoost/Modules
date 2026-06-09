<script>
	var VideoFormFieldPlayer = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	VideoFormFieldPlayer.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id, class : 'cell'}).appendTo('#input_fields_' + this.id_input);

				jQuery('<div/>', {'id' : 'trusted_hosts_' + id, class : 'grouped-inputs'}).appendTo('#' + id);

				jQuery('<input/> ', {type : 'text', id : 'field_platform_' + id, name : 'field_platform_' + id, class : 'grouped-element', placeholder : ${escapejs(@video.platform)}}).appendTo('#trusted_hosts_' + id);
				jQuery('#' + id).append(' ');

				jQuery('<input/> ', {type : 'text', id : 'field_domain_' + id, name : 'field_domain_' + id, class : 'grouped-element', placeholder : ${escapejs(@video.domain)}}).appendTo('#trusted_hosts_' + id);
				jQuery('#' + id).append(' ');

				jQuery('<input/> ', {type : 'text', id : 'field_player_' + id, name : 'field_player_' + id, class : 'grouped-element', placeholder : ${escapejs(@video.host.player)}}).appendTo('#trusted_hosts_' + id);
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {href : 'javascript:VideoFormFieldPlayer.delete_field('+ this.integer +');', class : 'grouped-element', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i><span class="sr-only">' + ${escapejs(@common.delete)} + '</span>').appendTo('#trusted_hosts_' + id);

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

	var VideoFormFieldPlayer = new VideoFormFieldPlayer();
</script>

<div id="input_fields_${escape(ID)}">
	# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<div id="trusted_hosts_{fieldelements.ID}" class="grouped-inputs">
				<input type="text" name="field_platform_${escape(ID)}_{fieldelements.ID}" id="field_platform_${escape(ID)}_{fieldelements.ID}" class="grouped-element" value="{fieldelements.PLATFORM}" placeholder="{@video.platform}"/>
				<input type="text" name="field_domain_${escape(ID)}_{fieldelements.ID}" id="field_domain_${escape(ID)}_{fieldelements.ID}" class="grouped-element" value="{fieldelements.DOMAIN}" placeholder="{@video.domain}"/>
				<input type="text" name="field_player_${escape(ID)}_{fieldelements.ID}" id="field_player_${escape(ID)}_{fieldelements.ID}" class="grouped-element" value="{fieldelements.PLAYER}" placeholder="{@video.host.player}"/>
				<a href="javascript:VideoFormFieldPlayer.delete_field({fieldelements.ID});" data-confirmation="delete-element" class="grouped-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			</div>
		</div>
	# END fieldelements #
</div>
<a href="javascript:VideoFormFieldPlayer.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
