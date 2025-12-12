<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\PushNotification\TokenGenerated;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\PushNotifications;
use Native\Mobile\Facades\SecureStorage;

class PushNotification extends Component
{
    public $token = '';

    public $result = '';

    public bool $hasPermission = true;


    public function promptForPushNotifications()
    {
        PushNotifications::enroll();
        $this->result = __('Requesting push notification');
        SecureStorage::set('push_requested', true);
    }

    #[OnNative(TokenGenerated::class)]
    public function handlePushNotificationsToken($token)
    {
        if(!empty($token)){
            $this->token = $token;
            $this->sendFcm();
        } else {
            $this->hasPermission = false;
            $this->result = __('We did not receive your permission. Try again if you wish to receive notifications');
        }
    }

    protected function sendFcm()
    {
        try{
            //send as fcm_token to api end route: send-push-notification as POST
            ApiClient::post('send-push-notification',['fcm_token' => $this->token]);
            SecureStorage::set('push_notification_token', $this->token);
            $this->result = __('Thank you for using Flag!t. You should receive a Welcome notification');
            $this->hasPermission = true;
            Dialog::toast(__('Notification has been saved successfully!'));
        } catch (\Exception $e) {
            Dialog::alert(__('Oops! Error saving notification.'), $e->getMessage());
            return false;
        }

    }

    public function render()
    {
            return view('livewire.pushnotification')
                ->layout('components.layouts.app', [
                    'title' => 'Push Notification',
                ]);
    }
}
