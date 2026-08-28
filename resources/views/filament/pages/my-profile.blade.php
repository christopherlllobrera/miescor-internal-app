<x-filament-panels::page>
    <form wire:submit="updateProfile" class="fi-sc-form">
        {{ $this->editProfileForm }}

        <div class="fi-ac fi-align-end">
            <x-filament::button type="submit">
                Save
            </x-filament::button>
        </div>
    </form>

    <form wire:submit="updatePassword" class="fi-sc-form">
        {{ $this->editPasswordForm }}

        <div class="fi-ac fi-align-end">
            <x-filament::button type="submit">
                Save
            </x-filament::button>
        </div>
    </form>

    @if(\Filament\Facades\Filament::hasMultiFactorAuthentication())
        <div class="fi-sc-form">
            {{ $this->mfaSchema }}
        </div>
    @endif

    <x-filament-actions::modals />
</x-filament-panels::page>
