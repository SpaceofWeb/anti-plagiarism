<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="/">АнтиПлагиат</a>
	<div class="collapse navbar-collapse" id="navbarCollapse">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item <?=($url['path'] == '/') ? 'active' : ''; ?>">
				<a class="nav-link" href="/">Дипломные</a>
			</li>
			<li class="nav-item <?=($url['path'] == '/add.php') ? 'active' : ''; ?>">
				<a class="nav-link" href="add.php">Добавить</a>
			</li>
			<li class="nav-item <?=($url['path'] == '/change.php') ? 'active' : ''; ?>">
				<a class="nav-link" href="change.php">Изменить</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="view.php">Просмотр</a>
			</li>
		</ul>
	</div>
</nav>