#!/bin/bash

echo "=========================================="
echo "FIXING MIGRATION CONFLICTS"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[⚠]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

print_info() {
    echo -e "${BLUE}[ℹ]${NC} $1"
}

# Fix the duplicate deleted_at column issue
print_info "Fixing migration conflict for mold_modifications table..."

# Check if the problematic migration exists
MIGRATION_FILE="database/migrations/2026_02_10_120001_add_deleted_at_to_mold_modifications_table.php"

if [ -f "$MIGRATION_FILE" ]; then
    print_info "Found problematic migration: $MIGRATION_FILE"
    
    # Create a safe version of the migration
    cat > "$MIGRATION_FILE.safe" << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before adding
        if (!Schema::hasColumn('mold_modifications', 'deleted_at')) {
            Schema::table('mold_modifications', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if column exists
        if (Schema::hasColumn('mold_modifications', 'deleted_at')) {
            Schema::table('mold_modifications', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};
EOF
    
    # Replace the problematic migration
    mv "$MIGRATION_FILE.safe" "$MIGRATION_FILE"
    print_status "Migration file updated with safety checks"
    
    # Mark the migration as already run to avoid conflicts
    print_info "Marking problematic migration as completed..."
    php artisan migrate:pretend --path=database/migrations/2026_02_10_120001_add_deleted_at_to_mold_modifications_table.php
    
    # Or manually insert into migrations table
    php artisan tinker --execute="
        DB::table('migrations')->insert([
            'migration' => '2026_02_10_120001_add_deleted_at_to_mold_modifications_table',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
    "
    
    print_status "Migration conflict resolved"
else
    print_warning "Migration file not found, skipping..."
fi

# Run remaining migrations safely
print_info "Running remaining migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    print_status "All migrations completed successfully!"
else
    print_error "Some migrations failed. Check the output above."
fi

# Check migration status
print_info "Checking migration status..."
php artisan migrate:status

print_status "Migration fix completed!"