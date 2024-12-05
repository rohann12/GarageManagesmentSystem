@extends('layouts.adminLayout')
@section('title', 'Parts')

@section('button')
<!-- Modal toggle -->
<div class="flex h-20 items-center justify-end">
    <button onclick="openModal()" class="px-10 py-2 mr-6 text-white bg-sky-500 rounded-md" type="button">
        + New Part
    </button>
</div>
@endsection

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="min-height: 400px;">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr>
                <th scope="col" class="px-6 py-3">S.N</th>
                <th scope="col" class="px-6 py-3">Part Name</th>
                <th scope="col" class="px-6 py-3">Description</th>
                <th scope="col" class="px-6 py-3">Price</th>
                <th scope="col" class="px-6 py-3">Quantity</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($parts as $part)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-5">
                    {{ $part->part_name }}
                </td>
                <td class="px-6 py-5">
                    {{ $part->description }}
                </td>
                <td class="px-6 py-5">
                    NPR {{ number_format($part->price, 2) }}
                </td>
                <td class="px-6 py-5">
                    {{ $part->quantity }}
                </td>
                <td class="px-6 py-5">
                    {{ ucfirst($part->status) }}
                </td>
                <td class="px-6 py-5">
                    <button onclick="openModal({{ $part->id }}, '{{ $part->part_name }}', '{{ $part->description }}', {{ $part->price }}, {{ $part->quantity }}, '{{ $part->status }}')" class="text-blue-500 hover:text-blue-700">
                        <svg width="24" height="26" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="stroke-current text-gray-600 hover:text-blue-500">
                            <path
                                d="M13.3148 2.14713C13.1191 1.95231 12.8868 1.79818 12.6312 1.69365C12.3757 1.58912 12.1019 1.53626 11.8258 1.53813C11.5497 1.54 11.2767 1.59656 11.0226 1.70454C10.7684 1.81252 10.5382 1.96978 10.3452 2.16724L2.01666 10.4958L1 14.4619L4.96612 13.4447L13.2947 5.11612C13.4922 4.92321 13.6495 4.69305 13.7575 4.43897C13.8655 4.18488 13.9221 3.91191 13.924 3.63582C13.9258 3.35974 13.873 3.08602 13.7684 2.83049C13.6638 2.57497 13.5097 2.34271 13.3148 2.14713V2.14713Z"
                                stroke="#615E83" class="hover:text-blue-500" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg></button>
                    <form action="{{ route('parts.destroy', $part) }}" method="post" class="inline-block" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                            <svg width="24" height="24" viewBox="0 0 16 16" fill="none"  xmlns="http://www.w3.org/2000/svg">
                               
                                <path d="M11.7697 14.4616H4.23122C3.9456 14.4616 3.67168 14.3482 3.46972 14.1462C3.26776 13.9442 3.1543 13.6703 3.1543 13.3847V3.69238H12.8466V13.3847C12.8466 13.6703 12.7331 13.9442 12.5312 14.1462C12.3292 14.3482 12.0553 14.4616 11.7697 14.4616Z" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.38477 11.2305V6.92285" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.61621 11.2305V6.92285" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1 3.69238H15" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.61531 1.53809H6.38454C6.09892 1.53809 5.825 1.65155 5.62304 1.85351C5.42108 2.05547 5.30762 2.32939 5.30762 2.61501V3.69193H10.6922V2.61501C10.6922 2.32939 10.5788 2.05547 10.3768 1.85351C10.1748 1.65155 9.90093 1.53809 9.61531 1.53809Z" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b hover:bg-gray-50">
                <td scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap" colspan="7">
                    No parts to show
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="partModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-4">Create New Part</h2>
        <form id="partForm" action="{{ route('parts.store') }}" method="post">
            @csrf
            <input type="hidden" id="partId" name="partId" value="">

            <div class="mb-4">
                <label for="part_name" class="block text-gray-700 text-sm font-bold mb-2">Part Name:</label>
                <input type="text" id="part_name" name="part_name" value="{{ old('part_name') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('part_name') border-red-500 @enderror">
                @error('part_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <input type="text" id="price" name="price" value="{{ old('price') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('quantity') border-red-500 @enderror">
                @error('quantity')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select id="status" name="status"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                    <option value="">Select Status</option>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id = null, part_name = '', description = '', price = '', quantity = 0, status = '') {
        const form = document.getElementById('partForm');
        const modalTitle = document.getElementById('modalTitle');
        const partId = document.getElementById('partId');
        const partNameField = document.getElementById('part_name');
        const descriptionField = document.getElementById('description');
        const priceField = document.getElementById('price');
        const quantityField = document.getElementById('quantity');
        const statusField = document.getElementById('status');

        if (id) {
            modalTitle.innerText = 'Edit Part';
            form.action = "{{ url('parts') }}/" + id;
            form.method = "POST";
            partId.value = id;
            partNameField.value = part_name;
            descriptionField.value = description;
            priceField.value = price;
            quantityField.value = quantity;
            statusField.value = status;

            // Add the PUT method dynamically
            let methodField = document.createElement('input');
            methodField.setAttribute('type', 'hidden');
            methodField.setAttribute('name', '_method');
            methodField.setAttribute('value', 'PUT');
            form.appendChild(methodField);
        } else {
            modalTitle.innerText = 'Create New Part';
            form.action = "{{ route('parts.store') }}";
            form.method = "POST";
            partId.value = '';
            partNameField.value = '';
            descriptionField.value = '';
            priceField.value = '';
            quantityField.value = '';
            statusField.value = '';

            // Remove the PUT method field if present
            let methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                form.removeChild(methodField);
            }
        }

        document.getElementById('partModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('partModal').classList.add('hidden');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this part?');
    }

    @if ($errors->any())
        openModal();
    @endif
</script>
@endsection
