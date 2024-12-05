@extends('layouts.adminLayout')
@section('title', 'Repairs')

@section('button')
<!-- Modal toggle -->
<div class="flex h-20 items-center justify-end">
    <button onclick="openModal()" class="px-10 py-2 mr-6 text-white bg-sky-500 rounded-md" type="button">
        + New Repair
    </button>
</div>
@endsection

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="min-height: 400px;">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr>
                <th scope="col" class="px-6 py-3">S.N</th>
                <th scope="col" class="px-6 py-3">repair Name</th>
                <th scope="col" class="px-6 py-3">Estimated Duration</th>
                <th scope="col" class="px-6 py-3">repair Complexity</th>
                <th scope="col" class="px-6 py-3">Actions</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($repairs as $repair)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-5">
                    {{ $repair->repair_name }}
                </td>
                <td class="px-6 py-5">
                    {{ $repair->estimated_duration }}
                </td>
                <td class="px-6 py-5">
                    {{ ucfirst($repair->repair_complexity) }}
                </td>
                <td class="px-6 py-5">
                    <button onclick="openModal({{ $repair->id }}, '{{ $repair->repair_name }}', '{{ $repair->estimated_duration }}', '{{ $repair->repair_complexity }}')" class="text-blue-500 hover:text-blue-700">
                        <svg width="24" height="26" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="stroke-current text-gray-600 hover:text-blue-500">
                            <path
                                d="M13.3148 2.14713C13.1191 1.95231 12.8868 1.79818 12.6312 1.69365C12.3757 1.58912 12.1019 1.53626 11.8258 1.53813C11.5497 1.54 11.2767 1.59656 11.0226 1.70454C10.7684 1.81252 10.5382 1.96978 10.3452 2.16724L2.01666 10.4958L1 14.4619L4.96612 13.4447L13.2947 5.11612C13.4922 4.92321 13.6495 4.69305 13.7575 4.43897C13.8655 4.18488 13.9221 3.91191 13.924 3.63582C13.9258 3.35974 13.873 3.08602 13.7684 2.83049C13.6638 2.57497 13.5097 2.34271 13.3148 2.14713V2.14713Z"
                                stroke="#615E83" class="hover:text-blue-500" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <form action="{{ route('repairs.destroy', $repair) }}" method="post" class="inline-block" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                            <svg width="24" height="24" viewBox="0 0 16 16" fill="none"  xmlns="http://www.w3.org/2000/svg">
                               
                                <path d="M11.7697 14.4616H4.23122C3.9456 14.4616 3.67168 14.3482 3.46972 14.1462C3.26776 13.9442 3.1543 13.6703 3.1543 13.3847V3.69238H12.8466V13.3847C12.8466 13.6703 12.7331 13.9442 12.5312 14.1462C12.3292 14.3482 12.0553 14.4616 11.7697 14.4616Z" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.38477 11.2305V6.92285" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.61621 11.2305V6.92285" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1 3.69238H15" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.61531 1.53809H6.38454C6.09892 1.53809 5.825 1.65155 5.62304 1.85351C5.42108 2.05547 5.30762 2.32939 5.30762 2.61501V3.69193H10.6922V2.61501C10.6922 2.32939 10.5788 2.05547 10.3768 1.85351C10.1748 1.65155 9.90093 1.53809 9.61531 1.53809Z" stroke="#FF0000" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b hover:bg-gray-50">
                <td scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap" colspan="5">
                    No repairs to show
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="repairModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-4">Create New Repair</h2>
        <form id="repairForm" action="{{ route('repairs.store') }}" method="post">
            @csrf
            <input type="hidden" id="repairId" name="repairId" value="">

            <div class="mb-4">
                <label for="repair_name" class="block text-gray-700 text-sm font-bold mb-2">repair Name:</label>
                <input type="text" id="repair_name" name="repair_name" value="{{ old('repair_name') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('repair_name') border-red-500 @enderror">
                @error('repair_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="estimated_duration" class="block text-gray-700 text-sm font-bold mb-2">Estimated Duration:</label>
                <input type="text" id="estimated_duration" name="estimated_duration" value="{{ old('estimated_duration') }}"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estimated_duration') border-red-500 @enderror">
                @error('estimated_duration')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="repair_complexity" class="block text-gray-700 text-sm font-bold mb-2">repair Complexity:</label>
                <select id="repair_complexity" name="repair_complexity"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('repair_complexity') border-red-500 @enderror">
                    <option value="">Select Complexity</option>
                    <option value="low" {{ old('repair_complexity') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('repair_complexity') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('repair_complexity') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="very high" {{ old('repair_complexity') == 'very high' ? 'selected' : '' }}>Very High</option>
                </select>
                @error('repair_complexity')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeModal()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id = null, repair_name = '', estimated_duration = '', repair_complexity = '') {
        const form = document.getElementById('repairForm');
        const modalTitle = document.getElementById('modalTitle');
        const repairId = document.getElementById('repairId');
        const repairNameField = document.getElementById('repair_name');
        const estimatedDurationField = document.getElementById('estimated_duration');
        const repairComplexityField = document.getElementById('repair_complexity');

        if (id) {
            modalTitle.innerText = 'Edit Repair';
            form.action = "{{ url('repairs') }}/" + id;
            form.method = "POST";
            repairId.value = id;
            repairNameField.value = repair_name;
            estimatedDurationField.value = estimated_duration;
            repairComplexityField.value = repair_complexity;

            // Add the PUT method dynamically
            let methodField = document.createElement('input');
            methodField.setAttribute('type', 'hidden');
            methodField.setAttribute('name', '_method');
            methodField.setAttribute('value', 'PUT');
            form.appendChild(methodField);
        } else {
            modalTitle.innerText = 'Create New Repair';
            form.action = "{{ route('repairs.store') }}";
            form.method = "POST";
            repairId.value = '';
            repairNameField.value = '';
            estimatedDurationField.value = '';
            repairComplexityField.value = '';

            // Remove the PUT method field if it exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                form.removeChild(methodField);
            }
        }

        document.getElementById('repairModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('repairModal').classList.add('hidden');
    }

    // If there are validation errors, open the modal
    @if ($errors->any())
        openModal();
    @endif
    function confirmDelete() {
        return confirm('Are you sure you want to delete this repair? This action cannot be undone.');
    }
</script>


@endsection
