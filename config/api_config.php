<?php
/**
 * API Configuration for Real Data Integration
 * This file contains all external API configurations for real data sources
 */

// Stripe Configuration for Financial Data
if (!defined('STRIPE_SECRET_KEY')) {
    define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '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');
}
if (!defined('STRIPE_PUBLISHABLE_KEY')) {
    define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: 'pk_live_51RgSvsHxd4KTYsDdodmX55cZkcaGwzXGgARw7yvfH4d8iZhUKUiKT7MGHyboIsnoAZkmsSovqrpJh2ajldqcc7te00gdwtNGiB');
}

// Google Analytics Configuration (optional)
if (!defined('GA_PROPERTY_ID')) {
    define('GA_PROPERTY_ID', getenv('GA_PROPERTY_ID') ?: '');
}
if (!defined('GA_SERVICE_ACCOUNT_JSON')) {
    define('GA_SERVICE_ACCOUNT_JSON', getenv('GA_SERVICE_ACCOUNT_JSON') ?: '');
}

// Slack Notifications (optional)
if (!defined('SLACK_WEBHOOK_URL')) {
    define('SLACK_WEBHOOK_URL', getenv('SLACK_WEBHOOK_URL') ?: '');
}

// Company Information
if (!defined('COMPANY_LEGAL_NAME')) {
    define('COMPANY_LEGAL_NAME', 'Nexi Bot LTD');
}
if (!defined('COMPANY_TAX_ID')) {
    define('COMPANY_TAX_ID', 'Not Applicable');
}
if (!defined('COMPANY_ADDRESS')) {
    define('COMPANY_ADDRESS', 'Nexi Bot LTD, 80A Ruskin Ave, Welling, London, DA16 3QQ');
}

// Real Platform URLs for monitoring
if (!defined('PLATFORM_URLS')) {
    define('PLATFORM_URLS', [
        'Nexi Hub' => 'https://nexihub.uk',
        'Nexi Web' => 'https://nexiweb.uk',
        'Nexi Bot' => 'https://nexibot.uk',
        'Nexi Pulse' => 'https://nexipulse.uk'
    ]);
}

// Database settings for production (only if not already defined)
if (!defined('DB_TYPE')) {
    define('DB_TYPE', 'mysql'); // Using MySQL since credentials are provided
}
if (!defined('DB_HOST')) {
    define('DB_HOST', '65.21.61.192');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', '3306');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'u25473_Y8CkMsMHyp');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'rlALotgMWdSy^8flYbx0PYS@');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 's25473_NexiBotDatabase');
}

// Email configuration for notifications
if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
}
if (!defined('SMTP_PORT')) {
    define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
}
if (!defined('SMTP_USER')) {
    define('SMTP_USER', getenv('SMTP_USER') ?: '');
}
if (!defined('SMTP_PASS')) {
    define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
}

// Feature flags
if (!defined('USE_REAL_FINANCIAL_DATA')) {
    define('USE_REAL_FINANCIAL_DATA', true); // Set to true to use Stripe API
}
if (!defined('USE_REAL_ANALYTICS_DATA')) {
    define('USE_REAL_ANALYTICS_DATA', true); // Set to true to use Google Analytics
}
if (!defined('USE_MOCK_DATA_FALLBACK')) {
    define('USE_MOCK_DATA_FALLBACK', false); // Set to false to disable all mock data
}

// API Rate limiting
if (!defined('API_RATE_LIMIT_MINUTES')) {
    define('API_RATE_LIMIT_MINUTES', 5);
}
if (!defined('API_CACHE_DURATION')) {
    define('API_CACHE_DURATION', 300); // 5 minutes cache for API responses
}
