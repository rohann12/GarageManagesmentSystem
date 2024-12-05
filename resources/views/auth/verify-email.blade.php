<script src="{{ asset('tailwind.jsx') }}"></script>


<p class="flex mt-16 align-center justify-center text-gray-800 text-xl mb-6">
    Please verify your email address by clicking the link we sent to your email.
</p>
    
<form method="POST" action="{{ route('verification.send') }}" class="flex justify-center">
    @csrf
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500">
        Resend Verification Email
    </button>
</form>
