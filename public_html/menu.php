<?php require_once "header.php"; ?>
<script src="/scripts/menu.js"></script>
<style>
	button::after {
		content: attr(name);
		display: block;
		color: gray;
	}
</style>
<button id="refresh">Refresh</button>
<div id="list"></div>
<div id="passwords"></div>
<?php require "footer.php"; ?>