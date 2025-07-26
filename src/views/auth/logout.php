<?php
require_once __DIR__ . "/../../_init_.php";

session_unset();
session_destroy(); // Destroy the session

header("Location:" . APP_URL); // Redirect to index page 
exit;
