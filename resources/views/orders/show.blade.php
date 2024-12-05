@extends('layouts.adminLayout')

@section('content')
<link rel="stylesheet" href="{{asset('checkbox.css')}}">
<div class="fixed top-24 right-5 bg-white p-4 shadow-lg z-20 rounded-lg border border-gray-200 animate-slide-in">
    <strong class="text-gray-700">Total Time Taken:</strong>
    <br><br>
    <span id="total_time_taken" class="text-2xl font-semibold text-blue-600">00:00:00</span>
</div>

<div class="container mx-auto mt-12 p-8 bg-white shadow-lg rounded-lg">
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
        <h2 class="text-lg font-semibold text-gray-700">Order Details</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Customer Name
                </th>
                <td class="px-6 py-3 whitespace-nowrap text-gray-800 font-semibold">{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Vehicle Number
                </th>
                <td class="px-6 py-3 whitespace-nowrap text-gray-800 font-semibold">{{ $order->vehicle_no }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Start Time
                </th>
                <td class="px-6 py-3 whitespace-nowrap text-gray-800 font-semibold">{{ $order->start_time }}</td>
            </tr>
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Repairs</th>
                <td class="px-6 py-3 whitespace-nowrap text-gray-800 font-semibold">
                    @foreach ($repairs as $index => $repair)
                    <div class="flex items-center space-x-2">
                        <div class="checkbox-wrapper-26">
                            <input type="checkbox" id="task-{{ $index }}" name="repairCheckbox" value="{{ $repair }}"
                                data-index="{{ $index }}" onchange="completeTask(this)" disabled>
                            <label for="task-{{ $index }}" class="checkbox-label">
                                <span class="tick_mark"></span>

                            </label>
                        </div>
                        {{ $repair->repair_name }}
                        <span id="task-time-{{ $index }}" class="ml-2 font-bold text-blue-600">00:00:00</span>

                    </div>
                    @endforeach
                </td>
            </tr>
        </thead>
    </table>

    <div class="container mt-5">
        <div id="dropdown-container">
            <div class="mb-4">
                <label for="parts" class="block text-gray-700 text-sm font-bold mb-2">Parts:</label>
                <div class="combo-box">
                    <select name="parts[]"
                        class="form-select border rounded-md w-1/2 py-2 px-3 text-gray-700  focus:outline-none focus:shadow-outline">
                        <option value=""></option> <!-- Initial empty option -->
                        @foreach($parts as $part)
                        <option value="{{ $part->part_name }}">{{ $part->part_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button id="add-dropdown" type="button"
            class="my-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Add Dropdown</button>
    </div>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include selectize.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js">
    </script>

    <script>
        $(document).ready(function() {
            // Initialize selectize on initial parts select
            $('.combo-box select').selectize({
                create: true,
                sortField: 'text'
            });
    
            // Add Dropdown button functionality
            $('#add-dropdown').click(function() {
                var dropdownHtml = `<div class="mb-4">                                       
                                        <div class="combo-box">
                                            <select name="parts[]" class="form-select border rounded-md w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                <option value=""></option> <!-- Initial empty option -->
                                                @foreach($parts as $part)
                                                <option value="{{ $part->part_name }}">{{ $part->part_name }}</option>
                                                @endforeach
                                            </select>  
                                            </div>                                        
                                    </div>`;
                $('#dropdown-container').append(dropdownHtml);
    
                // Initialize selectize on the newly added part select
                $('.combo-box:last select').selectize({
                    create: true,
                    sortField: 'text'
                });
            });
        });
    </script>

    <div class="flex justify-end mt-6 space-x-4">
        <button id="start_btn" onclick="startJob()"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-300 ease-in-out">
            Start</button>
        <button id="stop_btn" onclick="stopJob()" disabled
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-300 ease-in-out">
            Stop</button>
        <button id="complete_btn"
            class="bg-green-500 disabled:opacity-50 cursor-not-allowed hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-300 ease-in-out"
            disabled>
            Complete
        </button>
    </div>
</div>



<script>
    let startTime = 0;
    let elapsedTime = 0;
    let stopwatchInterval;
    let lastTaskEndTime = 0;
    let taskTimes = {}; // Track individual task times

    function startJob() {
        startTime = new Date().getTime();
        elapsedTime = 0;
        lastTaskEndTime = 0;
        document.getElementById("start_btn").disabled = true;
        document.getElementById("stop_btn").disabled = false;
        document.getElementById("complete_btn").disabled = true;
        enableCheckboxes(true);
        stopwatchInterval = setInterval(updateTotalTime, 1000);
       
    }

    function stopJob() {
        clearInterval(stopwatchInterval);
        document.getElementById("start_btn").disabled = false;
        document.getElementById("stop_btn").disabled = true;
        enableCheckboxes(false);
    }

    function updateTotalTime() {
        const currentTime = new Date().getTime();
        elapsedTime = currentTime - startTime;

        const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
        const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

        const timeString = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);
        document.getElementById("total_time_taken").innerText = timeString;
    }

    function completeTask(checkbox) {
        if (!checkbox.checked) {
            return; // Ignore if unchecked
        }

        const currentTime = new Date().getTime();
        const taskIndex = checkbox.dataset.index;

        // Calculate the time spent on this task
        const taskElapsed = currentTime - (startTime + lastTaskEndTime);
        lastTaskEndTime += taskElapsed; // Update the last task end time

        const taskHours = Math.floor(taskElapsed / (1000 * 60 * 60));
        const taskMinutes = Math.floor((taskElapsed % (1000 * 60 * 60)) / (1000 * 60));
        const taskSeconds = Math.floor((taskElapsed % (1000 * 60)) / 1000);

        // Store the task time in the dictionary
        taskTimes[checkbox.value] = {
            time: formatTime(taskHours) + ":" + formatTime(taskMinutes) + ":" + formatTime(taskSeconds),
            id: checkbox.value
        };

        // Display the time taken for this task next to the checkbox
        document.getElementById("task-time-" + taskIndex).innerText = taskTimes[checkbox.value].time;

        // Check if all tasks are completed to enable the complete button
        checkCompleteButton();
    }

    function checkCompleteButton() {
    let allChecked = true;
    const checkboxes = document.getElementsByName("repairCheckbox");
    
    for (let i = 0; i < checkboxes.length; i++) {
        if (!checkboxes[i].checked) {
            allChecked = false;
            break;
        }
    }

    const completeBtn = document.getElementById("complete_btn");

    if (allChecked) {
        completeBtn.disabled = false;
        completeBtn.classList.remove('disabled:opacity-50', 'cursor-not-allowed');
        completeBtn.classList.add('hover:bg-green-600');
        
          // Stop the stopwatch when all checkboxes are checked
          clearInterval(stopwatchInterval);
        completeBtn.addEventListener('click', function() {
            // Get the current total time taken from the stopwatch
            const timeTaken = elapsedTime;

            // Send task times and total time to the complete route
            const taskData = encodeURIComponent(JSON.stringify(taskTimes));

            // Collect selected parts from dropdowns
            const selectedParts = [];
            document.querySelectorAll('select[name="parts[]"]').forEach(select => {
                const selectedValue = select.value;
                if (selectedValue) {
                    selectedParts.push(selectedValue);
                }
            });

            // Encode the selected parts into a JSON string
            const partsData = encodeURIComponent(JSON.stringify(selectedParts));

            // Construct the URL with query parameters
            const url = "{{ route('orders.complete', ['id' => $order->order_id]) }}" + 
                        "?timeTaken=" + timeTaken + 
                        "&taskData=" + taskData + 
                        "&parts=" + partsData;

            // Redirect to the complete route with the query parameters
            window.location.href = url;
        });
    } else {
        completeBtn.disabled = true;
        completeBtn.classList.add('disabled:opacity-50', 'cursor-not-allowed');
        completeBtn.classList.remove('hover:bg-green-600');
    }
        }
    

    function formatTime(time) {
        return time < 10 ? "0" + time : time;
    }

    function enableCheckboxes(enabled) {
        const checkboxes = document.getElementsByName("repairCheckbox");
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].disabled = !enabled;
        }
    }
</script>

@endsection