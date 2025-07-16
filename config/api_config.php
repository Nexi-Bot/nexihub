<?php
/**
 * API Configuration for Real Data Integration
 * This file contains all external API configurations for real data sources
 */

// Stripe Configuration for Financial Data
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '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');
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: 'pk_live_51RgSvsHxd4KTYsDdodmX55cZkcaGwzXGgARw7yvfH4d8iZhUKUiKT7MGHyboIsnoAZkmsSovqrpJh2ajldqcc7te00gdwtNGiB');

// Google Analytics Configuration (optional)
define('GA_PROPERTY_ID', getenv('GA_PROPERTY_ID') ?: '');
define('GA_SERVICE_ACCOUNT_JSON', getenv('GA_SERVICE_ACCOUNT_JSON') ?: '');

// Slack Notifications (optional)
define('SLACK_WEBHOOK_URL', getenv('SLACK_WEBHOOK_URL') ?: '');

// Company Information
define('COMPANY_LEGAL_NAME', 'Nexi Bot LTD');
define('COMPANY_TAX_ID', 'Not Applicable');
define('COMPANY_ADDRESS', 'Nexi Bot LTD, 80A Ruskin Ave, Welling, London, DA16 3QQ');

// Real Platform URLs for monitoring
define('PLATFORM_URLS', [
    'Nexi Hub' => 'https://nexihub.uk',
    'Nexi Web' => 'https://nexiweb.uk',
    'Nexi Bot' => 'https://nexibot.uk',
    'Nexi Pulse' => 'https://nexipulse.uk'

]);

// Database settings for production
define('DB_TYPE', 'sqlite'); // 'sqlite' or 'mysql'
define('DB_HOST', '65.21.61.192');
define('DB_PORT', '3306');
define('DB_USER', 'u25473_Y8CkMsMHyp');
define('DB_PASS', 'rlALotgMWdSy^8flYbx0PYS@');
define('DB_NAME', 's25473_NexiBotDatabase');

// Email configuration for notifications
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');

// Feature flags
define('USE_REAL_FINANCIAL_DATA', true); // Set to true to use Stripe API
define('USE_REAL_ANALYTICS_DATA', true); // Set to true to use Google Analytics
define('USE_MOCK_DATA_FALLBACK', false); // Set to false to disable all mock data

// API Rate limiting
define('API_RATE_LIMIT_MINUTES', 5);
define('API_CACHE_DURATION', 300); // 5 minutes cache for API responses
