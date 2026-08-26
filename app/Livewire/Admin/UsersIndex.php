<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public string $searchTerm = '';
    public string $activeTab = 'all';

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('roles')->latest();

        if ($this->activeTab !== 'all' && in_array($this->activeTab, ['student', 'teacher', 'parent'])) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $this->activeTab));
        }

        if (trim($this->searchTerm) !== '') {
            $searchTerm = '%' . trim($this->searchTerm) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->paginate(15);

        $roleCounts = [
            'students' => User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->count(),
            'teachers' => User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->count(),
            'parents'  => User::whereHas('roles', fn ($q) => $q->where('name', 'parent'))->count(),
            'all'      => User::count(),
        ];

        return view('livewire.admin.users-index', compact('users', 'roleCounts'));
    }
}
