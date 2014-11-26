<?php
    session_start();
    unset($_SESSION['userType']);
    unset($_SESSION['username']);
    session_unset();
    session_destroy();
    if (isset($_GET['return']))
        $page = $_GET['return'];
    else
        $page = 'index.php';
    header('Location: ./' . $page . '?success=Thanks+for+stopping+by');
?>
