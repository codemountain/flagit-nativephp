<div class="flex flex-col gap-2 ">
    <div class="flex w-full flex-col text-left mt-30 gap-4">
        <div class="w-full max-w-lg">
            <flux:icon.pixeltrail-apps variant="flagit" class="w-24 h-24"/>
        </div>
        <div>
        <flux:heading size="xl">{{__('Log in to your account')}}</flux:heading>
        <flux:subheading>{{__('Enter your email to request your login code')}}</flux:subheading>
            <div class="w-full max-w-lg">
                <flux:icon.logo_allapps class="w-[75%] h-24"/>
            </div>
        </div>

    </div>
    @if($errorMessage)
        <div class="rounded-lg bg-red-50 p-4 dark:bg-amber-900/20">
            <p class=" text-red-800 dark:text-red-200">{{ $errorMessage }}</p>
        </div>
    @endif
    @if(!$displayingEmailForm)
        <div class="">
        <form wire:submit="submitOneTimePassword" class="space-y-8">
            <flux:otp
                wire:model="oneTimePassword"
                length="4"
                label="Login Code"
                :error:icon="false"
                error:class="text-center"
                class="mx-auto"
                autofocus/>
            <div class="space-y-4">
                <flux:button variant="primary" type="submit" class="w-full mobile">{{ __('Submit login code') }}</flux:button>

                <flux:button
                    variant="outline"
                    class="w-full mobile"
                    wire:click="resendCode"

                >{{ __('Resend code') }}</flux:button>

            </div>
        </form>
        </div>
    @else
        <div class="-mt-10">

            <form wire:submit="submitEmail" class="mt-6 space-y-6">
                <div>

                    <flux:input
                        name="email"
                        :label="__('Email')"
                        wire:model="email"
                        type="email"
                        autocomplete="email"
                        placeholder="email@example.com"
                        autofocus
                        class="mobile"
                    />
{{--                    @error('email')--}}
{{--                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</p>--}}
{{--                    @enderror--}}
                </div>

                <div>
                    <flux:button variant="primary" type="submit" class="w-full mobile" data-test="login-button">
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

{{--    <div class="mx-12 mb-6">--}}
{{--        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" >--}}
{{--            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>--}}
{{--            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>--}}
{{--        </flux:radio.group>--}}
{{--    </div>--}}
</div>
