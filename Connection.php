<?php
session_start();

// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CEMS Dashboard</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Remix Icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex font-sans">

<!-- Sidebar -->
<aside class="w-64 bg-white shadow-xl p-6 hidden md:block">
    <div class="flex items-center gap-3 mb-8">
        <img src="img/Community Logo.png" class="w-14 h-14 rounded-md" alt="Logo">
        <h2 class="text-2xl font-bold text-gray-800">CEMS</h2>
    </div>

    <nav class="space-y-4">
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 font-medium sidebar-link active" data-section="home">
            <i class="ri-dashboard-line text-xl text-blue-500"></i> Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-green-600 font-medium sidebar-link" data-section="beneficiary">
            <i class="ri-user-3-line text-xl text-green-500"></i> Beneficiary Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-yellow-600 font-medium sidebar-link" data-section="program">
            <i class="ri-archive-line text-xl text-yellow-500"></i> Program Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-purple-600 font-medium sidebar-link" data-section="project">
            <i class="ri-folder-2-line text-xl text-purple-500"></i> Project Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-pink-600 font-medium sidebar-link" data-section="activity">
            <i class="ri-calendar-line text-xl text-pink-500"></i> Activity Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-teal-600 font-medium sidebar-link" data-section="partner">
            <i class="ri-hand-heart-line text-xl text-teal-500"></i> Partner & Donor Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-indigo-600 font-medium sidebar-link" data-section="resource">
            <i class="ri-stack-line text-xl text-indigo-500"></i> Resource Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-orange-600 font-medium sidebar-link" data-section="location">
            <i class="ri-map-pin-line text-xl text-orange-500"></i> Location Management
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-cyan-600 font-medium sidebar-link" data-section="staff">
            <i class="ri-team-line text-xl text-cyan-500"></i> Coordinator / Staff / Volunteers
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-red-600 font-medium sidebar-link" data-section="evaluation">
            <i class="ri-survey-line text-xl text-red-500"></i> Evaluation & Monitoring
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-gray-600 font-medium sidebar-link" data-section="reports">
            <i class="ri-bar-chart-2-line text-xl text-gray-500"></i> Reports & Analytics
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-red-600 font-medium sidebar-link" data-section="logout">
            <i class="ri-logout-box-line text-xl text-red-500"></i> Logout
        </a>
    </nav>
</aside>

<!-- Main Content -->
<main class="flex-1 p-8" id="main-content">
    <?php
    // Load the appropriate section based on URL parameter or default to home
    $section = isset($_GET['section']) ? $_GET['section'] : 'home';
    $sectionFile = "sections/{$section}.php";
    
    if (file_exists($sectionFile)) {
        include($sectionFile);
    } else {
        include("sections/home.php");
    }
    ?>
</main>

<script>
// Navigation functionality
const sidebarLinks = document.querySelectorAll('.sidebar-link');

function navigateToSection(section) {
    // Update URL without page reload using History API
    const url = new URL(window.location);
    url.searchParams.set('section', section);
    window.history.pushState({}, '', url);
    
    // Load section content via AJAX
    loadSection(section);
}

function loadSection(section) {
    fetch(`sections/${section}.php`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('main-content').innerHTML = html;
            updateActiveLink(section);
            initializeSectionScripts(section);
        })
        .catch(error => {
            console.error('Error loading section:', error);
            document.getElementById('main-content').innerHTML = '<p>Error loading section. Please try again.</p>';
        });
}

function updateActiveLink(section) {
    sidebarLinks.forEach(link => {
        link.classList.remove('text-blue-600', 'font-bold');
        if (link.dataset.section === section) {
            link.classList.add('text-blue-600', 'font-bold');
        }
    });
}

function initializeSectionScripts(section) {
    // Initialize scripts specific to each section
    // This would contain the JavaScript logic for each section
    // For now, we'll reload the page to ensure scripts run
    // In a production environment, you'd want to modularize the JavaScript
    window.location.reload();
}

// Event listeners for sidebar navigation
sidebarLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        navigateToSection(link.dataset.section);
    });
});     

// Handle browser back/forward buttons
window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'home';
    loadSection(section);
});

// Load initial section
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'home';
    updateActiveLink(section);
});
</script>

</body>
</html>