<?php require_once "header.php"; ?>
<script src="scripts/settingsScript.js"></script>
<h1 style="text-align: center;padding: 0.5em;">Settings</h1>
<form class="inline-form" id="settingsform">
    <label for="dark_mode">Dark Mode:</label>
    <select name="dark_mode" id="dark_mode">
        <option value="">Select Option</option>
        <option value="on">On</option>
        <option value="off">Off</option>
    </select>
    <label for="preferred_language">Language:</label>
    <select name="preferred_language" id="preferred_language">
        <option value="">Select Language</option>
        <option value="en">English</option>
        <option value="es">Spanish</option>
        <option value="fr">French</option>
    </select>
    <label for="colour_scheme">Colour Scheme:</label>
    <select name="colour_scheme" id="colour_scheme">
        <option value="">Select a colour scheme (WIP)</option>
        <option value="greyscale">Greyscale</option>
        <option value="fullcolour">Full Colour</option>
        <option value="colourblind">Colour Blind Mode</option>
    </select>
    <button id="submitsettings" type="button">Save Settings</button>
</form>
<div>
    <p id="output"></p>
</div>