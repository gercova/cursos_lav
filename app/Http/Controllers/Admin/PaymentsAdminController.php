<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View {
        $query = Payment::with(['user', 'enrollment.course'])
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('names', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('enrollment.course', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->paginate(20);

        $stats = [
            'total'     => Payment::sum('amount'),
            'pending'   => Payment::where('status', 'pending')->sum('amount'),
            'completed' => Payment::where('status', 'completed')->sum('amount'),
            'failed'    => Payment::where('status', 'failed')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment): JsonResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])]
        ]);

        $payment->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del pago actualizado.'
        ]);
    }
}
