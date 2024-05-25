<div
    id="{{ $record->getKey() }}"
    wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="record select-none bg-white dark:bg-gray-700 rounded-lg px-4 py-2 cursor-grab font-medium"
>   
    <div class="font-semibold text-sm text-zinc-600 dark:text-gray-200">
        <span>#{{ $record->getKey() }}</span>
        <span>{{ $record->getKanbanRecordTitle() }}</span>
    </div>
    <div class="mt-2 text-xs">
        {{ $record->lead->phone }}
    </div>
</div>
