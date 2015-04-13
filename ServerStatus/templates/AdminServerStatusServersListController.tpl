<script>
<!--
var Servers = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tag:'li',
			only:'sortable-element'
		});
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	},
	serialize_sortable : function() {
		$('position').value = Sortable.serialize(this.id);
	},
	get_sortable_sequence : function() {
		return Sortable.sequence(this.id);
	},
	set_sortable_sequence : function(sequence) {
		Sortable.setSequence(this.id, sequence);
	},
	change_reposition_pictures : function() {
		sequence = Sortable.sequence(this.id);
		
		if ($('move_up_' + sequence[0]))
			$('move_up_' + sequence[0]).style.display = "none";
		if ($('move_down_' + sequence[0]))
			$('move_down_' + sequence[0]).style.display = "inline";
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			$('move_up_' + sequence[j]).style.display = "inline";
			$('move_down_' + sequence[j]).style.display = "inline";
		}
		
		if ($('move_up_' + sequence[sequence.length - 1]))
			$('move_up_' + sequence[sequence.length - 1]).style.display = "inline";
		if ($('move_down_' + sequence[sequence.length - 1]))
			$('move_down_' + sequence[sequence.length - 1]).style.display = "none";
	}
});

var Server = Class.create({
	id : '',
	more_is_opened : false,
	Servers: null,
	is_not_displayed : false,
	initialize : function(id, display, servers) {
		this.id = id;
		this.Servers = servers;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
		
		# IF C_MORE_THAN_ONE_SERVER #
		this.Servers.change_reposition_pictures();
		# ENDIF #
	},
	delete_server : function() {
		if (confirm(${escapejs(@admin.config.servers.delete_server.confirm)}))
		{
			new Ajax.Request('${relative_url(ServerStatusUrlBuilder::delete_server())}', {
				method:'post',
				asynchronous: false,
				parameters: {'id' : this.id, 'token' : '{TOKEN}'},
				onSuccess: function(transport) {
					if (transport.responseText == 0)
					{
						$('no-server').style.display = ""; 
					}
				}
			});
			
			var elementToDelete = $('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			Servers.destroy_sortable();
			Servers.create_sortable();
		}
	},
	move_up : function() {
		var sequence = Servers.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 1 ; j < sequence.length ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j-1];
					sequence[j-1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			Servers.set_sortable_sequence(sequence);
			Servers.change_reposition_pictures();
		}
	},
	move_down : function() {
		var sequence = Servers.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 0 ; j < sequence.length - 1 ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j+1];
					sequence[j+1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			Servers.set_sortable_sequence(sequence);
			Servers.change_reposition_pictures();
		}
	},
	change_display : function() {
		display = this.is_not_displayed;
		
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
		});
		
		this.change_display_picture();
	},
	change_display_picture : function() {
		if ($('change_display_' + this.id)) {
			if (this.is_not_displayed == false) {
				$('change_display_' + this.id).className = "fa fa-eye";
				$('change_display_' + this.id).title = "{@server.display}";
				$('change_display_' + this.id).alt = "{@server.display}";
				this.is_not_displayed = true;
			}
			else {
				$('change_display_' + this.id).className = "fa fa-eye-slash";
				$('change_display_' + this.id).title = "{@server.not_display}";
				$('change_display_' + this.id).alt = "{@server.not_display}";
				this.is_not_displayed = false;
			}
		}
	},
});

var Servers = new Servers('servers_list');
Event.observe(window, 'load', function() {
	Servers.destroy_sortable();
	Servers.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="Servers.serialize_sortable();">
	<fieldset id="servers_management">
	<legend>{@admin.config.servers.management}</legend>
		<ul id="servers_list" class="sortable-block">
			# START servers #
				<li class="sortable-element" id="list_{servers.ID}">
					<div class="sortable-title">
						<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
						<span style="padding:10px;"># IF servers.C_ICON #<img src="{servers.ICON}" alt="" /># ELSE #&nbsp;# ENDIF #</span>
						<span class="text-strong">{servers.NAME}</span>
						<div class="sortable-actions">
							# IF C_MORE_THAN_ONE_SERVER #
							<div class="sortable-options">
								<a href="" title="{@admin.config.servers.move_up}" id="move_up_{servers.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
							</div>
							<div class="sortable-options">
								<a href="" title="{@admin.config.servers.move_down}" id="move_down_{servers.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
							</div>
							# ENDIF #
							<div class="sortable-options">
								<a href="{servers.U_EDIT}" title="{@admin.config.servers.action.edit_server}" class="fa fa-edit"></a>
							</div>
							<div class="sortable-options">
								<a href="" onclick="return false;" title="{@admin.config.servers.delete_server}" id="delete_{servers.ID}" class="fa fa-delete"></a>
							</div>
							<div class="sortable-options">
								<a href="" onclick="return false;" id="change_display_{servers.ID}" class="fa fa-eye"></a>
							</div>
						</div>
					</div>
					<div class="spacer"></div>
				</li>
				<script>
				<!--
				Event.observe(window, 'load', function() {
					var server = new Server({servers.ID}, '{servers.C_DISPLAY}', Servers);
					
					$('list_{servers.ID}').observe('mouseup',function(){
						Servers.change_reposition_pictures();
					});
					
					$('delete_{servers.ID}').observe('click',function(){
						server.delete_server();
					});
					
					$('change_display_{servers.ID}').observe('click',function(){
						server.change_display();
					});
					
					# IF C_MORE_THAN_ONE_SERVER #
					$('move_up_{servers.ID}').observe('click',function(){
						server.move_up();
					});
					
					$('move_down_{servers.ID}').observe('click',function(){
						server.move_down();
					});
					# ENDIF #
				});
				-->
				</script>
			# END servers #
		</ul>
		<div id="no-server" class="center"# IF C_SERVERS # style="display:none;"# ENDIF #>
			<div class="notice message-helper-small">{@admin.config.servers.no_server}</div>
		</div>
	</fieldset>
	<fieldset class="fieldset-submit">
		# IF C_MORE_THAN_ONE_SERVER #
		<button type="submit" name="submit" value="true">{@admin.config.servers.update_fields_position}</button>
		<input type="hidden" name="token" value="{TOKEN}" />
		<input type="hidden" name="position" id="position" value="" />
		# ENDIF #
		# IF C_SERVERS #<button type="submit" name="regenerate_status" value="true">{@admin.config.servers.status_refresh}</button># ENDIF #
	</fieldset>
</form>
