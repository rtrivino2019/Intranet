
    <style>
        .supplier-section {
            page-break-before: always;
            margin-bottom: 20px; /* Add some spacing between supplier sections */
        }
    
        .supplier-section:first-child {
            page-break-before: auto;
        }
    
        /* Add more styling as needed */
    
        /* Style for the table */
        table {
            width: 75%; /* Set the table width to 75% of the page */
            border-collapse: collapse;
            margin-top: 15px; /* Add some spacing above the table */
        }
    
        th, td {
            border: 1px solid #ddd; /* Add borders to cells */
            padding: 8px; /* Add padding to cells */
            text-align: left; /* Align text to the left in cells */
        }
    
        th {
            background-color: #3498db; /* Blue color for table headings */
            color: #fff; /* White text for table headings */
        }
    </style>
    
    <div>
        @foreach($record->orderItems->where('product_amount', '>', 0)->groupBy('food_supplier') as $foodSupplier => $items)
            <div class="supplier-section">
                <h1>Supplier: {{ $foodSupplier }} </h1>
    
                <!-- Order details -->
                <p><strong>Order Number:</strong> {{ $record->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $record->order_date }}</p>
                <p><strong>User:</strong> {{ $record->user->name }}</p>
    
                <!-- Restaurant details -->
                <p><strong>Restaurant:</strong>
                    @foreach($record->restaurant as $restaurant)
                        {{ $restaurant->name }}
                    @endforeach
                </p>
    
                <!-- Order Items table -->
                <h2>Order Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Unit</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product_amount }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->categoryfood }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    