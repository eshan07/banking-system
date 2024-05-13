<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Deposit') }}
        </h2>
    </x-slot>
    <div class="flex justify-center">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('deposits.store') }}">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('Amount')"/>
                    <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')"
                                  required
                                  autofocus autocomplete="amount"/>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        {{ __('Add') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
