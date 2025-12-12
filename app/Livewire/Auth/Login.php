<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\ApiClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\SecureStorage;

class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public string $errorMessage = '';

    public string $oneTimePassword = '';

    public bool $isFixedEmail = false;

    public string $redirectTo = '/';

    public bool $displayingEmailForm = true;

    public $device = [];

    public $deviceId;

    public function mount(?string $redirectTo = null, ?string $email = ''): void
    {

        $this->email = $email;

        if ($this->email) {
            $this->isFixedEmail = true;
            $this->displayingEmailForm = false;
        }
        $this->device = json_decode(Device::getInfo());
        $this->deviceId = Device::getId();

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

            SecureStorage::set('user_id', $data['user_id']);
            SecureStorage::set('device_os', (! empty($this->device) ? $this->device->platform : null));
            SecureStorage::set('device_os_version', (! empty($this->device) ? $this->device->osVersion : null));
            SecureStorage::set('device_id', (! empty($this->deviceId) ? $this->deviceId : null));

            if(empty(SecureStorage::get('push_requested'))) SecureStorage::set('push_requested', false);

            if (config('app.env') == 'local') {
                Session::put('local_api_token', $data['access_token']);
                Session::put('local_user_id', $data['user_id']);
            }

            $this->checkDatabaseForUser($data);

            $this->redirect(route('home'));

        } else {
            $this->errorMessage = $response->json('message', 'Invalid credentials');
        }

    }

    public function checkDatabaseForUser(array $data): void
    {
        $user = User::updateOrCreate(
            ['user_id' => $data['user_id']],
            [
                'email' => $data['email'],
                'name' => $data['name'],
                'lang' => $data['lang'] ?? null,
                'phone' => $data['phone'] ?? null,
                'phone_verified_at' => isset($data['phone_verified_at']) && $data['phone_verified_at']
                    ? \Carbon\Carbon::parse($data['phone_verified_at'])
                    : null,
            ]
        );

        Auth::login($user);
    }

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
