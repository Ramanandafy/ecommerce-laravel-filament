<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title("Login")]
class LoginPage extends Component
{
    public $email;
    public$password;

    public function save(){
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('error', 'Le mot de passe est incorecte');
            return;
        }
        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
