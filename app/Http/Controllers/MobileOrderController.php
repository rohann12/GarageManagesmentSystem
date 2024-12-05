<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderRepair;
use App\Models\Part;
use App\Models\Repair;
use App\Services\PriorityCalculator;

class MobileOrderController extends Controller
{
    protected $priorityCalculator;

    public function __construct(PriorityCalculator $priorityCalculator)
    {
        $this->priorityCalculator = $priorityCalculator;
    }

    public function index()
    {
        $orders = Order::where('status', 'available')
            ->orderBy('priority', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'string',
            'customer_name' => 'required',
            'email' => 'nullable|email',
            'vehicle_no' => 'required',
            'estimated_completed' => 'required|date',
            'estimated_cost' => 'required|numeric',
            'jobs' => 'required|array',
            'jobs.*' => 'required|string',
        ]);

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->customer_name = $request->customer_name;
        $order->email = $request->email;
        $order->vehicle_no = $request->vehicle_no;
        $order->start_time = now();
        $order->estimated_completed = $request->estimated_completed;
        $order->estimated_cost = $request->estimated_cost;
        $order->repairs = implode(". ", $request->jobs);
        $order->priority = $this->priorityCalculator->calculatePriority(
            $request['start_time'],
            $request['estimated_completed'],
            $request['estimated_cost'],
            implode('. ', $request['jobs'])
        );

        $order->save();

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }

    public function show($id)
    {
        $order = Order::with('repairs', 'parts')->findOrFail($id);
        return response()->json($order);
    }

    public function complete(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'completed';
        $timeTaken = $request->input('timeTaken');
        
        // Format the time taken
        $formattedTimeTaken = gmdate("H:i:s", $timeTaken / 1000);
        $order->time_taken = $formattedTimeTaken;

        $order->save();
        return response()->json(['message' => 'Order completed successfully', 'order' => $order]);
    }

    public function createInvoice(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $taskTimes = json_decode($request->input('task_times'), true);

        foreach ($taskTimes as $taskJson => $taskInfo) {
            $taskDetails = json_decode($taskJson, true);
            OrderRepair::create([
                'order_id' => $orderId,
                'repair_id' => $taskDetails['id'],
                'user_id' => auth()->id(),
                'estimated_time' => $taskDetails['estimated_duration'],
                'time_taken' => $taskInfo['time']
            ]);
        }

        return response()->json(['message' => 'Invoice created successfully.']);
    }

    public function completedIndex()
    {
        $orders = Order::where('status', 'completed')->orderBy('order_id', 'desc')->get();
        return response()->json($orders);
    }
}