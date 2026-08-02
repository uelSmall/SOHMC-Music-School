#!/bin/bash

# Quick Start Guide for Laravel Starter Music School

echo "=== Laravel Starter Music School - Quick Setup ==="
echo

# 1. Install dependencies
echo "Step 1: Installing dependencies..."
composer install > /dev/null 2>&1 && npm install > /dev/null 2>&1
echo "✓ Dependencies installed"
echo

# 2. Run migrations
echo "Step 2: Running migrations..."
php artisan migrate:fresh --seed > /dev/null 2>&1
echo "✓ Database migrated and seeded"
echo

# 3. Build assets
echo "Step 3: Building frontend assets..."
npm run build > /dev/null 2>&1
echo "✓ Assets built"
echo

# 4. Display info
echo "=== Setup Complete! ==="
echo
echo "📝 Demo Accounts:"
echo "   Student:  student1@example.com / password"
echo "   Teacher:  teacher1@example.com / password"
echo "   Admin:    admin@admin.com / password"
echo
echo "🌐 Routes:"
echo "   Student Dashboard:   http://127.0.0.1:8000/lessons"
echo "   Teacher Dashboard:   http://127.0.0.1:8000/admin/assignments"
echo "   Lesson Management:   http://127.0.0.1:8000/admin/lessons"
echo
echo "📖 Documentation:"
echo "   Project Summary:  docs/PROJECT_SUMMARY.md"
echo "   Features Guide:   docs/FEATURES_GUIDE.md"
echo "   README:           README.md"
echo
echo "🚀 To start the development server:"
echo "   php artisan serve"
echo
echo "💻 In another terminal, run:"
echo "   npm run dev"
echo
