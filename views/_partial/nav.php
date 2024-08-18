<nav class="navbar navbar-expand-lg border-bottom">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">How Much Time Is Left?</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link <?php echo ($strPage == "index") ? "active" : ""; ?>" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo ($strPage == "life_experience") ? "active" : ""; ?>" href="index.php?Controller=experience&Action=lifeExperience">Life Experience</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo ($strPage == "create_experience") ? "active" : ""; ?>" href="index.php?Controller=experience&Action=createExperience">Share Experience</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo ($strPage == "about") ? "active" : ""; ?>" href="index.php?Controller=page&Action=about">About</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo ($strPage == "contact") ? "active" : ""; ?>" href="index.php?Controller=page&Action=contact">Contact</a>
				</li>
				<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == "administrator") { ?>
					<li class="nav-item">
						<a class="nav-link <?php echo ($strPage == "admin") ? "active" : ""; ?>" href="index.php?Controller=admin&Action=dashboard">Admin</a>
					</li>
				<?php } ?>
			</ul>
			<ul class="navbar-nav ms-auto">
				<?php if (isset($_SESSION['user'])) { ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fas fa-user"></i> <?php echo $_SESSION['user']['username']; ?>
						</a>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
							<li><a class="dropdown-item" href="index.php?Controller=user&Action=editProfile">My Account</a></li>
							<li>
								<hr class="dropdown-divider">
							</li>
							<li><a class="dropdown-item" href="index.php?Controller=user&Action=logout">Logout</a></li>
						</ul>
					</li>
				<?php } else { ?>
					<li class="nav-item">
						<a class="nav-link" href="index.php?Controller=user&Action=createAccount" title="Sign Up">
							<i class="fas fa-user-plus"></i> Create Account
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="index.php?Controller=user&Action=login" title="Login">
							<i class="fas fa-sign-in-alt"></i> Login
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>