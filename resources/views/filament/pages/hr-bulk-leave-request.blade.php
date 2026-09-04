<x-filament-panels::page>
    <form wire:submit="submit" class="fi-sc-form">
        {{ $this->form }}

        <div class="fi-ac fi-align-end">
            <x-filament::button type="submit">
                Submit Leave Request
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
