@extends('layouts.adminLayout')
@section('title', 'customers')

@section('button')
<!-- Modal toggle -->
<div class="flex h-20 items-center justify-end">
    <button onclick="openCreateModal()" class="px-10 py-2 mr-6 text-white bg-sky-500 rounded-md" type="button">
        + New customer
    </button>
</div>
@endsection

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="min-height: 400px;">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr>
                <th scope="col" class="px-6 py-3">S.N</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-5">
                    {{ $customer->name }}
                </td>
                <td class="px-6 py-5">
                    {{ $customer->email }}
                </td>
                <td class="px-6 py-5">
                    <button onclick="openEditModal({{ $customer->id }}, '{{ $customer->name }}', '{{ $customer->email }}', {{ $customer->isAdmin ? 'true' : 'false' }})" class="text-blue-500 hover:text-blue-700">Edit</button>
                    <form action="{{ route('customers.destroy', $customer) }}" method="post" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b hover:bg-gray-50">
                <td scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap" colspan="4">
                    No customers to show
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="customerModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-4">Create New customer</h2>
        <form id="customerForm" action="{{ route('customers.store') }}" method="post">
            @csrf
            <input type="hidden" id="customerId" name="customerId" value="">

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="isAdmin" class="block text-gray-700 text-sm font-bold mb-2">Admin:</label>
                <input type="checkbox" id="isAdmin" name="isAdmin" class="mr-2">
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Create New customer';
        document.getElementById('customerForm').action = '{{ route('customers.store') }}';
        document.getElementById('customerId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('isAdmin').checked = false;
        document.getElementById('customerModal').classList.remove('hidden');
    }

    function openEditModal(id, name, email, isAdmin) {
        document.getElementById('modalTitle').innerText = 'Edit customer';
        document.getElementById('customerForm').action = '/customers/' + id;
        document.getElementById('customerId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('password').value = '';
        document.getElementById('isAdmin').checked = isAdmin;
        document.getElementById('customerModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('customerModal').classList.add('hidden');
    }

    // If there are validation errors, open the modal
    @if ($errors->any())
        openCreateModal();
    @endif
</script>

@endsection
