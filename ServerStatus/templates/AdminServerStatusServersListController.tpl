<script>
<!--
var Servers = function(id){
	this.id = id;
};

Servers.prototype = {
	init_sortable : function() {
		jQuery("ul#servers_list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
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
	
	# IF C_MORE_THAN_ONE_SERVER #
	this.Servers.change_reposition_pictures();
	# ENDIF #
};

Server.prototype = {
	delete : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
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
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" title="{@server.display}"></i>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" title="{@server.not_display}"></i>');
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
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="Servers.serialize_sortable();">
	<fieldset id="servers_management">
	<legend>{@admin.config.servers.management}</legend>
		<ul id="servers_list" class="sortable-block">
			# START servers #
				<li class="sortable-element" id="list-{servers.ID}" data-id="{servers.ID}">
					<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
					<div class="sortable-title">
						<span style="padding:10px;"># IF servers.C_ICON #<img src="{servers.ICON}" alt="" /># ELSE #&nbsp;# ENDIF #</span>
						<span class="text-strong">{servers.NAME}</span>
						<div class="sortable-actions">
							# IF C_MORE_THAN_ONE_SERVER #
							<a href="" title="{@admin.config.servers.move_up}" id="move-up-{servers.ID}" onclick="return false;"><i class="fa fa-arrow-up"></i></a>
							<a href="" title="{@admin.config.servers.move_down}" id="move-down-{servers.ID}" onclick="return false;"><i class="fa fa-arrow-down"></i></a>
							# ENDIF #
							<a href="{servers.U_EDIT}" title="{@admin.config.servers.action.edit_server}"><i class="fa fa-edit"></i></a>
							<a href="" onclick="return false;" title="{@admin.config.servers.delete_server}" id="delete-{servers.ID}"><i class="fa fa-delete"></i></a>
							<a href="" onclick="return false;" id="change-display-{servers.ID}"><i # IF servers.C_DISPLAY #class="fa fa-eye" title="{@server.display}"# ELSE #class="fa fa-eye-slash" title="{@server.not_display}"# ENDIF #></i></a>
						</div>
					</div>
					<div class="spacer"></div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var server = new Server({servers.ID}, Servers);
						
						jQuery("#delete-{servers.ID}").on('click',function(){
							server.delete();
						});
						jQuery("#change-display-{servers.ID}").on('click',function(){
							server.change_display();
						});
						
						# IF C_MORE_THAN_ONE_SERVER #
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
					-->
					</script>
				</li>
			# END servers #
		</ul>
		<div id="no-server" class="center"# IF C_SERVERS # style="display:none;"# ENDIF #>
			<div class="notice message-helper-small">{@admin.config.servers.no_server}</div>
		</div>
	</fieldset>
	<fieldset class="fieldset-submit">
		<input type="hidden" name="token" value="{TOKEN}">
		# IF C_MORE_THAN_ONE_SERVER #
		<button type="submit" name="submit" value="true">{@admin.config.servers.update_fields_position}</button>
		<input type="hidden" name="tree" id="tree" value="">
		# ENDIF #
		# IF C_SERVERS #<button type="submit" name="regenerate_status" value="true">{@admin.config.servers.status_refresh}</button># ENDIF #
	</fieldset>
</form>
