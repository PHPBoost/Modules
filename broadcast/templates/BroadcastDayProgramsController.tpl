# IF C_ACCORDION_VIEW #
	<div id="{DAY_ID}" class="multiple-accordion">
		<span class="accordion-trigger">{DAY}</span>
		<div class="accordion-content">
			# IF C_ITEMS #
				<div class="broadcast-item several-items">
					# START items #
						# IF items.C_SELECTED_DAY #
							<div
									id="article-broadcast-{items.ID}"
									class="broadcast-item several-items# IF items.C_EXTRA_LIST # extra-list# ENDIF #"
									itemscope="itemscope"
									itemtype="https://schema.org/CreativeWork">
								<header class="flex-between ">
									<h3>
										<a href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a>
										<meta itemprop="url" content="{items.U_ITEM}">
										<meta itemprop="description" content="${escape(items.CONTENT)}"/>
										# IF items.C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{items.U_THUMBNAIL}"># ENDIF #
									</h3>
									<div class="accordion-thumbnail">
										<img src="# IF items.C_HAS_THUMBNAIL #{items.U_THUMBNAIL}# ELSE #{PATH_TO_ROOT}/broadcast/templates/images/default.jpg# ENDIF #" alt="{items.TITLE}">
									</div>
								</header>
								<div class="flex-between">
									<div class="more">
										<span class="pinned"><i class="fa fa-microphone-lines"></i> {items.AUTHOR_CUSTOM_NAME}</span>
										<span class="pinned"><i class="far fa-clock"></i> {items.START_HOURS}h{items.START_MINUTES} - {items.END_HOURS}h{items.END_MINUTES}</span>
										<span class="pinned"><i class="far fa-folder"></i> <a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></span>
									</div>
									# IF items.C_CONTROLS #
										<div class="align-right controls">
											# IF items.C_EDIT #
												<a href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt"></i></a>
											# ENDIF #
										</div>
									# ENDIF #
								</div>
								<div class="content">{items.CONTENT}</div>
							</div>
						# ENDIF #
					# END items #
				</div>
				<script>
					jQuery(document).ready(function(){
						var today = new Date();
						var weekdays = new Array(7);
						weekdays[0] = "sunday";
						weekdays[1] = "monday";
						weekdays[2] = "tuesday";
						weekdays[3] = "wednesday";
						weekdays[4] = "thursday";
						weekdays[5] = "friday";
						weekdays[6] = "saturday";
						var openedDay = weekdays[today.getDay()];
						jQuery('#' + openedDay)
								.addClass('is-open')
								.children('.accordion-trigger button')
								.attr('aria-expanded', true);
						jQuery('#' + openedDay)
								.children('.accordion-content')
								.css('display', 'block')
								.removeAttr('hidden');
					})
				</script>
			# ENDIF #
		</div>
	</div>
# ENDIF #
# IF C_TABLE_VIEW #
	<h2>{DAY}</h2>
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th>{@broadcast.program.name}</th>
				<th>{@broadcast.hourly}</th>
				<th>{@broadcast.announcer}</th>
				<th>{@common.category}</th>
				# IF C_CONTROLS #<th aria-label="{@common.moderation}"><i class="fa fa-legal"></i></th># ENDIF #
			</tr>
		</thead>
		<tbody>
			# IF C_ITEMS #
				# START items #
					# IF items.C_SELECTED_DAY #
						<tr>
							<td class="table-thumbnail"># IF items.C_HAS_THUMBNAIL #<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}"># ENDIF #</td>
							<td class="align-left"><a href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a></td>
							<td>{items.START_HOURS}h{items.START_MINUTES} - {items.END_HOURS}h{items.END_MINUTES}</td>
							<td>{items.AUTHOR_CUSTOM_NAME}</td>
							<td><a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></td>
							# IF items.C_CONTROLS #
								<td>
									# IF items.C_EDIT #
										<a href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a>
									# ENDIF #
									# IF items.C_DELETE #
										<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt"></i></a>
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# ENDIF #
				# END items #
			# ENDIF #
		</tbody>
	</table>
# ENDIF #
# IF C_CALENDAR_VIEW #
	<ul class="broadcast-calendar">
		<li class="align-center"><h6>{DAY}</h6></li>
		# IF C_ITEMS #
			# START items #
				# IF items.C_SELECTED_DAY #
					<li data-filterable data-filter-calendar="{items.DAY}">
						<a href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a>
						<p class="small">
							<i class="far fa-clock"></i> {items.START_HOURS}h{items.START_MINUTES} - {items.END_HOURS}h{items.END_MINUTES}
						</p>
					</li>
				# ENDIF #
			# END items #
		# ENDIF #
	</ul>
# ENDIF #
