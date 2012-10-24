{include file="bits/post.tpl" post=$post}
<h3 class="commentListHeading">{$commentCount} comments on <em>'$post.title'</em>:</h3>
{foreach $comments as $comment}
	{include file="bits/comment.tpl"}
{/foreach}