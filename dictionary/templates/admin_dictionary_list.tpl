<div id="admin-quick-menu">
	<ul>
		<li class="title-menu">{TITLE}</li>
		<li>
			<a href="admin_dictionary_cats.php"><img src="dictionary.png" alt="{L_DICTIONARY_CATS}" /></a>
			<br />
			<a href="admin_dictionary_cats.php" class="quick-link">{L_DICTIONARY_CATS}</a>
		</li>
		<li>
			<a href="admin_dictionary_cats.php?add=1&token={TOKEN}"><img src="dictionary.png" alt="{L_DICTIONARY_CATS_ADD}" /></a>
			<br />
			<a href="admin_dictionary_cats.php?add=1&token={TOKEN}" class="quick-link">{L_DICTIONARY_CATS_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary_list.php"><img src="dictionary.png" alt="{L_LIST_DEF}" /></a>
			<br />
			<a href="admin_dictionary_list.php" class="quick-link">{L_LIST_DEF}</a>
		</li>
		<li>
			<a href="dictionary.php?add=1&token={TOKEN}"><img src="dictionary.png" alt="{L_DICTIONARY_ADD}" /></a>
			<br />
			<a href="dictionary.php?add=1&token={TOKEN}" class="quick-link">{L_DICTIONARY_ADD}</a>
		</li>
		<li>
			<a href="admin_dictionary.php"><img src="dictionary.png" alt="{L_DICTIONARY_CONFIG}" /></a>
			<br />
			<a href="admin_dictionary.php" class="quick-link">{L_DICTIONARY_CONFIG}</a>
		</li>
	</ul>
</div>

<div id="admin-contents">
	<table>
		<caption>{L_LIST_DEF}</caption>
		<thead>
			<th>{L_DICTIONARY_WORD}</th>
			<th>{L_CATEGORY}</th>
			<th>{L_DATE}</th>
			<th>{L_APPROBATION}</th>
			<th>{L_MODIFY}</th>
			<th>{L_DELETE}</th>
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
					<a href="../dictionary/admin_dictionary_cats.php?add=1&id={dictionary_list.IDCAT}&token={TOKEN}">
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
					<a href="../dictionary/dictionary.php?edit={dictionary_list.ID}&token={TOKEN}" title="{L_UPDATE}" class="fa fa-edit"></a>
				</td>
				<td>
					<a href="../dictionary/dictionary.php?del={dictionary_list.ID}&token={TOKEN}" title="{L_DELETE}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
			</tr>
			# END dictionary_list #
		</tbody>
	</table>
</div>
