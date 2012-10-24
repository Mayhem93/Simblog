<article class="post-entry" id="post_{$post.id}" data-postid="{$post.id}">
    <h2 title="Heading"><a href="?action=post&id={$post.id}" title="{$post.title}">{$post.title}</a></h2>
    <header class="postMetadata">
        <span>Published: <time class="label" pubdate datetime="{date('Y-m-d\Th:iP', $post.utime)}">{date('F jS, Y', $post.utime)}</time></span>

        <a class="comments-count" href="#"><span class="badge badge-info">2</span>
            Comment(s)</a>
        <p class="categories">Categories:
        {if $post.category == ""}no category
        {else}
            <span class="label label-info">{$post.category}</span>
        {/if}
        </p>
    </header>
    <div class="postBody">
        {$post.content }
    </div>
    <footer>
        <span class="tags">Tags:
        {foreach $post.tags as $tag}
            <a href="#">$tag</a>
        {/foreach}
		</span>
			<button class="btn btn-primary reply-button">Leave a reply</button>
    </footer>
</article>