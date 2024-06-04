<?php
if (!defined('INCLUDED')) {
    // If not included, redirect to an error page or any other page you prefer
    header("Location: index.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="sidebar-container">
        <div class="sidebar">
            <header><img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client-2" alt="Browlesque Logo"></header>
            <h4 class="management mt-3 ms-4">Management</h4>
            <a href="dashboard.php" <?php if ($current_page == 'dashboard.php') echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/Dashboard-Icon.svg" alt="Dashboard">
                <span class="sb-label">Dashboard</span>
            </a>
            <a href="clients.php" <?php if ($current_page == 'clients.php') echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/Clients-Icon.svg" alt="Clients">
                <span class="sb-label">Clients</span>
            </a>
            <a href="calendar.php" <?php if ($current_page == 'calendar.php') echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/Calendar-Icon.svg" alt="Calendar">
                <span class="sb-label">Calendar</span>
            </a>
            <h4 class="content-desk mt-3 ms-4">Content Desk</h4>
            <a href="display_services.php" <?php if (in_array($current_page, ['display_services.php', 'services.php', 'edit_services.php'])) echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/Services-Icon.svg" alt="Services">
                <span class="sb-label">Services</span>
            </a>
            <a href="display_promos.php" <?php if (in_array($current_page, ['display_promos.php', 'promos.php', 'edit_promos.php'])) echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/Promos-Icon.svg" alt="Promos">
                <span class="sb-label">Promos</span>
            </a>
            <a href="archive.php" <?php if ($current_page == 'archive.php') echo 'class="active-link"'; ?>>
                <img src="./assets/images/icon/archive.svg" alt="Archive">
                <span class="sb-label">Archive</span>
            </a>
            <a href="logout.php" class="logout-link logout-button">
                <img src="./assets/images/icon/Logout-Icon.svg" alt="Logout">
                <span class="text nav-text sb-label">Logout</span>
            </a>
        </div>
    </div>
</body>
</html>