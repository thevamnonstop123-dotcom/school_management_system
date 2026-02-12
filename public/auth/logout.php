<?php
session_start();
session_unset(); // remove all variable from array
session_destroy(); // Destroys the session entirely

header("Location: login.php");
exit();