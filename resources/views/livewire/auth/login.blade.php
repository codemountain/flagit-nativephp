<div class="flex flex-col gap-6">
{{--    <div class="text-center">--}}
{{--        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Log in to your account</h1>--}}
{{--        <p class="mt-2  text-zinc-600 dark:text-zinc-400">Enter your email and password below to log in</p>--}}
{{--        <p class="text-blue-800 dark:text-blue-200 mt-6 bg-blue-50 dark:bg-blue-900 rounded-lg shadow p-4">--}}
{{--            This is the NativePHP Mobile API Starter Kit. To get started you must connect your app to an API. The API endpoints for this app is included in the same codebase. You can simply run `herd share` or use something like `ngrock` to start up the server. Then update your `.env` with the API endpoint, then continue to register/login.--}}
{{--        </p>--}}
{{--    </div>--}}
    @if($errorMessage)
        <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
            <p class=" text-red-800 dark:text-red-200">{{ $errorMessage }}</p>
        </div>
    @endif
    @if(!$displayingEmailForm)
        <flux:card>
        <form wire:submit="submitOneTimePassword" class="space-y-8">
            <flux:input wire:model="oneTimePassword" length="4" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto" />
            <div class="space-y-4">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('one-time-passwords::form.submit_login_code_button') }}</flux:button>
                <flux:button
                    variant="outline"
                    @click="
                if (!isResending) {
                    isResending = true;
                    resendText = 'Code sent';
                    $wire.resendCode();
                    setTimeout(() => {
                        resendText = '{{ __('Resend code') }}';
                        isResending = false;
                    }, 2000);
                }
            "
                    class="w-full text-sm text-gray-600 dark:text-gray-400 cursor-pointer bg-transparent border-0 p-0 m-0 text-left transition-opacity duration-300"
                    x-text="resendText"

                >{{ __('Resend code') }}</flux:button>

            </div>
{{--            <div class="text-center">--}}
{{--                <flux:button href="{{url('/')}}" variant="outline" size="sm" class="w-full" icon="arrow-path">--}}
{{--                    {{ __('Reload application') }}--}}
{{--                </flux:button>--}}
{{--                <flux:button @click="$wire.displayEmailForm()" variant="outline" size="sm" class="w-full" icon="arrow-path">--}}
{{--                    {{ __('Go back to email') }}--}}
{{--                </flux:button>--}}
{{--            </div>--}}
        </form>
    </flux:card>
    @else
        <div>

            <form wire:submit="submitEmail" class="mt-6 space-y-6">
                <div>

                    <flux:input
                        name="email"
                        :label="__('Email')"
                        wire:model="email"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
{{--                    @error('email')--}}
{{--                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</p>--}}
{{--                    @enderror--}}
                </div>

                <div>
                    <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                        {{__('Request login code')}}
                    </flux:button>
                </div>
{{--                <div class="text-center">--}}
{{--                    <flux:button @click="$wire.displayingEmailForm(true)" variant="outline" size="sm" class="w-full">--}}
{{--                        {{__('Return to email')}}--}}
{{--                    </flux:button>--}}
{{--                </div>--}}
            </form>
        </div>
    @endif


</div>
