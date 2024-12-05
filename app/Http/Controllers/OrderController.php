<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderRepair;
use App\Models\Part;
use App\Models\Repair;
use App\Models\User;
use App\Services\PriorityCalculator;
use App\Services\ExperiencePoint;
use App\Services\NotificationService;
use App\Services\TaskAssigner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    // Show all orders
    public function index()
    {
        // Fetch orders sorted by priority in descending order
        $orders = Order::where('status', 'available')
            ->orderBy('priority', 'desc')
            ->get();
            $mechanics=User::all();
        // Pass the sorted orders to the view
        return view('orders.index', compact('orders','mechanics'));
    }

    // Show the form for creating a new order
    public function create()
    {
        $repairs = Repair::orderBy('repair_complexity', 'desc')->get();
        return view('orders.create', [
            'repairs' => $repairs,
        ]);
    }

    // Store a newly created order in the database
    protected $priorityCalculator;
    protected $taskAssigner;
    protected $notificationService;

    public function __construct(PriorityCalculator $priorityCalculator, TaskAssigner $taskAssigner, NotificationService $notificationService)
    {
        $this->priorityCalculator = $priorityCalculator;
        $this->taskAssigner = $taskAssigner;
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'string',
            'customer_name' => 'required',
            'email' => 'nullable|email',
            'vehicle_no' => 'required',
            'start_time' => 'date',
            'estimated_completed' => 'required|date',
            'estimated_cost' => 'required|numeric',
            'jobs' => 'required|array',
            'jobs.*' => 'required|string', // Assuming each job is a string
        ]);
        if (!$request) {
            return redirect()->back()->withErrors($request)->withInput();
        }

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->customer_name = $request->customer_name;
        $order->email = $request->email;
        $order->vehicle_no = $request->vehicle_no;
        $order->start_time = now();
        $order->estimated_completed = $request->estimated_completed;
        $order->estimated_cost = $request->estimated_cost;
        $order->repairs = implode(". ", $request->jobs); // Concatenate jobs into a single string
        $priority = $this->priorityCalculator->calculatePriority(
            startTime: $request['start_time'],
            estimatedCompleted: $request['estimated_completed'],
            estimatedCost: $request['estimated_cost'],
            concatenatedJobs: implode('. ', $request['jobs'])
        );
        $order->priority = $priority;
        $order->save();

        // Assign mechanic using TaskAssigner
        $assignedMechanic = app(TaskAssigner::class)->assignMechanic($request->jobs);
        if ($assignedMechanic) {
            // Update order with assigned mechanic
            $order->assigned_to = $assignedMechanic->id;
            $order->save();
           
            // Send notification to the assigned  mechanic
            app(NotificationService::class)->notifyMechanic($assignedMechanic, $order->order_id);
        } else {
            // Notify the default mechanic with ID 1
            $defaultMechanic = User::find(1); // Fetch the mechanic with ID 1

            // Check if the default mechanic exists
            if ($defaultMechanic) {
                app(NotificationService::class)->notifyMechanic($defaultMechanic, $order->order_id);
            } else {
                // Handle case if default mechanic does not exist
                return redirect()->route('orders.index')->with('error', 'Default mechanic not found, please check your database.');
            }
        }
        return redirect()->route('orders.index')->with('success', 'Data inserted successfully');
    }

    public function show($id)
    {
        $order = Order::find($id);

        // Explode the repairs string using a dot as the delimiter
        $repairIds = explode('.', $order->repairs);
        // Trim any extra whitespace around the IDs
        $repairIds = array_map('trim', $repairIds);

        $repairs = Repair::whereIn('id', $repairIds)->get();

        $parts = Part::all();
        User::where('id', Auth::id())->update(['is_online' => 2]); // Set is_online to 1


        return view('orders.show', compact('order', 'repairs', 'parts'));
    }

    public function complete(Request $request, $id)
    {
        // Fetch the order
        $order = Order::findOrFail($id);
        $order->status = 'completed';

        // Get time taken and task/parts data from the request
        $timeTaken = $request->query('timeTaken');
        $taskData = $request->query('taskData');
        $partsData = $request->query('parts');

        // Format time taken into HH:MM:SS
        $hours = floor($timeTaken / (1000 * 60 * 60));
        $minutes = floor(($timeTaken % (1000 * 60 * 60)) / (1000 * 60));
        $seconds = floor(($timeTaken % (1000 * 60)) / 1000);
        $formattedTimeTaken = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        $order->time_taken = $formattedTimeTaken;

        // Decode task and parts data
        $taskTimes = json_decode($taskData, true) ?? [];
        $parts = json_decode($partsData, true) ?? [];

        // Fetch parts from the database and create an array with required fields
        $partsArray = [];
        $totalPartPrice = 0;

        foreach ($parts as $partName) {
            $part = Part::where('part_name', $partName)->first();
            if ($part) {
                // dd($part);
                $partsArray[] = [
                    'id' => $part->id,
                    'name' => $part->part_name,
                    'price' => $part->price,
                ];
                $totalPartPrice += $part->price; // Add to total price
            }
        }

        // Calculate labor charge
        $laborCharges=100;
        $laborChargePer30Min = 100;
        $totalTimeInMinutes = ($hours * 60) + $minutes;
        $laborCharges += floor($totalTimeInMinutes / 30) * $laborChargePer30Min;

        // Calculate total price
        $totalPrice = $totalPartPrice + $laborCharges;

        // Calculate points
        $experiencePointService = new ExperiencePoint();
        $points = [];
        $totalPoints = 0;
        foreach ($taskTimes as $taskJson => $taskInfo) {
            $taskDetails = json_decode($taskJson, true);
            // dd($taskDetails);
            $pointsEarned = $experiencePointService->calculatePoints($taskDetails, $taskInfo, auth()->id());
            $points[$taskJson] = $pointsEarned;
            $totalPoints += $pointsEarned;

        }

        // Save the updated order
        $order->save();
        User::where('id', Auth::id())->update(['is_online' => 1]); // Set is_online to 1

        // Return the view with all necessary data
        return view('orders.complete', [
            'order' => $order,
            'taskTimes' => $taskTimes,
            'parts' => $partsArray, // Pass the structured array to the view
            'points' => $points,
            'totalPoints' => $totalPoints,
            'totalPrice' => $totalPrice, // Pass the total price to the view
            'laborCharges' => $laborCharges, // Pass the labor charges to the view
        ]);
    }

    public function createInvoice(Request $request, $orderId)
    {
        // Fetch the order
        $order = Order::findOrFail($orderId);

        // Calculate total price and labor charges from request
        $totalPrice = $request->input('total_price');
        $laborCharges = $request->input('labor_charges');

        // Create the invoice
        $invoice = Invoice::create([
            'order_id' => $orderId,
            'total_price' => $totalPrice,
            'labor_charges' => $laborCharges,
            'status' => 'unpaid', // set default status
        ]);

        // Decode the task times from the request
        $taskTimes = json_decode($request->input('task_times'), true);

        // Prepare to store experience points
        $experiencePointService = new ExperiencePoint();
        $totalPoints = 0;

        foreach ($taskTimes as $taskJson => $taskInfo) {
            $taskDetails = json_decode($taskJson, true);

            // Calculate points for this task
            $pointsEarned = $experiencePointService->calculatePoints($taskDetails, $taskInfo, auth()->id());
            $totalPoints += $pointsEarned;

            // Create OrderRepair record with points earned
            OrderRepair::create([
                'order_id' => $orderId,
                'repair_id' => $taskDetails['id'],
                'user_id' => auth()->id(),
                'user_Xp' => auth()->user()->experience_points, 
                'estimated_time' => $taskDetails['estimated_duration'],
                'time_taken' => $taskInfo['time'],
                'Xp' => $pointsEarned, // Store points earned here
            ]);
        }

        // Update user's experience points
        $user = User::find(auth()->id());
        $user->update([
            'experience_points' => $user->experience_points + $totalPoints,
        ]);

        // Decode parts from request and deduct quantities
        $parts = json_decode($request->input('parts'), true);
        $partsArray = []; // To hold part details for the invoice
        foreach ($parts as $part) {
            // Assuming the part contains an 'id' and you need to deduct its quantity
            $partModel = Part::find($part['id']);
            if ($partModel) {
                $partsArray[] = [
                    'id' => $partModel->id,
                    'name' => $partModel->part_name,
                    'price' => $partModel->price,
                ];
                $partModel->quantity -= 1; // Deduct quantity as needed
                $partModel->save();
            }
        }

        // Redirect to the invoice page with necessary data
        return redirect()->route('showInvoice', [
            'invoice' => $invoice->id,
            'parts' => $partsArray,
            'laborCharges' => $laborCharges,
        ]);
    }



    public function showInvoice($invoiceId, Request $request)
    {
        // Fetch the invoice
        $invoice = Invoice::findOrFail($invoiceId);

        // Fetch the order associated with the invoice
        $order = $invoice->order_id;

        // Get parts and labor charges from the request
        $parts = $request->input('parts', []);
        $laborCharges = $request->input('laborCharges', 0);

        // Calculate total parts price
        $totalPartsPrice = array_sum(array_column($parts, 'price'));

        // Calculate final total (parts + labor)
        $finalTotal = $totalPartsPrice + $laborCharges;

        // Return the view with all necessary data
        return view('orders.invoice', [
            'invoice' => $invoice,
            'order' => $order,
            'parts' => $parts,
            'totalPartsPrice' => $totalPartsPrice,
            'laborCharges' => $laborCharges,
            'finalTotal' => $finalTotal,
        ]);
    }

    public function invoiceIndex()
    {
        // Fetch all invoices
        $invoices = Invoice::all(); // No relationship assumed, just fetch all invoices

        // Return the view with invoices
        return view('orders.invoiceIndex', compact('invoices'));
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'invoiceId' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::find($request->invoiceId);
        $invoice->status = ($invoice->status === 'paid') ? 'unpaid' : 'paid';
        $invoice->save();

        return redirect()->route('invoice')->with('success', 'Invoice status updated successfully.');
    }


    // Show the form for editing a specific order
    public function edit(Order $order)
    {
        return view('orders.edit', ['order' => $order]);
    }

    // Update the specified order in the database
    public function update(Request $request, Order $order)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'vehicle_no' => 'required|string|max:10',
            // Add validation rules for other fields
        ]);

        // Update the order
        $order->update($validatedData);

        // Redirect to a relevant page
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    // Remove the specified order from the database
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
