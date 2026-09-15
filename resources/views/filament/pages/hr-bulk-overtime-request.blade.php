<x-filament-panels::page>
    <form wire:submit="submit" class="fi-sc-form">
        {{ $this->form }}

        <div class="fi-ac fi-align-start gap-3">
            <x-filament::button type="submit">
                Submit 
            </x-filament::button>
            <x-filament::button color="gray" tag="a" :href="url()->previous()">
                Cancel
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
