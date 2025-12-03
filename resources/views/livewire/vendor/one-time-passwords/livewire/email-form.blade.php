<div>
{{--    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">--}}
{{--        {{ __('one-time-passwords::form.email_form_title') }}--}}
{{--    </h2>--}}

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
            @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('one-time-passwords::form.send_login_code_button') }}
            </flux:button>
        </div>
        <div class="text-center">
            <flux:button href="{{url('/')}}" variant="outline" size="sm" class="w-full" icon="arrow-path">
                {{ __('Reload flowcast application') }}
            </flux:button>
        </div>
    </form>
</div>
