<div class="section" id="main1">
	<div class="widget Blog" id="Blog1">
		<div class="blog-posts hfeed">
			<div class="date-outer">
				<div class="date-posts">
					{foreach $blog_posts as $post}
					<div class="post-outer">
						<div class="post-hentry">
							<h2 class="entry-title">{$post.title}</h2>
							<div class="entry-meta">
								<span class="author vcard">Category: {$post.category} | </span>
								<span class="onDate">{$post.date_posted} | </span> <span
									class="onDate">{blog_getCommentsNumber({$post.id})}
									Comment(s)</span>
							</div>
							<div class="entry-content">{$post.content}</div>
						</div>
					</div>
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