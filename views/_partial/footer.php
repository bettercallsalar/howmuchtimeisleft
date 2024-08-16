</main>

<footer class="py-5 text-center text-body-secondary bg-body-tertiary">
	<p>Créé par <a href="https://ce-formation.com/" class="text-decoration-none">CE FORMATION</a>.</p>
	<p>
		<a href="index.php?ctrl=page&action=mentions" class="text-decoration-none">Mentions légales</a>
		| <a href="index.php?ctrl=page&action=contact" class="text-decoration-none">Contact</a>
	</p>
	<p class="mb-0">
		<a href="#" class="text-decoration-none">Back to top</a>
	</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<?php if ($strPage == "blog") { ?>
	<script>
		changePeriod();
	</script>
<?php } ?>
</body>

</html>