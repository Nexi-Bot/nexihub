#!/bin/bash

# Script to remove all sample/mock data files and clean up the database

echo "🧹 Cleaning up all sample/mock data from Nexi Hub..."

# Remove sample data initialization files
echo "📂 Removing sample data files..."

if [ -f "/Users/macbook/Downloads/NexiHub/staff/init_sample_data.php" ]; then
    rm "/Users/macbook/Downloads/NexiHub/staff/init_sample_data.php"
    echo "   ✓ Removed init_sample_data.php"
fi

# Clean the existing database to remove any sample data
echo "🗄️ Cleaning database of sample data..."
php << 'EOF'
<?php
$db_path = "/Users/macbook/Downloads/NexiHub/database/nexihub.db";

if (file_exists($db_path)) {
    $db = new PDO("sqlite:$db_path");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Clear all tables but keep structure
    $tables = ['staff', 'projects', 'financial_records', 'time_off_requests', 'activity_log', 'platforms'];
    
    foreach ($tables as $table) {
        try {
            $db->exec("DELETE FROM $table");
            echo "   ✓ Cleared $table table\n";
        } catch (Exception $e) {
            echo "   ⚠ Table $table doesn't exist or couldn't be cleared\n";
        }
    }
    
    echo "   ✓ Database cleaned of all sample data\n";
} else {
    echo "   ⚠ Database file not found\n";
}
EOF

echo ""
echo "🎯 Sample data cleanup complete!"
echo "💡 Dashboard now uses only real data sources:"
echo "   • Financial data from Stripe API (when configured)"
echo "   • Staff/project data from actual database entries"
echo "   • No mock or sample data fallbacks"
echo ""
echo "🔧 To add real data:"
echo "   1. Configure Stripe API keys in /config/api_config.php"
echo "   2. Add real staff via the dashboard interface"
echo "   3. Add real projects via the dashboard interface" 
echo "   4. Financial data will come from Stripe automatically"
echo ""
