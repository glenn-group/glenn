<h1>Blog posts</h1>
<ul>
	<? foreach ($posts as $post): ?>
		<li><?= $post->title ?> <?= $post->content ?></li>
	<? endforeach ?>
</ul>
