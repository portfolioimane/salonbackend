<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingsController extends Controller
{
    /**
     * Get all bookings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Log::info('Fetching all bookings'); // Log entry

        try {
            $bookings = Booking::with(['service'])->get(); // Load related user and service
            Log::info('Bookings fetched successfully', ['bookings_count' => $bookings->count()]);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch bookings'], 500);
        }

        return response()->json(['bookings' => $bookings], 200);
    }

    /**
     * Get details of a specific booking.
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($bookingId)
    {
        Log::info('Fetching booking details', ['booking_id' => $bookingId]);

        try {
            $booking = Booking::with(['service'])->find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Booking not found'], 404);
            }

            Log::info('Booking found', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            Log::error('Error fetching booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch booking'], 500);
        }

        return response()->json(['booking' => $booking], 200);
    }



    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'nullable|string|max:255',
        'service_id' => 'nullable|exists:services,id',
        'payment_method' => 'nullable|string',
        'status' => 'nullable|in:Pending,Confirmed,Cancelled',
        'date' => 'nullable|date',
        'start_time' => 'nullable|string',
        'end_time' => 'nullable|string',
        'total' => 'nullable|numeric',
        'paid_amount' => 'nullable|numeric',
    ]);

    $booking = \App\Models\Booking::find($id);

    if (!$booking) {
        return response()->json(['error' => 'Booking not found'], 404);
    }

    $booking->update($request->all());

    return response()->json(['booking' => $booking]);
}


    /**
     * Delete a booking.
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($bookingId)
    {
        Log::info('Deleting booking', ['booking_id' => $bookingId]);

        try {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found for deletion', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Booking not found'], 404);
            }

            $booking->delete();
            Log::info('Booking deleted successfully', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            Log::error('Error deleting booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to delete booking'], 500);
        }

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
