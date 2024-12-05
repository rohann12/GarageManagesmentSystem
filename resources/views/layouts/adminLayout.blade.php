<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RepairMate-@yield('title')</title>
    {{-- <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}
    {{-- @vite('resources/css/app.css')
    @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="{{ asset('tailwind.jsx') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <link rel="icon" href="{{ asset('logos/lo.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;700&display=swap" rel="stylesheet">
    {{--
    <link rel="stylesheet" href="{{ asset('css/scroll.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
    {{-- <script src="https://kit.fontawesome.com/20789f0b6f.js" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>


</head>
<style>
    body {
        /* font-family: 'poppins'; */
    }

    .object {
        height: 35px;
        width: 35px;

    }

    object {
        pointer-events: none;
    }

    /* Define styles for the SVG */
    svg {

        transition: transform 0.3s ease;
        /* Add a smooth transition */
    }

    /* Define styles for the SVG when hovered */
    svg:hover {
        transform: scale(1.2);
        /* Scale up the SVG on hover */
    }
</style>

<body>
    <div class="flex flex-row w-screen h-screen overflow-x-hidden">
        @include('layouts.sidebar')

        <div class="flex flex-1 flex-col w-full h-screen overflow-hidden">
            <div class="flex flex-row items-center justify-between w-full h-20 ps-6 border-b border-gray-200 ">
                <div class="flex flex-col gap-y-1">
                    <h3 class="font-semibold lg:text-2xl md:text-xl">@yield('heading')</h3>
                    <span class="text-gray-500 font-normal lg:text-xs md:text-xs">@yield('subheading')</span>
                </div>

                <div class="flex flex-row gap-x-4">
                    {{-- Notification icon and dropdown --}}
                    <div class="relative inline-block text-left">
                        <button id="notificationButton" data-dropdown-toggle="notificationDropdown"
                            class="text-gray-300 hover:text-black mt-2 mr-0 font-medium rounded-lg text-sm text-center inline-flex items-center"
                            type="button">
                            {{-- Bell icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11.5a7.5 7.5 0 00-5-7.116V3.5a1.5 1.5 0 00-3 0v.884a7.5 7.5 0 00-5 7.116v2.658c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1.5a3 3 0 01-6 0V17m6 0H9" />
                            </svg>
                            @if($notifications->where('is_read', false)->count() > 0)
                            <span
                                class="absolute top-0 right-0 -mr-1 -mt-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                                {{ $notifications->where('is_read', false)->count() }}
                            </span>
                            @endif
                        </button>

                        <!-- Dropdown menu -->
                        <div id="notificationDropdown"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-56">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="notificationButton">
                                @forelse($notifications as $notification)
                                    <li class="flex items-center px-4 py-2 hover:bg-gray-100 hover:text-sky-500">
                                        <object type="image/svg+xml" data="{{ asset('logos/notification.svg') }}" class="text-sky-500"></object>
                                        <span class="block px-2">{{ $notification->message }}</span>
                                        
                                    <!-- Notification link and tick button -->
<a href="#" class="ml-auto text-blue-500 text-sm" onclick="handleNotificationClick('{{ $notification->order_id ?? '' }}', '{{ route('orders.show', ['order' => $notification->order_id ?? 0]) }}', '{{ route('notifications.read', $notification) }}', event)">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l5 5L20 7" />
    </svg>
</a>

<!-- No form needed as we will submit the mark-as-read request via JavaScript -->

<!-- JavaScript function -->
<script>
    function handleNotificationClick(orderId, orderRoute, markAsReadRoute, event) {
        event.preventDefault(); // Prevent default link behavior
        
        // Use fetch to submit the mark-as-read request asynchronously
        fetch(markAsReadRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for the request
            },
        }).then(response => {
            if (response.ok) {
                // After marking the notification as read, redirect to the order page
                if (orderId) {
                    window.location.href = orderRoute;
                }
            } else {
                console.error('Failed to mark notification as read');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
</script>

                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-sm text-gray-500">No new notifications</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>



                    {{-- User icon and dropdown --}}
                    <button id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider"
                        class="text-gray-300  hover:text-black   mr-5 font-medium rounded-lg text-sm text-center inline-flex items-center"
                        type="button">
                        {{-- User icon --}}
                        <div class="h-10 w-10 rounded-full bg-gray-200 ">
                            {{-- Insert image here --}}
                            <img src="{{ asset('logos/lo.png') }}" alt="Profile Photo" class="rounded-full"
                                style="height:100%;width:100%;object-fit:cover;">
                        </div>
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownDivider"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 ">
                        <ul class="py-2  text-sm text-gray-700 " aria-labelledby="dropdownDividerButton">
                            <li class="flex items-center px-4 hover:bg-gray-100 hover:text-sky-500">
                                <object type="image/svg+xml" data="{{ asset('logos/profile.svg') }}"
                                    class="text-sky-500"></object>
                                <a href="{{route('profile')}}" class="block px-2 py-2 ">Profile</a>
                            </li>

                            <li class="flex items-center px-4 hover:bg-gray-100 hover:text-sky-500">
                                <object type="image/svg+xml" data="{{ asset('logos/settings.svg') }}"
                                    class="text-sky-500"></object>
                                <a href="#" class="block px-2 py-2">Settings</a>
                            </li>


                        </ul>
                        <div
                            class="py-2 flex items-center text-sm text-gray-700 px-4 hover:bg-gray-100 hover:text-sky-500">
                            <object type="image/svg+xml" data="{{ asset('logos/logout.svg') }}"
                                class="text-sky-500"></object>

                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @yield('button')

            <div class="flex flex-col px-6 pt-4 flex-1 min-h-96 bg-gray-50 overflow-x-hidden overflow-y-scroll ">
                <div class="flex flex-col bg-white w-full h-fit rounded-md ">
                    @yield('content')

                </div>

            </div>
        </div>
    </div>
    <!-- Include the Error Modal Component -->
    {{--
    <x-error-modal :message="session('error')" /> --}}

    {{--
    @if (session()->has('success'))
    <div id="success" class="fixed bottom-0 left-0 w-full bg-blue-500 border text-white text-center p-4 ">
        {{ session('success') }}
    </div>
    <script>
        function showSuccess() {
                    const successDiv = document.getElementById('success');
                    setTimeout(() => {
                        successDiv.classList.add('hidden');
                    }, 5000);
                }
                showSuccess();
    </script>
    @endif --}}
    {{-- @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div id="error" class="fixed bottom-0 left-0 w-full bg-red-500 text-white text-center p-4">
        {{ $error }}</div>
    <script>
        function showError() {
                        const errorDiv = document.getElementById('error');
                        setTimeout(() => {
                            errorDiv.classList.add('hidden');
                        }, 5000);
                    }
                    showError();
    </script>
    @endforeach
    @endif --}}
    <!-- Include scripts to manage the modal -->
    {{-- <script>
        const errorModal = document.getElementById('errorModal');
        const closeErrorModal = document.getElementById('closeErrorModal');
        const closeErrorModalButton = document.getElementById('closeErrorModalButton');

        // If there's an error message, show the modal
        @if(session('error'))
            errorModal.classList.remove('hidden');
        @endif

        // Close modal on button click
        [closeErrorModal, closeErrorModalButton].forEach(button => {
            button.addEventListener('click', () => {
                errorModal.classList.add('hidden');
            });
        });
    </script> --}}


</body>

</html>