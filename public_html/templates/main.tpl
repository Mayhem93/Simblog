<div class="section" id="main1">
	<div class="widget Blog" id="Blog1">
		<div class="blog-posts hfeed">
			<div class="date-outer">
				<div class="date-posts">
					{foreach $blog_posts as $post}
						{include file="bits/post.tpl"}
					{/foreach}
				</div>
			</div>
		</div>
		<div class="navigation" id="nav-below">
		{if $page!=1}
		<a class="nav_previous" href="?page={$page-1}">&lt; Previous Page</a>
		{/if}
		{if !smarty_isLastPage($page)}
		<a class="nav_next" href="?page={$page+1}">Next Page &gt;</a>
		{/if}
		</div>
	</div>
</div>