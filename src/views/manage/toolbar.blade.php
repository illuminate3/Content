
<nav class="content_toolbar navbar navbar-default navbar-fixed-bottom" role="content">
		<ul class="nav navbar-nav pull-right">
			<li>
				@if($mode == 'view')
				<a href="?mode=content"><i class="icon-th-list"></i> Switch to content mode</a>
				@else
				<a href="?mode=view"><i class="icon-eye-open"></i> Switch to view mode</a>
				@endif
			</li>
		</ul>
</nav>