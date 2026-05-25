<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Display audit logs with filtering
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filter by action
        if ($request->filled('action')) {
            $query->byAction($request->input('action'));
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->byModel($request->input('model_type'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->input('user_id'));
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->byRole($request->input('role'));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date') . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date') . ' 23:59:59');
        }

        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Get all unique actions for the dropdown
        $actions = AuditLog::distinct()->pluck('action')->sort();
        
        // Get all unique model types
        $modelTypes = AuditLog::whereNotNull('model_type')->distinct()->pluck('model_type')->sort();
        
        // Get users for filtering
        $users = User::orderBy('name')->get();

        // Paginate results
        $auditLogs = $query->latest('created_at')->paginate(20);

        return view('audit-logs.index', compact('auditLogs', 'actions', 'modelTypes', 'users'));
    }

    /**
     * Show detailed view of a specific audit log
     */
    public function show(AuditLog $auditLog)
    {   
        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Get user audit history (for customer/personal view)
     */
    public function userHistory(Request $request)
    {
        // Get current logged-in user
        $user = Auth::user();

        // Get audit logs for the current user
        $query = AuditLog::where('user_id', $user->id);

        // Filter by action
        if ($request->filled('action')) {
            $query->byAction($request->input('action'));
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->byModel($request->input('model_type'));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date') . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date') . ' 23:59:59');
        }

        $actions = AuditLog::where('user_id', $user->id)->distinct()->pluck('action')->sort();
        $modelTypes = AuditLog::where('user_id', $user->id)->whereNotNull('model_type')->distinct()->pluck('model_type')->sort();

        $auditLogs = $query->latest('created_at')->paginate(15);

        return view('audit-logs.user-history', compact('auditLogs', 'actions', 'modelTypes'));
    }

    /**
     * Get audit logs for a specific model
     */
    public function modelHistory($modelType, $modelId)
    {
        $auditLogs = AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->latest('created_at')
            ->paginate(20);

        return view('audit-logs.model-history', compact('auditLogs', 'modelType', 'modelId'));
    }

    /**
     * Export audit logs as CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

        // Apply the same filters as index
        if ($request->filled('action')) {
            $query->byAction($request->input('action'));
        }

        if ($request->filled('model_type')) {
            $query->byModel($request->input('model_type'));
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->input('user_id'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date') . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date') . ' 23:59:59');
        }

        $auditLogs = $query->latest('created_at')->get();

        // Generate CSV
        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV header
            fputcsv($file, [
                'Date',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'Description',
                'IP Address',
            ]);

            // Add data rows
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'Unknown',
                    $log->action,
                    $log->getModelLabel(),
                    $log->model_id,
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::where('created_at', '>=', now()->startOfDay())->count(),
            'this_week_logs' => AuditLog::recent(7)->count(),
            'this_month_logs' => AuditLog::recent(30)->count(),
            'total_users_active' => AuditLog::distinct('user_id')->count('user_id'),
            'actions_breakdown' => AuditLog::select('action')
                ->selectRaw('count(*) as count')
                ->groupBy('action')
                ->get()
                ->pluck('count', 'action'),
            'models_breakdown' => AuditLog::select('model_type')
                ->selectRaw('count(*) as count')
                ->groupBy('model_type')
                ->get(),
        ];

        return response()->json($stats);
    }
}
