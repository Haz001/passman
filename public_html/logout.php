<?php
session_start(["cookie_domain" => "packages.benforino.co.uk"]);
session_unset();
session_destroy();
header("location: index.php?logout=successful");
exit();
