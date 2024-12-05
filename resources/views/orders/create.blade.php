@extends('layouts.adminLayout')
@section('heading', 'Career Details')
@section('subheading', 'Create new career')
@section('title', 'Create Career')
@section('content')

<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 mx-4">Order Form</h2>
    <form action="{{ route('orders.store') }}" method="post"
        class="max-w-md bg-white shadow-2xl rounded px-8 pt-6 pb-8 mb-4 mx-auto">
        @csrf

        <div class="mb-4">
            <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                class="appearance-none border rounded w-full py-2 px-3
                text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_name') border-red-500 @enderror">
            @error('customer_name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="vehicle_no" class="block text-gray-700 text-sm font-bold mb-2">Vehicle Number:</label>
            <input type="text" id="vehicle_no" name="vehicle_no" value="{{ old('vehicle_no') }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                focus:outline-none focus:shadow-outline @error('vehicle_no') border-red-500 @enderror">
            @error('vehicle_no')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        {{-- <div class="mb-4">
            <label for="override_estimated_time" class="block text-gray-700 text-sm font-bold mb-2">Override Estimated
                Completion Time:</label>
            <input type="checkbox" id="override_estimated_time" class="mr-2 leading-tight">
            <span class="text-sm">Check to manually enter completion time</span>
        </div>

        <div class="mb-4" id="estimated_time_container" style="display: none;">
            <label for="estimated_completed" class="block text-gray-700 text-sm font-bold mb-2">Estimated Completion
                Time:</label>
            <input type="datetime-local" id="estimated_completed" name="estimated_completed"
                value="{{ old('estimated_completed') }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                   @error('estimated_completed') border-red-500 @enderror">
            @error('estimated_completed')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="override_estimated_cost" class="block text-gray-700 text-sm font-bold mb-2">Override Estimated
                Cost:</label>
            <input type="checkbox" id="override_estimated_cost" class="mr-2 leading-tight">
            <span class="text-sm">Check to manually enter estimated cost</span>
        </div>

        <div class="mb-4" id="estimated_cost_container" style="display: none;">
            <label for="estimated_cost" class="block text-gray-700 text-sm font-bold mb-2">Estimated Cost:</label>
            <input type="number" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost') }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                   @error('estimated_cost') border-red-500 @enderror">
            @error('estimated_cost')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <script>
            document.getElementById('override_estimated_time').addEventListener('change', function () {
                const estimatedTimeContainer = document.getElementById('estimated_time_container');
                if (this.checked) {
                    estimatedTimeContainer.style.display = 'block';
                } else {
                    estimatedTimeContainer.style.display = 'none';
                }
            });
        
            document.getElementById('override_estimated_cost').addEventListener('change', function () {
            const estimatedCostContainer = document.getElementById('estimated_cost_container');
             if (this.checked) {
            estimatedCostContainer.style.display = 'block';
            } else {
             estimatedCostContainer.style.display = 'none';
                 }
            });
        </script> --}}
        <div class="mb-4">
            <label for="estimated_completed" class="block text-gray-700 text-sm font-bold mb-2">Enter Estimated
                Completion Time:</label>
            <input type="datetime-local" id="estimated_completed" name="estimated_completed"
                value="{{ old('estimated_completed') }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                       @error('estimated_completed') border-red-500 @enderror">
            @error('estimated_completed')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- Hidden Estimated Cost Input -->
        <input type="hidden" id="estimated_cost" name="estimated_cost" value="100">


        <div class="container mt-5">
            <div id="dropdown-container">
                <div class="dropdown-container">
                    <div class="form-group mb-4 border-b-2 border-gray-300">
                        <label for="jobs" class="block text-gray-700 text-sm font-bold mb-2">Repairs:</label>
                        <div class="combo-box">
                            <select name="jobs[]"
                                class="form-select border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value=""></option> <!-- Initial empty option -->

                                @foreach($repairs as $repair)

                                <option value="{{ $repair->id }}">{{ $repair->repair_name }}</option>
                                <!-- Pass repair_id -->
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <button id="add-dropdown" type="button"
                class="my-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add
                Dropdown</button>
        </div>

        <!-- Include jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- Include selectize.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js">
        </script>

        <script>
            $(document).ready(function() {
                // Initialize selectize on initial job select
                $('.combo-box select').selectize({
                    create: true,
                    sortField: 'text'
                });
        
                // Add Dropdown button functionality
                $('#add-dropdown').click(function() {
                    var dropdownHtml = `<div class="dropdown-container">
                                            <div class="form-group mb-4 border-b-2 border-gray-300">
                                                <label for="jobs" class="block text-gray-700 text-sm font-bold mb-2">Repairs:</label>
                                                <div class="combo-box">
                                                    <select name="jobs[]" class="form-select border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        <option value=""></option> <!-- Initial empty option -->
                                                        @foreach($repairs as $repair)
                                                        <option value="{{ $repair->id }}">{{ $repair->repair_name }}</option> <!-- Pass repair_id -->
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>`;
                    $('#dropdown-container').append(dropdownHtml);
        
                    // Initialize selectize on the newly added job select
                    $('.combo-box:last select').selectize({
                        create: true,
                        sortField: 'text'
                    });
                });
            });
        </script>

        <button type="submit"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
    </form>
</div>

@endsection