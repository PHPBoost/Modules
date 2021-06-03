<script>
	var Servers = function(id){
		this.id = id;
	};

	Servers.prototype = {
		init_sortable : function() {
			jQuery("ul#servers_list").sortable({
				handle: '.sortable-selector',
				placeholder: '<div class="dropzone">' + ${escapejs(@common.drop.here)} + '</div>'
			});
		},
		serialize_sortable : function() {
			jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
		},
		get_sortable_sequence : function() {
			var sequence = jQuery("ul#servers_list").sortable("serialize").get();
			return sequence[0];
		},
		change_reposition_pictures : function() {
			sequence = this.get_sortable_sequence();
			var length = sequence.length;
			for(var i = 0; i < length; i++)
			{
				if (jQuery('#list-' + sequence[i].id).is(':first-child'))
					jQuery("#move-up-" + sequence[i].id).hide();
				else
					jQuery("#move-up-" + sequence[i].id).show();

				if (jQuery('#list-' + sequence[i].id).is(':last-child'))
					jQuery("#move-down-" + sequence[i].id).hide();
				else
					jQuery("#move-down-" + sequence[i].id).show();
			}
		}
	};

	var Server = function(id, servers){
		this.id = id;
		this.Servers = servers;

		# IF C_SEVERAL_SERVERS #
			this.Servers.change_reposition_pictures();
		# ENDIF #
	};

	Server.prototype = {
		delete : function() {
			if (confirm(${escapejs(LangLoader::get_message('warning.confirm.delete', 'warning-lang'))}))
			{
				jQuery.ajax({
					url: '${relative_url(ServerStatusUrlBuilder::delete_server())}',
					type: "post",
					dataType: "json",
					data: {'id' : this.id, 'token' : '{TOKEN}'},
					success: function(returnData){
						if (returnData.code > 0) {
							jQuery("#list-" + returnData.code).remove();
							Servers.init_sortable();
						}
					}
				});
			}
		},
		change_display : function() {
			jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
			jQuery.ajax({
				url: '${relative_url(ServerStatusUrlBuilder::change_display())}',
				type: "post",
				dataType: "json",
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.id > 0) {
						if (returnData.display) {
							jQuery("#change-display-" + returnData.id).attr('aria-label', '{@common.displayed}').html('<i class="far fa-eye"></i>');
						} else {
							jQuery("#change-display-" + returnData.id).attr('aria-label', '{@common.hidden}').html('<i class="far fa-eye-slash"></i>');
						}
					}
				}
			});
		}
	};

	var Servers = new Servers('servers_list');
	jQuery(document).ready(function() {
		Servers.init_sortable();
		jQuery('li.sortable-element').on('mouseout',function(){
			Servers.change_reposition_pictures();
		});
	});
</script>
# INCLUDE MESSAGE_HELPER #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="Servers.serialize_sortable();">
	<fieldset id="servers_management">
		<legend>{@server.management}</legend>
		<div class="fieldset-inset">
			# IF C_SERVERS #
				<ul id="servers_list" class="sortable-block">
					# START servers #
						<li class="sortable-element" id="list-{servers.ID}" data-id="{servers.ID}">
							<div class="sortable-selector" aria-label="{@common.move}"></div>
							<div class="sortable-title">
								<span class="server-icon"># IF servers.C_ICON #<img src="{servers.ICON}" alt="{servers.NAME}" /># ELSE #&nbsp;# ENDIF #</span>
								<span>{servers.NAME}</span>
							</div>
							<div class="sortable-actions">
								# IF C_SEVERAL_SERVERS #
									<a href="#" aria-label="{@common.move.up}" id="move-up-{servers.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
									<a href="#" aria-label="{@common.move.down}" id="move-down-{servers.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
								# ENDIF #
								<a href="{servers.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
								<a href="#" onclick="return false;" aria-label="{@common.delete}" id="delete-{servers.ID}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
								<a href="#" onclick="return false;" id="change-display-{servers.ID}" aria-label="# IF servers.C_DISPLAY #{@common.displayed}# ELSE #{@common.hidden}# ENDIF #"><i class="far fa-eye# IF NOT servers.C_DISPLAY #-slash# ENDIF #" aria-hidden="true"></i></a>
							</div>
							<div class="spacer"></div>
							<script>
								jQuery(document).ready(function() {
									var server = new Server({servers.ID}, Servers);

									jQuery("#delete-{servers.ID}").on('click',function(){
										server.delete();
									});
									jQuery("#change-display-{servers.ID}").on('click',function(){
										server.change_display();
									});

									# IF C_SEVERAL_SERVERS #
										jQuery("#move-up-{servers.ID}").on('click',function(){
											var li = jQuery(this).closest('li');
											li.insertBefore( li.prev() );
											Servers.change_reposition_pictures();
										});

										jQuery("#move-down-{servers.ID}").on('click',function(){
											var li = jQuery(this).closest('li');
											li.insertAfter( li.next() );
											Servers.change_reposition_pictures();
										});
									# ENDIF #
								});
							</script>
						</li>
					# END servers #
				</ul>
			# ELSE #
				<div class="message-helper bgc notice">{@common.no.item.now}</div>
			# ENDIF #
		</div>
	</fieldset>
	# IF C_SEVERAL_SERVERS #
		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fielset-inset">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" class="button submit" name="submit" value="true">{@form.submit}</button>
				<input type="hidden" name="tree" id="tree" value="">
			</div>
		</fieldset>
	# ENDIF #
	# IF C_SERVERS #
		<fieldset>
			<legend>{@server.refresh.status}</legend>
		</fieldset>
		<fieldset class="fieldset-submit">
			<legend>{@form.refresh}</legend>
			<div class="fieldset-inset">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" class="button submit" name="regenerate_status" value="true">{@form.refresh}</button>
			</div>
		</fieldset>
	# ENDIF #
</form>
