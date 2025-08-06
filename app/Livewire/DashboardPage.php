<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardPage extends Component
{

    public $count = [];

    public function mount()
    {
        $this->count['matkul'] = \App\Models\MataKuliah::count();
        $this->count['dosen'] = \App\Models\User::where('role', 'dosen')->count();
        $this->count['peminatan'] = \App\Models\Peminatan::count();
    }

    public function render()
    {
        return view('livewire.dashboard-page');
    }
}
