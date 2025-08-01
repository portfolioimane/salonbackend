<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
       $finances = Finance::all();

    // Log all finance records as array
    \Log::info('All finance records:', $finances->toArray());

    return $finances;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:revenue,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        return Finance::create($data);
    }

    public function show(Finance $finance)
    {
        return $finance;
    }

    public function update(Request $request, Finance $finance)
    {
        $data = $request->validate([
            'type' => 'sometimes|in:revenue,expense',
            'title' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
        ]);

        $finance->update($data);

        return $finance;
    }

    public function destroy(Finance $finance)
    {
        $finance->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
