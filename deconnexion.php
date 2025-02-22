<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    header('Location: http://localhost:8000/formConnection.html');
    exit();
?>