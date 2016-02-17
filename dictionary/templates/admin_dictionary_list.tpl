<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_DICTIONARY_MANAGEMENT}">
		<i class="fa fa-bars"></i> {L_DICTIONARY_MANAGEMENT}
	</a>
	<ul>
		<li>
			<a href="admin_dictionary_cats.php" class="quick-link">{L_DICTIONARY_CATS}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1" class="quick-link">{L_DICTIONARY_CATS_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php" class="quick-link">{L_LIST_DEF}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1" class="quick-link">{L_DICTIONARY_ADD}</a>
		</li>
		<li>
			<a href="${relative_url(DictionaryUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<table>
		<caption>{L_LIST_DEF}</caption>
		<thead>
			<th>{L_DICTIONARY_WORD}</th>
			<th>{L_CATEGORY}</th>
			<th>{L_DATE}</th>
			<th>{L_APPROBATION}</th>
			<th>${LangLoader::get_message('edit', 'common')}</th>
			<th>${LangLoader::get_message('delete', 'common')}</th>
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="7">
					# INCLUDE PAGINATION #
				</th>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START dictionary_list #
			<tr> 
				<td> 
					<a href="../dictionary/dictionary.php?l={dictionary_list.NAME}">{dictionary_list.NAME}</a>
				</td>
				<td> 
					<a href="../dictionary/admin_dictionary_cats.php?add=1&id={dictionary_list.IDCAT}">
						{dictionary_list.IMG} {dictionary_list.CAT}
					</a>
				</td>
				<td>
					{dictionary_list.DATE}
				</td>
				<td>
					{dictionary_list.APROBATION}
				</td>
				<td> 
					<a href="../dictionary/dictionary.php?edit={dictionary_list.ID}" title="{L_UPDATE}" class="fa fa-edit"></a>
				</td>
				<td>
					<a href="../dictionary/dictionary.php?del={dictionary_list.ID}&token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
			</tr>
			# END dictionary_list #
		</tbody>
	</table>
</div>
