#!/bin/bash

# Local development server startup script for NexiHub
# This script starts the PHP development server with proper routing

echo "Starting NexiHub local development server..."
echo "Server will be available at: http://localhost:8000"
echo ""
echo "Available URLs (clean URLs without .php):"
echo "  Main pages:"
echo "    - http://localhost:8000/ (home)"
echo "    - http://localhost:8000/about"
echo "    - http://localhost:8000/team"
echo "    - http://localhost:8000/careers"
echo "    - http://localhost:8000/legal"
echo "    - http://localhost:8000/contact"
echo ""
echo "  Staff pages:"
echo "    - http://localhost:8000/staff/login"
echo "    - http://localhost:8000/staff/dashboard"
echo "    - http://localhost:8000/staff/forgot-password"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Kill any existing server on port 8000
lsof -ti:8000 | xargs kill -9 2>/dev/null
sleep 1

# Start PHP development server with custom router
php -S localhost:8000 router.php
