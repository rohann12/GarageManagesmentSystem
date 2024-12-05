@extends('layouts.adminLayout')
@section('title', 'Orders')

@section('button')
<!-- Modal toggle -->
<div class="flex h-20 items-center justify-end">
    <a href="{{ route('orders.create') }}">
        <button
            class="px-10 py-2 mr-6 text-white bg-sky-500 rounded-md" type="button">
            + New Order
        </button>
    </a>
</div>
@endsection

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="min-height: 400px;">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr>
                <th scope="col" class="px-6 py-3">Order No.</th>
                <th scope="col" class="px-6 py-3">Customer Name</th>
                <th scope="col" class="px-6 py-3">Vehicle Number</th>
                <th scope="col" class="px-6 py-3">Priority</th>
                <th scope="col" class="px-6 py-3">Assigned To</th>
                <th scope="col" class="px-6 py-3">Take Job</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">
                    {{ $order->order_id }}
                </th> 
                <td class="px-6 py-5">
                    {{ $order->customer_name }}
                </td>
                <td class="px-6 py-5">
                    {{ $order->vehicle_no }}
                </td>
                <td class="px-6 py-5">
                    {{ $order->priority }}
                </td>
                <td class="px-6 py-5">
                    {{ $mechanics->firstWhere('id', $order->assigned_to)->name ?? 'Not Assigned' }}
                </td>
                <td class="px-6 py-5">
                    @if (is_null($order->assigned_to))
                        <a href="{{ route('orders.show', ['order' => $order]) }}">
                            <button class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-700">Take job</button>
                        </a>
                    @else
                        <span class="text-gray-500">Assigned</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b hover:bg-gray-50">
                <td scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap" colspan="6">
                    No orders to show
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
