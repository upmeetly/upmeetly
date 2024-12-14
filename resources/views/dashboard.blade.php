<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <section>
        <h3 class="text-base font-semibold dark:text-gray-200 text-gray-800">Last 30 days</h3>
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
          <div
            class="overflow-hidden rounded-lg bg-white px-4 py-5 sm:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-800">
            <dt class="truncate text-sm font-medium dark:text-gray-400 text-gray-500">Total Subscribers</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">71,897</dd>
          </div>
          <div
            class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-800">
            <dt class="truncate text-sm font-medium dark:text-gray-400 text-gray-500">Avg. Open Rate</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">58.16%</dd>
          </div>
          <div
            class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-800">
            <dt class="truncate text-sm font-medium dark:text-gray-400 text-gray-500">Avg. Click Rate</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">24.57%</dd>
          </div>
        </dl>
      </section>

      <livewire:meetups-tabs />
    </div>
  </div>
</x-app-layout>
