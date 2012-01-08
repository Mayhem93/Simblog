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
			<!-- google_ad_section_end -->
		</div>
		<div class="clear"></div>
		<div class="navigation" id="nav-below"></div>
		<div class="clear"></div>
	</div>
</div>