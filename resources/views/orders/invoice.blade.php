@extends('layouts.adminLayout')

@section('content')
<div id="invoice" class="w-full mx-6 p-6 border border-gray-300 rounded-lg bg-white">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold">Invoice</h2>
        <p class="mt-2">Invoice ID: <span class="font-semibold">{{ $invoice->id }}</span></p>
        <p>Order ID: <span class="font-semibold">{{ $order }}</span></p>
        <p>Date: <span class="font-semibold">{{ now()->format('Y-m-d') }}</span></p>
    </div>

    <div class="mt-4">
        <h3 class="text-lg font-semibold">Selected Parts</h3>
        <table class="w-full mt-2 border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2">Part Name</th>
                    <th class="border border-gray-300 p-2">Price (NPR)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parts as $part)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $part['name'] }}</td>
                        <td class="border border-gray-300 p-2">{{ $part['price'] }} NPR</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="border border-gray-300 p-2 text-center text-gray-600">No parts selected.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Labor Charges:</h3>
            <p class="text-gray-800">{{ $laborCharges }} NPR</p>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Total Parts Price:</h3>
            <p class="text-gray-800">{{ $totalPartsPrice }} NPR</p>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Final Total:</h3>
            <p class="text-xl font-bold text-blue-600">{{ $finalTotal }} NPR</p>
        </div>
    </div>

    <div class="mt-6 text-center no-print">
        <button onclick="printInvoice()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
            Print Invoice
        </button>
    </div>
    
    <script>
        function printInvoice() {
            const invoiceDiv = document.getElementById('invoice');
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Invoice</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">'); // Add your Tailwind CSS link here
            printWindow.document.write('<style>@media print { .no-print { display: none; } }</style>'); // Hide elements with class 'no-print'
            printWindow.document.write('</head><body>');
            printWindow.document.write(invoiceDiv.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
