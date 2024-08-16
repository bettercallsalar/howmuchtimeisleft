<nav class="nav nav-underline justify-content-between">
	<a class="nav-item nav-link link-body-emphasis <?php echo (($strPage == "index")?"active":""); ?>" href="index.php">Accueil</a>
	<a class="nav-item nav-link link-body-emphasis <?php echo (($strPage == "about")?"active":""); ?>" href="index.php?ctrl=page&action=about">A propos</a>
	<a class="nav-item nav-link link-body-emphasis <?php echo (($strPage == "blog")?"active":""); ?>" href="index.php?ctrl=article&action=blog">Blog</a>
	<a class="nav-item nav-link link-body-emphasis <?php echo (($strPage == "contact")?"active":""); ?>" href="index.php?ctrl=page&action=contact">Contact</a>
</nav>
