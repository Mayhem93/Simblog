<div id="archiveBlock">
    <h2>Archives</h2>
    <ul id="archiveBlockList">
        {foreach $archives as $year}
        <li>{$year@key}
            <ul>
                {foreach $year as $month}
                <li>{$month@key}
                    <ul>
                        {foreach $month as $post}
							<li><a href="?action=post&id={$post.id}" title="{$post.title}">{$post.title}</a></li>
                        {/foreach}
                    </ul>
                </li>
                {/foreach}
            </ul>
        </li>
        {/foreach}
    </ul>
</div>