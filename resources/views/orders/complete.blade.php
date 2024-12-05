@extends('layouts.adminLayout')

@section('content')
<div class="container mx-auto mt-12 p-8 bg-white shadow-lg rounded-lg">
    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
        <h2 class="text-lg font-semibold text-blue-800">Order Completion Details</h2>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Time Taken:</h3>
        <p class="text-2xl font-semibold text-blue-600">{{ $order->time_taken }}</p>
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Task Times and Points:</h3>
        <ul class="list-disc pl-5">
            @forelse ($taskTimes as $taskJson => $taskInfo)
            @php                
                $taskDetails = json_decode($taskJson, true);
                $pointsEarned = $points[$taskJson] ?? 0;
                $taskTime = $taskInfo['time'] ?? 'N/A'; // Accessing the time directly from taskInfo
            @endphp
            <li class="text-gray-800 font-semibold mb-2 flex gap-x-5">
                <span>{{ $taskDetails['repair_name'] ?? 'N/A' }}</span>
                <span>{{ $taskTime }}</span> <!-- Displaying the time field -->
                <span class="text-green-600 font-bold">+{{ $pointsEarned }} XP</span>
            </li>
        @empty
            <li class="text-gray-800">No task times recorded.</li>
        @endforelse
        </ul>
        <h3 class="text-lg font-semibold text-gray-700 mt-4">Total Points Earned: <span class="text-blue-600">{{ $totalPoints }} XP</span></h3>
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Selected Parts:</h3>
        <ul class="list-disc pl-5">
            @forelse ($parts as $part)
                <li class="text-gray-800 font-semibold mb-2 flex justify-between">
                    <span>{{ $part['name'] }}</span>
                    <span class="text-gray-600">Price: {{ $part['price'] }} NPR</span>
                </li>
            @empty
                <li class="text-gray-800">No parts selected.</li>
            @endforelse
        </ul>
        <h3 class="text-lg font-semibold text-gray-700">Labor charger: {{ $laborCharges }} NPR</h3>
    </div>
    

    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Total Price:</h3>
        <p class="text-xl font-semibold text-blue-600">{{ $totalPrice }} NPR</p>
    </div>

    <!-- Create Invoice Button -->
    <div class="mt-6">
        <form action="{{ route('order.createInvoice', ['order' => $order]) }}" method="POST">
            @csrf
            <input type="hidden" name="task_times" value="{{ json_encode($taskTimes) }}">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
            <input type="hidden" name="parts" value="{{ json_encode($parts) }}">
            <input type="hidden" name="labor_charges" value="{{ $laborCharges }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300">Create Invoice</button>
        </form>
        
    </div>
</div>
@endsection
     