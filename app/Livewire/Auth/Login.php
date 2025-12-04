<?php

namespace App\Livewire\Auth;

use App\Services\ApiClient;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;

class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public string $errorMessage = '';

    public string $oneTimePassword = '';

    public bool $isFixedEmail = false;

    public string $redirectTo = '/';

    public bool $displayingEmailForm = false;

    public function mount(?string $redirectTo = null, ?string $email = ''): void
    {

        $this->email = $email;

        if ($this->email) {
            $this->isFixedEmail = true;
            $this->displayingEmailForm = false;
        }

    }

//    public function login(): void
//    {
//        $this->validate();
//
//        $this->errorMessage = '';
//
//        $response = ApiClient::post('otp-email', [
//            'email' => $this->email,
////            'password' => $this->password,
//        ]);
//
//        if ($response->successful()) {
//            $data = $response->json();
//            SecureStorage::set('api_token', $data['token']);
//            SecureStorage::set('user_name', $data['user']['name']);
//            SecureStorage::set('user_email', $data['user']['email']);
//
//            $this->redirect(route('home'));
//        } else {
//            $this->errorMessage = $response->json('message', 'Invalid credentials');
//        }
//    }
//
//    public function skipLogin()
//    {
//        SecureStorage::set('api_token', Str::uuid()->toString());
//        SecureStorage::set('user_name', 'Simon Hamp');
//        SecureStorage::set('user_email', 'simon@nativephp.com');
//        $this->redirect(route('home'));
//    }

    public function submitEmail(): void
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $response = ApiClient::post('/otp-email', [
            'email' => $this->email,
        ]);


        $this->displayingEmailForm = false;
    }

    public function resendCode(): void
    {
        $this->sendCode();
    }

    protected function sendCode(): void
    {

        $response = ApiClient::post('/otp-email', [
            'email' => $this->email,
        ]);

        $this->displayingEmailForm = false;

        $this->errorMessage = $response->json('message', 'Code sent to your email');

    }

    public function displayEmailForm(): void
    {
        $this->email = null;

        $this->displayingEmailForm = true;
    }

    public function submitOneTimePassword()
    {

        $response = ApiClient::post('/otp-code', [
            'email' => $this->email,
            'code' => $this->oneTimePassword,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            SecureStorage::set('api_token', $data['access_token']);
            SecureStorage::set('user_name', $data['name']);
            SecureStorage::set('user_email', $data['email']);
            SecureStorage::set('user_lang', $data['lang']);
            SecureStorage::set('user_phone', $data['phone']);
            SecureStorage::set('user_phone_verified_at', $data['phone_verified_at']);

            $this->redirect(route('home'));
        } else {
            $this->errorMessage = $response->json('message', 'Invalid credentials');
        }

    }

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
