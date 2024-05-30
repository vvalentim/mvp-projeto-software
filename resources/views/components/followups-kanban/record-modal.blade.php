<x-filament-panels::form wire:submit.prevent="handleFormSubmit">
    <x-filament::modal id="kanban--edit-record-modal" :slideOver="$this->getEditModalSlideOver()" :width="$this->getEditModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>
                {{ $this->getEditModalTitle() }}
            </x-filament::modal.heading>
        </x-slot>

        @unless(empty($this->editModalRecordId))
            {{ $this->getModalInfolist }}
        @endunless

        <x-slot name="footer">
            {{-- <div class="flex justify-end">
                <x-filament::button type="submit" class="me-2" color="primary">
                    {{$this->getEditModalSaveButtonLabel()}}
                </x-filament::button>
            </div> --}}
        </x-slot>
    </x-filament::modal>
</x-filament-panels::form>
