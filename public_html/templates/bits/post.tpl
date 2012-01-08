<div id="post_{$post.id}" class="post-outer">
	<div class="post-hentry">
		<h2 class="entry-title">
			<a href="?action=post&id={$post.id}">{$post.title}</a>
		</h2>
		{if smarty_isAdminSession() }<span class="post-delete">[X]</span>
		{/if}
		<div class="entry-meta">
			<span class="author vcard">Category: {$post.category} | </span> <span
				class="onDate">{$post.date_posted} | </span> <span class="onDate">{blog_getCommentsNumber({$post.id})}
				Comment(s)</span>
		</div>
		<div class="entry-content">{$post.content}</div>
	</div>
</div>