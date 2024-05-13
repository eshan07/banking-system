<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Deposit Transactions") }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Deposit Add Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="height: 18rem;"> <!-- Fixed height -->
                    <div class="p-6 md:pr-12"> <!-- Adjusted padding -->
                        <h2 class="text-lg font-semibold mb-4">Add Deposit</h2>
                        <form method="POST" action="{{ route('deposits.store') }}">
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="amount" :value="__('Amount')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" required autofocus autocomplete="amount" />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <x-primary-button>
                                {{ __('Add Deposit') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Deposit Index Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Deposit History</h2>
                        @if ($transactions->isEmpty())
                            <p class="text-gray-500">No transactions available.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($transaction->created_at->diffInDays(now()) > 1)
                                                    {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                                                @else
                                                    {{ $transaction->created_at->diffForHumans() }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $transactions->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
