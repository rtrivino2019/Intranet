<div>
    <div class="space-y-4">
       <!-- Start Date Input -->
    <div>
        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
        <input wire:model="startDate" type="date" id="startDate" name="startDate" min="2018-01-01" class="mt-1 block w-1/3 p-2 border rounded-md" wire:change="updateDates">
    </div>

    <!-- End Date Input -->
    <div>
        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
        <input wire:model="endDate" type="date" id="endDate" name="endDate" min="2018-01-01" class="mt-1 block w-1/3 p-2 border rounded-md" wire:change="updateDates">
    </div>

    <!-- Display Total Sales -->
    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Total Sales:</label>
        <p class="text-lg font-bold">${{ $salesTotal }}</p>
    </div>

    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Total Expenses:</label>
        <p class="text-lg font-bold">${{ $expensesTotal }}</p>
    </div>


</div>
