<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * Display a listing of system logs with filtering, sorting, and pagination
     */
    public function index(Request $request)
    {
        $query = SystemLog::with('user');

        // Search by action or message
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('action', 'ilike', "%$q%")
                    ->orWhere('message', 'ilike', "%$q%");
            });
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $dir  = $request->get('dir', 'desc');

        $allowedSort = ['created_at', 'level', 'action'];
        if (!in_array($sort, $allowedSort)) $sort = 'created_at';
        if (!in_array($dir, ['asc','desc'])) $dir = 'desc';

        $logs = $query->orderBy($sort, $dir)->paginate(20)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
