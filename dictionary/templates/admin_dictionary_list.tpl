<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@dictionary.items.management}
	</a>
	<ul>
		<li>
			<a href="index.php" class="quick-link">{@common.home}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php" class="quick-link">{@category.categories.management}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1" class="quick-link">{@category.add}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php" class="quick-link">{@dictionary.items.management}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1" class="quick-link">{@dictionary.add.item}</a>
		</li>
		<li>
			<a href="${relative_url(DictionaryUrlBuilder::configuration())}" class="quick-link">{@form.configuration}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<table class="table">
		<caption>{@dictionary.items.management}</caption>
		<thead>
			<th class="align-left">{@dictionary.items}</th>
			<th class="align-left">{@category.category}</th>
			<th>{@common.creation.date}</th>
			<th>{@common.status.approved}</th>
			<th><span class="sr-only">{@common.moderation}</span></th>
		</thead>
		<tbody>
			# START dictionary_list #
				<tr>
					<td class="align-left">
						<a href="../dictionary/dictionary.php?l={dictionary_list.NAME}">{dictionary_list.NAME}</a>
					</td>
					<td class="align-left">
						<a href="../dictionary/admin_dictionary_cats.php?add=1&id={dictionary_list.CATEGORY_ID}">
							{dictionary_list.CATEGORY_IMAGE} {dictionary_list.CATEGORY_NAME}
						</a>
					</td>
					<td>
						{dictionary_list.DATE}
					</td>
					<td>
						{dictionary_list.APPROBATION}
					</td>
					<td class="col-small controls">
						<a href="../dictionary/dictionary.php?edit={dictionary_list.ITEM_ID}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
						<a href="../dictionary/dictionary.php?del={dictionary_list.ITEM_ID}&token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
					</td>
				</tr>
			# END dictionary_list #
		</tbody>
		# IF C_PAGINATION #
			<tfoot>
				<tr>
					<th colspan="7">
						# INCLUDE PAGINATION #
					</th>
				</tr>
			</tfoot>
		# ENDIF #
	</table>
</div>
