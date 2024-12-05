@extends('layouts.adminLayout')

@section('content')
<style>
    .content {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease-out;
    }

    .container.expanded .content {
        max-height: 1000px;
        transition: max-height 0.3s ease-in;
    }

    @keyframes slide-in {
        from {
            transform: translateX(100%);
        }
        to {
            transform: translateX(0);
        }
    }
</style>

<div class="fixed top-24 right-5 bg-white p-4 shadow-md z-10 animate-slide-in">
    <strong>Time taken:</strong> <br><br> <span id="time_taken">00:00:00</span>
</div>

<div class="container mx-auto mt-8 p-6 bg-white shadow-md rounded">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                <td class="px-6 py-3 whitespace-nowrap">{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Number</th>
                <td class="px-6 py-3 whitespace-nowrap">{{ $order->vehicle_no }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                <td class="px-6 py-3 whitespace-nowrap">{{ $order->start_time }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Cost</th>
                <td class="px-6 py-3 whitespace-nowrap">{{ $order->estimated_cost }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repairs</th>
                <td class="px-6 py-3 whitespace-nowrap">
                    @foreach ($repairsArray as $repair)
                        <label class="block">
                            <input type="checkbox" name="repairCheckbox" value="{{ $repair }}" disabled class="mr-2">{{ $repair }}<br>
                        </label>
                    @endforeach
                </td>
            </tr>
        </thead>
    </table>
    <div class="flex justify-end mt-4">
        <button id="start_btn" onclick="startStopwatch()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Start</button>
        <button id="stop_btn" onclick="stopStopwatch()" disabled class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Stop</button>
        @if (!$allRepairsCompleted)
            <button id="complete_btn" onclick="complete()" disabled class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Complete</button>
        @endif
    </div>
</div>

<script>
    var startTime = 0;
    var elapsedTime = 0;
    var stopwatchInterval;
    var startBtn = document.getElementById("start_btn");
    var stopBtn = document.getElementById("stop_btn");
    var completeBtn = document.getElementById("complete_btn");
    var repairCheckboxes = document.getElementsByName("repairCheckbox");
    var timeTakenElement = document.getElementById("time_taken");

    function startStopwatch() {
        startTime = new Date().getTime();
        startBtn.disabled = true;
        stopBtn.disabled = false;
        completeBtn.disabled = true;
        enableCheckboxes(true);
        stopwatchInterval = setInterval(updateElapsedTime, 1000);
    }   

    function stopStopwatch() {
        clearInterval(stopwatchInterval);
        startBtn.disabled = false;
        stopBtn.disabled = true;
        enableCheckboxes(false);
    }

    function enableCheckboxes(enabled) {
        for (var i = 0; i < repairCheckboxes.length; i++) {
            repairCheckboxes[i].disabled = !enabled;
        }
    }

    function updateElapsedTime() {
        var currentTime = new Date().getTime();
        elapsedTime += currentTime - startTime;
        startTime = currentTime;

        var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
        var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

        var timeString = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);
        time_taken.innerText = timeString;
        checkCompleteButton();
    }

    function formatTime(time) {
        return time < 10 ? "0" + time : time;
    }

    function checkCompleteButton() {
        var allChecked = true;
        for (var i = 0; i < repairCheckboxes.length; i++) {
            if (!repairCheckboxes[i].checked) {
                allChecked = false;
                break;
            }
        }
        completeBtn.disabled = !allChecked;
    }

    for (var i = 0; i < repairCheckboxes.length; i++) {
        repairCheckboxes[i].addEventListener("change", checkCompleteButton);
    }

    function complete() {
        var timeTaken = time_taken.innerText;
        var id = {{ $order->id }};
        var url = "{{ route('orders.complete', ['id' => $order->id]) }}?timeTaken=" + encodeURIComponent(timeTaken);
        window.location.href = url;
    }
</script>
@endsection
