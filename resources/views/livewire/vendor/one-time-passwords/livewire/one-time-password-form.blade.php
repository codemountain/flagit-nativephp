<div x-data="{ resendText: '{{ __('one-time-passwords::form.resend_code') }}', isResending: false }">
{{--    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">--}}
{{--        {{ __('one-time-passwords::form.one_time_password_form_title') }}--}}
{{--    </h2>--}}
    <flux:card>
        <form wire:submit="submitOneTimePassword" class="space-y-8">
            <div class="max-w-64 mx-auto space-y-2">
                <flux:heading size="lg" class="text-center">{{ __('one-time-passwords::form.one_time_password_form_title') }}</flux:heading>
                <flux:text class="text-center">{{__('Please enter a one-time password sent to you')}}</flux:text>
            </div>
            <flux:otp wire:model="oneTimePassword" length="4" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto" />
            <div class="space-y-4">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('one-time-passwords::form.submit_login_code_button') }}</flux:button>
                <flux:button
                    @click="
                if (!isResending) {
                    isResending = true;
                    resendText = 'Code sent';
                    $wire.resendCode();
                    setTimeout(() => {
                        resendText = '{{ __('one-time-passwords::form.resend_code') }}';
                        isResending = false;
                    }, 2000);
                }
            "
                    class="w-full text-sm text-gray-600 dark:text-gray-400 cursor-pointer bg-transparent border-0 p-0 m-0 text-left transition-opacity duration-300"
                    x-text="resendText"

                >{{__('Resend code<')}}</flux:button>

            </div>
            <div class="text-center">
                <flux:button href="{{url('/')}}" variant="outline" size="sm" class="w-full" icon="arrow-path">
                    {{ __('Reload application') }}
                </flux:button>
                <flux:button @click="$wire.displayingEmailForm(true)" variant="outline" size="sm" class="w-full" icon="arrow-path">
                    {{ __('Go back to email) }}
                </flux:button>
            </div>
        </form>
    </flux:card>
{{--    <form wire:submit="submitOneTimePassword" class="mt-6 space-y-6">--}}
{{--        <div>--}}
{{--            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">--}}
{{--                {{ __('one-time-passwords::form.password_label') }}--}}
{{--            </label>--}}
{{--            <input--}}
{{--                class="p-2 mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"--}}
{{--                type="text"--}}
{{--                id="one_time_password"--}}
{{--                wire:model="oneTimePassword"--}}
{{--            >--}}
{{--            @error('oneTimePassword')--}}
{{--            <p class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</p>--}}
{{--            @enderror--}}
{{--        </div>--}}

{{--        <div>--}}
{{--            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">--}}
{{--                {{ __('one-time-passwords::form.submit_login_code_button') }}--}}
{{--            </button>--}}
{{--        </div>--}}

{{--        <button--}}
{{--            type="button"--}}
{{--            @click="--}}
{{--                if (!isResending) {--}}
{{--                    isResending = true;--}}
{{--                    resendText = 'Code sent';--}}
{{--                    $wire.resendCode();--}}
{{--                    setTimeout(() => {--}}
{{--                        resendText = '{{ __('one-time-passwords::form.resend_code') }}';--}}
{{--                        isResending = false;--}}
{{--                    }, 2000);--}}
{{--                }--}}
{{--            "--}}
{{--            class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer bg-transparent border-0 p-0 m-0 text-left transition-opacity duration-300"--}}
{{--            :class="{ 'underline': !isResending }"--}}
{{--            x-text="resendText"--}}
{{--        ></button>--}}
{{--    </form>--}}
</div>
