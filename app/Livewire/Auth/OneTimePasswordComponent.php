<?php

namespace App\Livewire\Auth;

use App\Services\ApiClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\OneTimePasswords\Rules\OneTimePasswordRule;

class OneTimePasswordComponent extends Component
{
    public ?string $email = null;

    public string $oneTimePassword = '';

    public bool $isFixedEmail = false;

    public string $errorMessage = '';

    public string $redirectTo = '/';

    public bool $displayingEmailForm = true;

    public function mount(?string $redirectTo = null, ?string $email = ''): void
    {

        $this->email = $email;

        if ($this->email) {
            $this->isFixedEmail = true;
            $this->displayingEmailForm = false;
        }

        $this->redirectTo = $redirectTo
            ?? config('one-time-passwords.redirect_successful_authentication_to');
    }

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
        $user = $this->findUser();

        if ($this->rateLimitHit()) {
            return;
        }

        $this->displayingEmailForm = false;

        $user->sendOneTimePassword();
    }

    protected function rateLimitHit(): bool
    {
        $rateLimitKey = "one-time-password-component-send-code.{$this->email}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return true;
        }

        RateLimiter::hit($rateLimitKey, 60); // 60 seconds decay time

        return false;
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

    public function render(): View
    {
        return view("one-time-passwords::livewire.{$this->showViewName()}");
    }

    /**
     * @return HasOneTimePasswords&Model&Authenticatable
     */
    protected function findUser(): ?Authenticatable
    {
        $authenticatableModel = config('auth.providers.users.model');

        return $authenticatableModel::firstWhere('email', $this->email);
    }

    public function authenticate(Authenticatable $user): void
    {
        auth()->login($user, true);
    }

    public function showViewName(): string
    {
        return $this->displayingEmailForm
            ? 'email-form'
            : 'one-time-password-form';
    }
}
