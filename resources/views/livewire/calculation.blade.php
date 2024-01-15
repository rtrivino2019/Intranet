<div 
    x-data="{ darkMode: false }"
    :class="{ 'dark': darkMode }"
    class="p-8 rounded-lg shadow-md dark:bg-gray-800 dark:text-white"
>
    <!-- livewire/calculation.blade.php -->

    <div class="mt-2 text-lg w-2/3 text-center dark:text-white">
        <div class="w-full text-center">
            <h2 class="text-2xl font-semibold dark:text-black">{{ $currentRestaurantName }}</h2>
        </div>
    </div>
    
    <div>
        @if(in_array($role, ['Admin', 'Owner', 'Manager']))
            <div class="flex flex-col w-full justify-center items-center">
                <!-- Date Selectors -->
                <div class="mb-6 flex space-x-8 p-4 rounded-lg shadow-sm w-2/3">
                    <div class="w-1/2">
                        <label for="startDate" class="block text-sm font-medium text-gray-700" style="color: green;">Start Date:</label>
                        <input wire:model="startDate" type="date" id="startDate" name="startDate" min="2018-01-01" class="mt-1 block w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white" wire:change="updateDates">
                    </div>

                    <div class="w-1/2">
                        <label for="endDate" class="block text-sm font-medium text-gray-700" style="color: green;">End Date:</label>
                        <input wire:model="endDate" type="date" id="endDate" name="endDate" min="2018-01-01" class="mt-1 block w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white" wire:change="updateDates">
                    </div>
                </div>

                <!-- Results Table -->
                <div class="bg-white dark:bg-gray-700 dark:text-white p-4 rounded-lg shadow-sm w-2/3">
                    <table class="min-w-full border-collapse mb-4 mx-auto">

                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-2 border border-gray-500 text-center" style="color: green;">Type</th>
                                <th class="p-2 border border-gray-500 text-center" style="color: green;">Expense</th>
                                <th class="p-2 border border-gray-500 text-center" style="color: green;">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sales == 0)
                                <tr>
                                    <td colspan="3" class="p-2 border border-gray-500 text-center text-red-500 dark:text-white">Sales data is unavailable. Percentages and expenses cannot be resolved.</td>
                                </tr>
                            @else
                                @foreach ([
                                    'Food' => [$food, $pFood],
                                    'Alcohol' => [$alcohol, $pAlcohol],
                                    'Payroll' => [$payroll, $pPayroll],
                                    'Utilities' => [$utilities, $pUtilities],
                                    'Others' => [$others, $pOthers]
                                ] as $type => [$amount, $percentage])
                                    <tr class="hover:bg-gray-50 dark-mode:hover:bg-gray-600">
                                        <td class="p-2 border border-gray-500 text-center">{{ $type }}</td>
                                        <td class="p-2 border border-gray-500 text-center">${{ number_format($amount, 2) }}</td>
                                        <td class="p-2 border border-gray-500 text-center">{{ number_format($percentage, 2) }}%</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>

                <div class="mt-4 text-lg w-2/3 text-center dark:text-white">
                    <span class="font-medium">Total Expenses:</span> ${{ number_format($expenses, 2) }}
                </div>

                <div class="mt-2 text-lg w-2/3 text-center dark:text-white">
                    <span class="font-medium">Total Sales:</span> ${{ number_format($sales, 2) }}
                </div>

                
              

                

                <div class="flex justify-center mt-4 w-2/3">
                    <button onclick="printCurrentView()" class="bg-blue-500 text-white font-bold px-4 py-2 rounded">
                        Print Percentage Report
                    </button>
                </div>
                
                <script>
                    function printCurrentView() {
                        window.print();
                    }
                </script>
                
                
                

            </div>
        @else
            <!-- Content for other roles or no content at all -->
            <p class="dark:text-white">This content is only visible to Admin, Owner, or Manager.</p>
        @endif
    </div>

    

</div>




