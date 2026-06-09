<section>
	<header>
		<h1>{@contribution.confirmed}</h1>
	</header>
	<article>
		<div class="content">
			<div class="message-helper bgc success">{@H|contribution.confirmed.messages}</div>
			<p class="align-center">
				<a class="button offload" href="${relative_url(DictionaryUrlBuilder::home())}">{@dictionary.module.title}</a>
				<a class="button offload" href="${Url::to_rel('dictionary.php?add=1')}">{@dictionary.add.item}</a>
			</p>
		</div>
	</article>
	<footer></footer>
</section>
