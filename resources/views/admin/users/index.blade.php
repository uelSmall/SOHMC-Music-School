<x-layouts.admin :title="'Users'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Admin Dashboard', 'route' => route('admin.dashboard')],
            ['label' => 'Users', 'current' => true],
        ]" />

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                <p class="text-sm text-gray-500">Manage all user accounts by role.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="soh-btn-primary inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add User
            </a>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <livewire:admin.users-index />
    </div>
</x-layouts.admin>
