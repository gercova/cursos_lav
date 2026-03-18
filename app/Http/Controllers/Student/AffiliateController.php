<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }
    /**
     * Mostrar dashboard de afiliados
     */
    public function dashboard(): View|RedirectResponse {
        $user = Auth::user();
        
        // Verificar si tiene código de promoción
        if (!$user->code) {
            // Redirigir a perfil para generar código
            return redirect()->route('student.profile')->with('info', 'Primero necesitas generar un código de promoción.');
        }

        // Estadísticas
        $stats = [
            'total_sales'       => $user->courses_sold_count ?? 0,
            'total_commission'  => $user->total_commission ?? 0,
            'pending_sales'     => CourseSale::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_sales'   => CourseSale::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        // Ventas recientes
        $recentSales = CourseSale::with(['course', 'buyer'])
            ->where('user_id', $user->id)
            ->orderBy('sold_at', 'desc')
            ->limit(10)
            ->get();

        // Cursos más vendidos
        $topCourses = CourseSale::selectRaw('course_id, COUNT(*) as sales_count, SUM(sale_amount) as total_revenue')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->groupBy('course_id')
            ->with('course')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        return view('student.affiliate.dashboard', compact('user', 'stats', 'recentSales', 'topCourses'));
    }

    /**
     * Mostrar ventas detalladas
     */
    public function sales(): View {
        $user = Auth::user();
        
        // Tu variable original paginada
        $sales = CourseSale::with(['course', 'buyer', 'order'])
            ->where('user_id', $user->id)
            ->orderBy('sold_at', 'desc')
            ->paginate(10);

        // Agregamos las variables que pide tu blade
        $stats = [
            'total_sales'       => $user->courses_sold_count ?? 0,
            'total_commission'  => $user->total_commission ?? 0,
            'pending_sales'     => CourseSale::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_sales'   => CourseSale::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $recentSales = CourseSale::with(['course', 'buyer'])
            ->where('user_id', $user->id)
            ->orderBy('sold_at', 'desc')
            ->limit(10)
            ->get();

        $topCourses = CourseSale::selectRaw('course_id, COUNT(*) as sales_count, SUM(sale_amount) as total_revenue')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->groupBy('course_id')
            ->with('course')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        // Enviamos todo a la vista
        return view('student.affiliate.sales', compact('sales', 'user', 'stats', 'recentSales', 'topCourses'));
    }

    /**
     * Mostrar reportes y estadísticas
     */
    public function reports(Request $request): View {
        $user = Auth::user();
        
        // Filtrar por fecha
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = CourseSale::where('user_id', $user->id)
            ->whereBetween('sold_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Estadísticas por período
        $periodStats = [
            'total_sales'       => $query->count(),
            'total_revenue'     => $query->sum('sale_amount'),
            'total_commission'  => $query->sum('commission_amount'),
            'completed_sales'   => $query->where('status', 'completed')->count(),
        ];

        // Ventas por día (para gráfico)
        $salesByDay = CourseSale::where('user_id', $user->id)
            ->whereBetween('sold_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(sold_at) as date, COUNT(*) as count, SUM(sale_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('student.affiliate.reports', compact('user', 'periodStats', 'salesByDay', 'startDate', 'endDate'));
    }

    /**
     * Mostrar enlaces de afiliado
     */
    public function links(): View {
        $user = Auth::user();
        
        // Obtener cursos con códigos promocionales activos
        $promoCourses = Course::whereHas('coursePromotionCode', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('is_active', true);
        })
        ->with(['coursePromotionCode' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('is_active', true);
        }])
        ->get();

        // Generar enlaces para cada curso
        $affiliateLinks = [];
        foreach ($promoCourses as $course) {
            $promoCode = $course->coursePromotionCode->first();
            if ($promoCode) {
                $affiliateLinks[] = [
                    'course'                => $course,
                    'promo_code'            => $promoCode->code,
                    'link'                  => route('curso-promo', ['slug' => $course->slug, 'code' => $user->code]),
                    'discount_percentage'   => $promoCode->discount_percentage ?? 0,
                ];
            }
        }

        return view('student.affiliate.links', compact('user', 'affiliateLinks'));
    }

    /**
     * API: Obtener estadísticas para dashboard
     */
    public function getStats(Request $request) {
        $user = Auth::user();
        
        $stats = [
            'total_sales'       => $user->courses_sold_count ?? 0,
            'total_commission'  => number_format($user->total_commission ?? 0, 2),
            'pending_sales'     => CourseSale::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'monthly_sales'     => CourseSale::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('sold_at', now()->month)
                ->whereYear('sold_at', now()->year)
                ->count(),
        ];

        return response()->json($stats);
    }
}
