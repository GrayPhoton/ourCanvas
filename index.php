<?php
ini_set('allow_url_fopen',1);

if( !headers_sent() && '' == session_id() ) {
    session_start();
}

switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'home.php';
        break;
    case '/home.php':
        require 'home.php';
        break;
    case '/login.php':
        require 'login.php';
        break;
    case '/register.php':
        require 'register.php';
        break;
    case '/course.php':
        require 'course.php';
        break;
    case '/assignment.php':
        require 'assignment.php';
        break;
    case '/main.php':
        require 'main.php';
        break;
    case 'filter_invites.php':
        require 'filter_invites.php';
        break;
    default:
        http_response_code(404);
        echo @parse_url($_SERVER['REQUEST_URI'])['path'];
        echo ' Not Found';
        break;
}