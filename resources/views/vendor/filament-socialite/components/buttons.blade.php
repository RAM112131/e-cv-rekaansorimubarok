<div
    x-data="{}"
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('filament-socialite-styles', package: 'filament-socialite'))]"
>
    <div class="flex flex-col gap-y-6">
        @if ($messageBag->isNotEmpty())
            @foreach($messageBag->all() as $value)
                <p class="fi-fo-field-wrp-error-message text-danger-600 dark:text-danger-400">{{ __($value) }}</p>
            @endforeach
        @endif
        @if (count($visibleProviders))
            @if($showDivider)
                <div class="relative flex items-center justify-center text-center">
                    <div class="absolute border-t border-gray-200 w-full h-px"></div>
                    <p class="inline-block relative bg-white text-sm p-2 rounded-full font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        {{ __('filament-socialite::auth.login-via') }}
                    </p>
                </div>
            @endif
            <div class="grid @if(count($visibleProviders) > 1) grid-cols-2 @endif gap-4">
                @foreach($visibleProviders as $key => $provider)
                    <x-filament::button
                        :color="$provider->getColor()"
                        :outlined="$provider->getOutlined()"
                        :icon="$key === 'google' ? null : $provider->getIcon()"
                        tag="a"
                        :href="route($socialiteRoute, $key)"
                        :spa-mode="false"
                    >
                        <span class="flex items-center justify-center gap-x-2">
                            @if ($key === 'google')
                                <img
                                    src="{{ asset('images/icons/google.png') }}"
                                    alt="google"
                                    style="height:18px;width:18px;object-fit:contain;margin-right:8px;"
                                />
                            @endif
                            {{ $provider->getLabel() }}
                        </span>
                    </x-filament::button>
                @endforeach
            </div>
        @else
            <span></span>
        @endif
    </div>
</div>