<!-- <a href="{{ url(optional($news->category)->slug . '/' .$news->slug) }}.html#disqus_thread">Comments</a> -->
<div id="disqus_thread"></div>
<!-- Count comment disqus -->
<script id="dsq-count-scr" src="//http-127-0-0-1-8000-5tfntgctby.disqus.com/count.js" async></script>
<script>
	var disqus_config = function () {
		this.page.url = "{{ url(optional($news->category)->slug . '/' .$news->slug) }}.html";  // Replace PAGE_URL with your page's canonical URL variable
		this.page.identifier = "post-{{ $news->slug }}"; // Replace PAGE_IDENTIFIER  
	};

		(function() { // DON'T EDIT BELOW THIS LINE
		var d = document, s = d.createElement('script');
		s.src = 'https://http-natugi-com.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
		})();
</script>

