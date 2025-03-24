<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mot de passe oublier')]
class ForgotPasswordPage extends Component
{
    public $email;
    public function save(){
        $this->validate([
      'email' => 'required|email|exists:users,email'
        ]);
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT){
            session()->flash('success','Votre code de confirmation est envoyé par votre adresse email!');
            $this->email = '';
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
