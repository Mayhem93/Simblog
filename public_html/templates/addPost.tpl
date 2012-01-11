<div class="section addPost" id="main1">
	<p>HTML control buttons goes here !</p>
	<form name="addPost" action="?action=addpost" method="post">
		<ul class="forms">
			<li><label for="post_title">Post title: </label><input name="post_title" type="text" /></li>
			<li><label for="category">Category: </label><input name="category" type="text" /></li>
			<li><label for="pinned">Pinned: </label><input type="checkbox" name="pinned" /></li>
			<li><textarea name="post_content"></textarea></li>
			<li><input name="submit_post" type="submit" value="Post" /><li>
		</ul>
	</form>
</div>