<script>
<!--
var HomeLandingModules = function(id){
	this.id = id;
};

HomeLandingModules.prototype = {
	init_sortable : function() {
		jQuery("ul#modules_list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
		});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#modules_list").sortable("serialize").get();
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

var HomeLandingModule = function(id, modules){
	this.id = id;
	this.HomeLandingModules = modules;

	# IF C_MORE_THAN_ONE_MODULE #
	this.HomeLandingModules.change_reposition_pictures();
	# ENDIF #
};

HomeLandingModule.prototype = {
	change_display : function() {
		jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
		jQuery.ajax({
			url: '${relative_url(HomeLandingUrlBuilder::change_display())}',
			type: "post",
			dataType: "json",
			data: {'id' : this.id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.id > 0) {
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" title="{@field.display}"></i>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" title="{@field.not_display}"></i>');
					}
				}
			}
		});
	}
};

var HomeLandingModules = new HomeLandingModules('modules_list');
jQuery(document).ready(function() {
	HomeLandingModules.init_sortable();
	jQuery('li.sortable-element').on('mouseout',function(){
		HomeLandingModules.change_reposition_pictures();
	});
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="HomeLandingModules.serialize_sortable();" class="fieldset-content">
	<fieldset id="homelanding_modules_management">
		<legend>${LangLoader::get_message('admin.elements_position', 'common', 'HomeLanding')}</legend>
		<ul id="modules_list" class="sortable-block">
			# START modules_list #
				<li class="sortable-element" id="list-{modules_list.ID}" data-id="{modules_list.ID}">
					<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
					<div class="sortable-title">
						{modules_list.NAME}
					</div>
					<div class="sortable-actions">
						# IF C_MORE_THAN_ONE_MODULE #
						<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{modules_list.ID}" onclick="return false;"><i class="fa fa-arrow-up"></i></a>
						<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{modules_list.ID}" onclick="return false;"><i class="fa fa-arrow-down"></i></a>
						# ENDIF #
						<a href="{modules_list.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						<a href="" onclick="return false;" id="change-display-{modules_list.ID}"><i # IF modules_list.C_DISPLAY #class="fa fa-eye" title="{@field.display}"# ELSE #class="fa fa-eye-slash" title="{@field.not_display}"# ENDIF #></i></a>
					</div>
					<div class="spacer"></div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var module = new HomeLandingModule({modules_list.ID}, HomeLandingModules);

						jQuery("#change-display-{modules_list.ID}").on('click',function(){
							module.change_display();
						});

						# IF C_MORE_THAN_ONE_MODULE #
						jQuery("#move-up-{modules_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertBefore( li.prev() );
							HomeLandingModules.change_reposition_pictures();
						});

						jQuery("#move-down-{modules_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertAfter( li.next() );
							HomeLandingModules.change_reposition_pictures();
						});
						# ENDIF #
					});
					-->
					</script>
				</li>
			# END modules_list #
		</ul>
	</fieldset>
	# IF C_MORE_THAN_ONE_MODULE #
	<fieldset class="fieldset-submit">
		<div class="fieldset-inset">
			<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="tree" id="tree" value="">
		</div>
	</fieldset>
	# ENDIF #
</form>
