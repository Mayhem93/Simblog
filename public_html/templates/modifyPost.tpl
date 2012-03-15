<div class="section modifyPost" id="main1">
	{include file="bits/htmlButtons.tpl"}
	<form name="addPost" action="?action=modifyPost" method="post">
		<ul class="forms">
			<li><label for="post_title">Post title: </label><input name="post_title" type="text" value="{$post.title}" /></li>
			<li><label for="category">Category: </label>
				<select name="category">
				{if $categories}
					{foreach $categories as $cat}
					<option value="{$cat.name}">{$cat.name}</option>
					{/foreach}
				{else}
					<option style="color: gray">no categories available</option>
				{/if}
				</select>
			</li>
			<li><textarea name="post_content">{$post.content}</textarea></li>
			<li><input name="submit_post" type="submit" value="Post" /><li>
		</ul>
		<input name="post_id" type="hidden" value="{$post.id}" />
	</form>
</div>