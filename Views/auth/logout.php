<?php
require_once __DIR__ . "/../../Config/Url.php";

session_start();
session_destroy();

header("Location: " . VIEW_URL . "/auth/login");
exit();
