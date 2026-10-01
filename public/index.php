<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/CheckinController.php';
require_once __DIR__ . '/../app/controllers/MemoryController.php';
require_once __DIR__ . '/../app/controllers/LoveLetterController.php';
require_once __DIR__ . '/../app/controllers/StoryController.php';
require_once __DIR__ . '/../app/controllers/MenuController.php';
require_once __DIR__ . '/../app/controllers/TimelineController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    // Auth
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'pair':
        (new AuthController())->pair();
        break;

    // Home
    case 'home':
        (new HomeController())->index();
        break;

    // Check-in
    case 'checkin_save':
        (new CheckinController())->save();
        break;
    case 'emotions':
        (new CheckinController())->stats();
        break;

    // Memories
    case 'memories':
        (new MemoryController())->index();
        break;
    case 'memory_create':
        (new MemoryController())->create();
        break;

    // Love Letters
    case 'letters':
        (new LoveLetterController())->index();
        break;
    case 'letter_create':
        (new LoveLetterController())->create();
        break;
    case 'letter_read':
        (new LoveLetterController())->read();
        break;

    // Story
    case 'story':
        (new StoryController())->index();
        break;
    case 'story_create':
        (new StoryController())->create();
        break;

    // Timeline
    case 'timeline':
        (new TimelineController())->index();
        break;

    // Menu / Settings
    case 'settings':
        (new MenuController())->settings();
        break;
    case 'update_profile':
        (new MenuController())->updateProfile();
        break;
    case 'update_start_date':
        (new MenuController())->updateStartDate();
        break;
    case 'help':
        (new HomeController())->help();
        break;
    case 'onboarding_done':
        (new HomeController())->onboardingDone();
        break;
    default:
        (new HomeController())->index();
}
