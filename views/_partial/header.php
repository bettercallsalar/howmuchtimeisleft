<!-- header.php -->
<!doctype html>
<html lang="fr" data-bs-theme="auto">

<head>

	<title>How Much Time Is Left?</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body>
	<?php include("nav.php"); ?>

	<main class="container mt-4">
		<div class="p-4 p-md-5 mb-4 rounded bg-light">
			<div class="col-lg-6 px-0">
				<h1 class="display-4 fst-italic"><?php echo $strTitleH1; ?></h1>
				<p class="my-3"><?php echo $strFirstP; ?></p>
			</div>
		</div>

		<?php
		if (isset($_SESSION['valid'])) {
			echo "<p class='alert alert-success'>" . $_SESSION['valid'] . "</p>";
			unset($_SESSION['valid']);
		}
		if (isset($_SESSION['error'])) {
			echo "<p class='alert alert-danger'>" . $_SESSION['error'] . "</p>";
			unset($_SESSION['error']);
		}
		?>