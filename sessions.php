<?php
session_start();
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
    echo $_SESSION['count'];
} else {
    $_SESSION['count']++;
    echo $_SESSION['count'];
}
if (isset($_SESSION['count']) && ($_SESSION['count'] >= 10)) {
    unset($_SESSION['count']);
}
?>