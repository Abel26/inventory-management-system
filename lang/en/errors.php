<?php

return [
    'meta_title' => 'Error {code} - Ebara Inventory Management System',
    'meta_description' => 'Error {code} page - Ebara Inventory Management System',
    
    '404' => [
        'title' => 'Page Not Found',
        'subtitle' => 'Oops! The page you\'re looking for doesn\'t exist or has been moved.',
        'description' => 'It seems you\'ve gotten lost in our inventory world. Let us help you find your way back.',
        'actions' => [
            'home' => 'Back to Home',
            'dashboard' => 'Go to Dashboard',
            'search' => 'Search Assets',
            'contact' => 'Contact Us'
        ],
        'suggestions' => [
            'title' => 'Maybe You\'re Looking For:',
            'dashboard' => 'Inventory Dashboard',
            'assets' => 'Asset List',
            'reports' => 'Reports',
            'login' => 'Login to System'
        ]
    ],
    
    '500' => [
        'title' => 'Internal Server Error',
        'subtitle' => 'Oops! Something went wrong on our server.',
        'description' => 'Our team has been notified about this issue and is working to fix it. Please try again in a few moments.',
        'actions' => [
            'refresh' => 'Refresh Page',
            'home' => 'Back to Home',
            'dashboard' => 'Go to Dashboard',
            'report' => 'Report Issue'
        ],
        'reassurance' => 'Don\'t worry, your data is safe and unaffected by this error.'
    ],
    
    '403' => [
        'title' => 'Access Denied',
        'subtitle' => 'Oops! You don\'t have permission to access this page.',
        'description' => 'It seems you\'re trying to access an area that requires special permissions. Please log in with the appropriate account or contact an administrator.',
        'actions' => [
            'login' => 'Login to System',
            'home' => 'Back to Home',
            'dashboard' => 'Go to Dashboard',
            'contact' => 'Contact Administrator'
        ],
        'help' => 'If you believe you should have access, please contact our IT team.'
    ],
    
    '419' => [
        'title' => 'Page Expired',
        'subtitle' => 'Oops! Your session has expired.',
        'description' => 'For your security, active sessions are only valid for a limited time. Please refresh the page and try again.',
        'actions' => [
            'refresh' => 'Refresh Page',
            'login' => 'Login Again',
            'home' => 'Back to Home'
        ],
        'security_note' => 'This is a security feature to protect your account.'
    ],
    
    '429' => [
        'title' => 'Too Many Requests',
        'subtitle' => 'Oops! You\'ve exceeded the allowed request limit.',
        'description' => 'To maintain system performance, we limit the number of requests within a certain time period. Please wait a moment before trying again.',
        'actions' => [
            'wait' => 'Wait {seconds} Seconds',
            'refresh' => 'Try Again',
            'home' => 'Back to Home'
        ],
        'rate_limit_info' => 'The request limit will reset automatically.'
    ],
    
    'common' => [
        'error_code' => 'Error Code: {code}',
        'timestamp' => 'Time: {time}',
        'help_text' => 'Need help? Contact our support team.',
        'back_button' => 'Go Back',
        'continue_button' => 'Continue'
    ]
];