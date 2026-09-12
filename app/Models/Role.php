<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * Name should be lowercase.
     *
     * @param  string  $value  Name value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Human-readable label for a role name.
     */
    public static function labelFor(string $role): string
    {
        return self::colorMap()[$role]['label'] ?? ucwords($role);
    }

    /**
     * Tailwind classes for a role badge.
     */
    public static function badgeFor(string $role): string
    {
        return self::colorMap()[$role]['badge'] ?? 'bg-gray-100 text-gray-600';
    }

    /**
     * Tailwind classes for a role avatar circle.
     */
    public static function avatarFor(string $role): string
    {
        return self::colorMap()[$role]['avatar'] ?? 'bg-gray-500';
    }

    /**
     * Bootstrap badge class for the CoreUI backend tables.
     */
    public static function bootstrapBadgeFor(string $role): string
    {
        return self::colorMap()[$role]['bootstrap'] ?? 'badge-light';
    }

    /**
     * Single source of truth for role colors and display labels.
     *
     * @return array<string, array{label: string, badge: string, avatar: string, bootstrap: string}>
     */
    public static function colorMap(): array
    {
        return [
            'student' => [
                'label' => 'Student',
                'badge' => 'bg-blue-100 text-blue-700',
                'avatar' => 'bg-blue-500',
                'bootstrap' => 'badge-info',
            ],
            'teacher' => [
                'label' => 'Teacher',
                'badge' => 'bg-purple-100 text-purple-700',
                'avatar' => 'bg-purple-500',
                'bootstrap' => 'badge-success',
            ],
            'parent' => [
                'label' => 'Parent',
                'badge' => 'bg-orange-100 text-orange-800',
                'avatar' => 'bg-orange-500',
                'bootstrap' => 'badge-warning',
            ],
            'administrator' => [
                'label' => 'Administrator',
                'badge' => 'bg-red-100 text-red-700',
                'avatar' => 'bg-red-500',
                'bootstrap' => 'badge-danger',
            ],
            'super admin' => [
                'label' => 'Super Admin',
                'badge' => 'bg-fuchsia-100 text-fuchsia-700',
                'avatar' => 'bg-fuchsia-600',
                'bootstrap' => 'badge-primary',
            ],
        ];
    }

    /**
     * Get the count of users assigned to this role.
     *
     * @return int
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
