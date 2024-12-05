<div class="sidebar w-72 h-full  border-r border-gray-200">
    <div class="flex h-20 border-b border-gray-200 justify-center bg-black">
        <span class="text-2xl cursor-pointer h-full">
            <img src="{{ asset('logos/lo.png') }}" alt="icon" class="h-full">
        </span>
    </div>
    <div class="flex flex-row items-center h-20 ps-8">
        <div class=" h-10 w-10 rounded-full bg-red-200">
            <img src="{{ asset('logos/logo.png') }}" alt="Profile Photo"
                onerror="this.onerror=null;this.src='{{ asset('images/minesh.jpg') }}';" class="rounded-full"
                style="height:100%;width:100%;object-fit:cover;">
        </div>
        <div class="flex flex-col ml-4">
            <h3 class="font-bold text-sm ">{{ Auth::user()->name }}</h3>
            <span class="text-gray-300 text-sm">@ {{ Auth::user()->email }}</span>
        </div>
    </div>

    <div id="sidebar" class="flex flex-col gap-y-3 mt-4 sidebar-links text-gray-500 ">
        <div class="flex items-center  h-12">
            <a href="{{route('home')}}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/dashboard.svg') }}"></object>
                Dashboard</a>
        </div>
        <div class="flex items-center h-12 ">
            <a href="{{route('orders.index')}}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/orders.svg') }}"></object>
                Orders</a>
        </div>

        <div class="flex items-center  h-12">
            <a href="{{route('employees.index')}}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/employees.svg') }}"></object>
                Employee Update</a>
        </div>
        <div class="flex items-center  h-12">
            <a href="{{route('customers.index')}}" class="flex flex-row items-center gap-3 w-full py-4 ps-8">
                <object type="image/svg+xml" data="{{ asset('logos/clients-and-partners.svg') }}"></object>
                Customers</a>
        </div>
        <div class="flex items-center  h-12">
            <a href="{{route('invoice')}}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/invoice.svg') }}"></object>
                Invoice</a>
        </div>
        {{-- <div class="flex items-center  h-12">
            <a href="{{ route('careers.index') }}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/careers.svg') }}"></object>
                Careers</a>
        </div> --}}
        {{-- <div class="flex items-center h-12">
            <a href="{{ route('message.index') }}" class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/company-details.svg') }}"></object>
                Messages</a>
            y
        </div> --}}
        <div class="flex items-center h-12">
            <a href="{{route('repairs.index')}}"class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/repair.svg') }}"></object>Repairs</a>
        </div>
        <div class="flex items-center h-12">
            <a href="{{route('parts.index')}}"class="flex flex-row items-center gap-3 w-full py-4 px-8">
                <object type="image/svg+xml" data="{{ asset('logos/parts.svg') }}"></object>Parts</a>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            // Get the current page URL
            const currentPageUrl = window.location.href;

            // Get all sidebar links
            const sidebarLinks = document.querySelectorAll('#sidebar a');

            // Loop through each link
            sidebarLinks.forEach(link => {
                if (currentPageUrl.startsWith(link.href)) {
                    link.classList.add('text-amber-500', 'font-bold', 'bg-gray-200',
                        'rounded-lg');
                    const svgObject = link.querySelector('object');
                    if (svgObject) {
                        if (svgObject.contentDocument && svgObject.contentDocument
                            .readyState === 'complete') {
                            // SVG content already loaded
                            applySVGColor(svgObject);
                        } else {
                            // Wait for SVG content to load
                            svgObject.addEventListener('load', function() {
                                applySVGColor(svgObject);
                            });
                        }
                    }
                }
            });
        }, 1000); // 3000 milliseconds = 3 seconds
    });

    function applySVGColor(svgObject) {
        const svgDoc = svgObject.contentDocument;
        if (svgDoc) {
            const svg = svgDoc.querySelector('svg');
            if (svg) {
                const paths = svg.querySelectorAll('path');
                paths.forEach(path => {
                    path.setAttribute('fill', 'rgb(245 158 11)');
                });
            }
        }
    }
    function goBack() {
    window.history.back();
}
</script>