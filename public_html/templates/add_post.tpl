{* <div class="section addPost" id="main1">
 	{include file="bits/htmlButtons.tpl"}
 	<form name="addPost" action="?action=addpost" method="post">
 		<ul class="forms">
 			<li><label for="post_title">Post title: </label><input name="post_title" type="text" /></li>
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
 			<li><label for="pinned">Pinned: </label><input type="checkbox" name="pinned" /></li>
 			<li><textarea name="post_content"></textarea></li>
 			<li><input name="submit_post" type="submit" value="Post" /><li>
 		</ul>
 	</form>
 </div> *}

<form name="addPost" class="well form-horizontal" action="?action=addpost" method="POST">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="postTitle">Title: </label>
			<div class="controls">
				<input name="title" type="text" class="input-xxlarge" id="postTitle">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postCategory">Category: </label>
			<div class="controls">
				<select {if !$categories}disabled{/if} id="postCategory" name="category">
				{if $categories}
					{foreach $categories as $cat}
					<option value="{$cat.name}">{$cat.name}</option>
					{/foreach}
				{/if}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postTags">Tags: </label>
			<div class="controls">
				<input class="input-xxlarge" id="postTags" name="tags">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postPinned">Pinned</label>
			<div class="controls">
				<input name="pinned" id="postPinned" type="checkbox">
			</div>
		</div>
		<textarea id="postContent" name="content"></textarea>
	</fieldset>
</form>