<h1>Blog posts</h1>
<ul>
	<? foreach ($posts as $post): ?>
		<li><strong><?= $post->title ?></strong> <?= $post->content ?></li>
	<? endforeach ?>
</ul>
