<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summary(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        // Total appointments (bookings) in selected month/year
        $totalAppointments = Booking::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();

        // Total revenue from bookings (sum of 'total' column)
        $totalRevenue = Booking::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total');

        // New customers in the month (unique emails who booked)
        $newCustomers = Booking::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct('email')
            ->count('email');

        // Occupancy Rate (custom calculation, example: total appointments / max capacity * 100)
        $maxCapacity = 100; // adjust as needed
        $occupancyRate = $maxCapacity > 0 ? round(($totalAppointments / $maxCapacity) * 100, 2) : 0;

        return response()->json([
            'totalAppointments' => $totalAppointments,
            'totalRevenue' => $totalRevenue,
            'newCustomers' => $newCustomers,
            'occupancyRate' => $occupancyRate,
        ]);
    }

    public function popularServices(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        // Aggregate bookings grouped by service
        $services = Booking::select('service_id', DB::raw('COUNT(*) as timesBooked'), DB::raw('SUM(total) as revenue'))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('service_id')
            ->with('service:id,name') // eager load service name
            ->orderByDesc('timesBooked')
            ->get()
            ->map(function ($booking) {
                return [
                    'serviceName' => $booking->service ? $booking->service->name : 'Unknown',
                    'timesBooked' => $booking->timesBooked,
                    'revenue' => $booking->revenue,
                ];
            });

        return response()->json($services);
    }

    public function topClients(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        // Aggregate bookings grouped by email (client)
        $clients = Booking::select('email', DB::raw('COUNT(*) as visits'))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('email')
            ->orderByDesc('visits')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'clientName' => $booking->email ?? 'Unknown',
                    'visits' => $booking->visits,
                ];
            });

        return response()->json($clients);
    }
}
