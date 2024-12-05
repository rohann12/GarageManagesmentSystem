@extends('layouts.adminLayout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-3xl font-bold text-center mb-6">My Profile</h2>

        <div class="flex items-center mb-6">
            <img src="{{ asset('logos/profile.svg') }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-2 border-gray-300">
            <div class="ml-4">
                <h3 class="text-2xl font-semibold">{{ $user->name }}</h3>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-100 rounded-lg p-4 shadow">
                <h4 class="font-semibold">Experience Points</h4>
                <p class="text-xl text-blue-500">{{ $user->experience_points }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 shadow">
                <h4 class="font-semibold">Assigned Level</h4>
                <p class="text-xl text-blue-500">{{ $user->level }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 shadow">
                <h4 class="font-semibold">Total Repairs Done</h4>
                <p class="text-xl text-blue-500">{{ $repairCount }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 shadow">
                <h4 class="font-semibold">Completed Orders</h4>
                <p class="text-xl text-blue-500">{{ $completedOrdersCount }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 shadow">
                <h4 class="font-semibold">Daily XP Earned</h4>
                <p class="text-xl text-blue-500">{{ $dailyXP }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="font-semibold">Progress to Next Level</h4>
            <div class="relative h-48">
                <canvas id="xpChart" class="absolute inset-0"></canvas>
                <div class="flex items-center justify-center h-full">
                    <span class="text-2xl font-bold text-blue-500">{{ $user->experience_points }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const experiencePoints = {{ $user->experience_points }};
    const levelThresholds = [100, 300, 600, 1000, 1500, 2000];
    let nextLevelXP = levelThresholds[Math.min(levelThresholds.length - 1, Math.floor(experiencePoints / 100))] || 0;
    
    const remainingXP = nextLevelXP - experiencePoints;

    const ctx = document.getElementById('xpChart').getContext('2d');
    const xpChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Current XP', 'Remaining XP'],
            datasets: [{
                data: [experiencePoints, remainingXP],
                backgroundColor: ['#3b82f6', '#e5e7eb'],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%', // To create a ring chart
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });
</script>
@endsection
