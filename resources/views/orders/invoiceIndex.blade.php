@extends('layouts.adminLayout')
@section('title', 'Invoices')

@section('button')
<div class="flex h-20 items-center justify-end">
    <button onclick="openStatusModal()" class="px-10 py-2 mr-6 text-white bg-sky-500 rounded-md" type="button">
        Change Status
    </button>
</div>
@endsection

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="min-height: 400px;">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr>
                <th scope="col" class="px-6 py-3">S.N</th>
                <th scope="col" class="px-6 py-3">Order ID</th>
                <th scope="col" class="px-6 py-3">Amount</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-5">
                    {{ $invoice->order_id }}
                </td>
                <td class="px-6 py-5">
                    {{ $invoice->total_price }} NPR
                </td>
                <td class="px-6 py-5">
                    <span class="{{ $invoice->status == 'paid' ? 'text-green-500' : 'text-red-500' }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </td>
                <td class="px-6 py-5 flex flex-row gap-x-3">
                    <!-- Change Status button -->
                    <button onclick="openStatusModal({{ $invoice->id }}, '{{ $invoice->status }}')"
                        class="text-blue-500 hover:text-blue-700">
                        <svg width="24" height="26" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="stroke-current text-gray-600 hover:text-blue-500">
                            <path
                                d="M13.3148 2.14713C13.1191 1.95231 12.8868 1.79818 12.6312 1.69365C12.3757 1.58912 12.1019 1.53626 11.8258 1.53813C11.5497 1.54 11.2767 1.59656 11.0226 1.70454C10.7684 1.81252 10.5382 1.96978 10.3452 2.16724L2.01666 10.4958L1 14.4619L4.96612 13.4447L13.2947 5.11612C13.4922 4.92321 13.6495 4.69305 13.7575 4.43897C13.8655 4.18488 13.9221 3.91191 13.924 3.63582C13.9258 3.35974 13.873 3.08602 13.7684 2.83049C13.6638 2.57497 13.5097 2.34271 13.3148 2.14713V2.14713Z"
                                stroke="#615E83" class="hover:text-blue-500" stroke-width="1.3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                    <!-- Show Invoice button -->
                    <a href="{{ route('showInvoice', $invoice->id) }}" class="text-blue-500 hover:text-blue-700">
                        <svg width="24" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="stroke-current text-gray-600 hover:text-blue-500">
                            <path d="M1.5 12C2.5 7.5 6.5 4 12 4s9.5 3.5 10.5 8c-1 4.5-5 8-10.5 8S2.5 16.5 1.5 12z"
                                stroke="#615E83" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="3" fill="none" stroke="#615E83" stroke-width="1.3" />
                        </svg>
                    </a>

                </td>
            </tr>
            @empty
            <tr class="bg-white border-b hover:bg-gray-50">
                <td scope="row" class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap" colspan="5">
                    No invoices to show
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Status Change Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-4">Change Invoice Status</h2>
        <p id="currentStatus" class="mb-4">Current Status: <span class="font-semibold" id="currentStatusValue"></span>
        </p>
        <form id="statusForm" action="" method="post">
            @csrf
            <input type="hidden" id="invoiceId" name="invoiceId" value="">
            <div class="flex justify-end">
                <button type="button" onclick="closeStatusModal()"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Change
                    Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal(id = null, currentStatus = '') {
        const modal = document.getElementById('statusModal');
        const invoiceIdField = document.getElementById('invoiceId');
        const currentStatusValue = document.getElementById('currentStatusValue');

        invoiceIdField.value = id;
        currentStatusValue.innerText = currentStatus;

        // Set the action for the form based on the current status
        const form = document.getElementById('statusForm');
        form.action = "{{ url('invoices/status') }}"; // Change this to your update route

        modal.classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>
@endsection