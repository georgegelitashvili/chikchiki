<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateChat extends Component
{

    public function render()
    {
        return view('livewire.chat.create-chat');
    }

}
