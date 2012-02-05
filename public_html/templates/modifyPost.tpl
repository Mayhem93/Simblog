<div class="section modifyPost" id="main1">
	<p>HTML control buttons goes here !</p>
	<form name="modifyPost" action="?action=modifyPost" method="post">
		<ul class="forms">
			<li><label for="post_title">Post title: </label><input name="post_title" type="text" value="{$post.title}" /></li>
			<li><label for="category">Category: </label><input name="category" type="text" value="{$post.category}" /></li>
			<li><textarea name="post_content">{stripslashes($post.content)}</textarea></li>
			<li><input name="submit_post" type="submit" value="Post" /><li>
		</ul>
		<input name="post_id" type="hidden" value="{$post.id}" />
	</form>
</div>