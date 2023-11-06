<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UsersIndex extends Component
{
    use WithPagination;

    public $search;

    protected $paginationTheme = "bootstrap";

    public function updatingSearch(){
        $this->resertPage();
    }

    public function render()
    {
        $users = User::where('name', 'LIKE' , '%' . $this->search . '%')
        ->orWhere('email', 'LIKE' , '%' . $this->search . '%')
        ->paginate();
        return view('livewire.admin.users-index', compact('users'));
    }
}
