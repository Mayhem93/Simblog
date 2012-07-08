{*<div class="widget BlogArchive" id="BlogArchive1">
	<h2>Archives</h2>
	<div class="widget-content">
		<div id="ArchiveList">
			<div id="BlogArchive1_ArchiveList">
				<ul class="hierarchy">
					{foreach $archives as $year}
						<li id="year_{$year@key}">{$year@key}<ul>
							{foreach $year as $month}
							<li class="archiveMonths" id="month_{$month@key}">{$month@key}<ul>
								{foreach $month as $post}
								<li><a href="?action=post&id={$post.id}">{$post.title}</a></li>
								{/foreach}
							</ul>
							</li>
							{/foreach}
						</ul>
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
	</div>
</div>*}

<div id="archiveBlock">
    <h2>Archives</h2>
    <ul id="archiveBlockList">
        {foreach $archives as $year}
        <li>{$year@key}
            <ul>
                {foreach $year as $month}
                <li>{$month@key}
                    <ul>
                        {foreach $month as $post}
							<li><a href="?action=post&id={$post.id}" title="{$post.title}">{$post.title}</a></li>
                        {/foreach}
                    </ul>
                </li>
                {/foreach}
            </ul>
        </li>
        {/foreach}
    </ul>
</div>