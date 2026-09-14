<div class="space-y-4">
    @forelse ($remarks as $remark)
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    {{ $remark->user?->name ?? 'User' }}
                </span>
                <span class="text-xs text-gray-500">
                    {{ $remark->created_at->format('M d, Y h:i A') }}
                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $remark->remark }}</p>
        </div>
    @empty
        <p class="text-sm text-gray-500">No remarks found.</p>
    @endforelse
</div>
