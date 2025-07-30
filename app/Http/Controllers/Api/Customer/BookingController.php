<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Slot;  // Assuming you have a Slot model or manage slots somehow
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessHours;



class BookingController extends Controller
{

public function getAvailableSlots(Request $request)
{
    $date = $request->query('date');
    $serviceId = $request->query('service_id');

    Log::info("Fetching available slots for date: $date and service_id: $serviceId");

    $dayName = date('l', strtotime($date));
    $businessHours = BusinessHours::where('day', $dayName)->first();

    if (!$businessHours || $businessHours->is_closed) {
        Log::error("Business hours not set or closed for day: $dayName");
        return response()->json(['message' => "Business hours not set or closed for $dayName"], 404);
    }

    $startTime = Carbon::parse($businessHours->open_time);
    $endTime = Carbon::parse($businessHours->close_time);

    $service = Service::find($serviceId);
    if (!$service) {
        Log::error("Service not found for id: $serviceId");
        return response()->json(['message' => 'Service not found'], 404);
    }

    Log::info("Service duration: {$service->duration} minutes");

    // Fetch all bookings on the selected date that conflict, with any relevant status (e.g., confirmed or pending)
    $bookings = Booking::where('date', $date)
        ->where('service_id', $serviceId)
        ->whereIn('status', ['confirmed', 'pending']) // Consider these as blocking slots
        ->get();

    Log::info("Found {$bookings->count()} active bookings for date $date and service $serviceId");

    $availableSlots = [];
    $currentTime = $startTime->copy();

    while ($currentTime->lt($endTime)) {
        $slotStart = $currentTime->format('H:i');
        $slotEnd = $currentTime->copy()->addMinutes($service->duration);

        // If slot end is after closing time, stop
        if ($slotEnd->gt($endTime)) break;

        $slotEndFormatted = $slotEnd->format('H:i');

        // Check if slot overlaps any booking
        $isBooked = $bookings->contains(function ($booking) use ($slotStart, $slotEndFormatted) {
            // booking times assumed in H:i format
            return ($booking->start_time < $slotEndFormatted && $booking->end_time > $slotStart);
        });

        if (!$isBooked) {
            $availableSlots[] = [
                'start_time' => $slotStart,
                'end_time' => $slotEndFormatted,
            ];
        }

        $currentTime->addMinutes($service->duration);
    }

Log::info("Available slots: " . json_encode($availableSlots));
    return response()->json($availableSlots);
}


    // Handle booking creation
public function createBooking(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'service_id' => 'required|exists:services,id',
        'date' => 'required|date', // Ensure date is passed
        'start_time' => 'required|date_format:H:i', // Ensure time format is correct
        'end_time' => 'required|date_format:H:i|after:start_time', // Ensure end_time is after start_time
        'payment_method' => 'required|string',
        'total' => 'required|numeric',
    ]);

    // Retrieve the selected service
    $service = Service::find($validated['service_id']);
    if (!$service) {
        return response()->json(['message' => 'Service not found'], 404);
    }

$existingBooking = Booking::where('date', $validated['date'])
    ->where('service_id', $service->id)
    ->whereIn('status', ['confirmed', 'pending']) // Only check active bookings
    ->where(function ($query) use ($validated) {
        $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
              ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
              ->orWhere(function ($query) use ($validated) {
                  $query->where('start_time', '<=', $validated['start_time'])
                        ->where('end_time', '>=', $validated['end_time']);
              });
    })
    ->exists();


    // Set booking status and paid_amount based on payment_method
    $status = 'pending'; // Default status
    $paidAmount = 0;

    if (in_array($validated['payment_method'], ['stripe', 'paypal'])) {
        $status = 'completed'; // Set status to confirmed for Stripe/PayPal
        $paidAmount = 50; // Set the booking fee as the paid amount
    }

    // Create the booking WITHOUT user_id
    $booking = Booking::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'service_id' => $validated['service_id'],
        'date' => $validated['date'],
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
        'payment_method' => $validated['payment_method'],
        'total' => $validated['total'],
        'status' => $status,
        'paid_amount' => $paidAmount,
    ]);

    /* Optional: Email reminder scheduling here, unchanged */

    return response()->json([
        'message' => 'Booking initiated successfully!',
        'booking' => $booking,
    ], 201);
}

}
