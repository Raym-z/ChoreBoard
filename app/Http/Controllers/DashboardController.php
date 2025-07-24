<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserChore;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $households = $user->households;
        $currentHouseholdId = session('current_household_id', $households->first()?->id);
        $period = $request->query('period', 'today'); // today, week, month
        $date = $request->query('date');
        $baseDate = $date ? Carbon::parse($date) : Carbon::today();

        // Overdue: due before today, not completed, and in current household
        $overdueChores = UserChore::with('chore')
            ->where('user_id', $user->id)
            ->whereHas('chore', function($q) use ($currentHouseholdId) {
                $q->where('household_id', $currentHouseholdId);
            })
            ->where('status', 'pending')
            ->whereDate('due_date', '<', Carbon::today())
            ->get();
        foreach ($overdueChores as $uc) {
            $uc->overdue = true;
        }

        // Determine date range for filter
        if ($period === 'week') {
            $start = $baseDate->copy()->startOfWeek();
            $end = $baseDate->copy()->endOfWeek();
        } elseif ($period === 'month') {
            $start = $baseDate->copy()->startOfMonth();
            $end = $baseDate->copy()->endOfMonth();
        } else { // today
            $start = $baseDate->copy();
            $end = $baseDate->copy();
        }

        // Chores for the selected period, filtered by household
        $chores = UserChore::with('chore')
            ->where('user_id', $user->id)
            ->whereHas('chore', function($q) use ($currentHouseholdId) {
                $q->where('household_id', $currentHouseholdId);
            })
            ->whereBetween('due_date', [$start, $end])
            ->get();
        foreach ($chores as $uc) {
            $uc->overdue = false;
        }

        // Merge overdue chores at the top (avoid duplicates)
        $allChores = $overdueChores->merge($chores->whereNotIn('id', $overdueChores->pluck('id')));

        // Sort: Overdue -> Pending -> Completed, then by due_date ascending
        $allChores = $allChores->sort(function($a, $b) {
            $statusOrder = function($chore) {
                if ($chore->overdue ?? false) return 0; // Overdue first
                if ($chore->status === 'pending') return 1; // Pending next
                return 2; // Completed last
            };
            $cmp = $statusOrder($a) <=> $statusOrder($b);
            if ($cmp === 0) {
                return strtotime($a->due_date) <=> strtotime($b->due_date);
            }
            return $cmp;
        })->values();

        // Add due_label to each chore
        $now = Carbon::today();
        foreach ($allChores as $chore) {
            $due = Carbon::parse($chore->due_date);
            if ($due->isToday()) {
                $chore->due_label = 'Today';
            } elseif ($due->isTomorrow()) {
                $chore->due_label = 'Tomorrow';
            } elseif ($due->isYesterday()) {
                $chore->due_label = 'Yesterday';
            } elseif ($due->isSameWeek($now)) {
                if ($due->isFuture()) {
                    $chore->due_label = 'This week';
                } else {
                    $chore->due_label = $due->diffForHumans($now, ['parts' => 1]);
                }
            } elseif ($due->isSameMonth($now)) {
                $chore->due_label = 'This month';
            } else {
                $chore->due_label = $due->diffForHumans($now, ['parts' => 1]);
            }
        }

        // For progress/points, use this week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $choresThisWeek = UserChore::with('chore')
            ->where('user_id', $user->id)
            ->whereHas('chore', function($q) use ($currentHouseholdId) {
                $q->where('household_id', $currentHouseholdId);
            })
            ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->get();
        $completedThisWeek = $choresThisWeek->where('status', 'completed')->count();
        $totalThisWeek = $choresThisWeek->count();
        $progressPercent = $totalThisWeek > 0 ? round(($completedThisWeek / $totalThisWeek) * 100) : 0;
        $pointsThisWeek = $choresThisWeek->where('status', 'completed')->sum(function($uc) {
            return $uc->chore->points ?? 0;
        });

        // Leaderboard: top 5 users by points earned this week
        $leaderboard = User::with(['userChores.chore'])
            ->get()
            ->map(function($user) use ($startOfWeek, $endOfWeek) {
                $points = $user->userChores
                    ->where('status', 'completed')
                    ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
                    ->sum(function($uc) {
                        return $uc->chore->points ?? 0;
                    });
                return [
                    'name' => $user->name,
                    'points' => $points,
                ];
            })
            ->sortByDesc('points')
            ->take(10)
            ->values();

        // Calculate previous/next period for arrows
        if ($period === 'week') {
            $prevDate = $baseDate->copy()->subWeek()->toDateString();
            $nextDate = $baseDate->copy()->addWeek()->toDateString();
        } elseif ($period === 'month') {
            $prevDate = $baseDate->copy()->subMonth()->toDateString();
            $nextDate = $baseDate->copy()->addMonth()->toDateString();
        } else { // today
            $prevDate = $baseDate->copy()->subDay()->toDateString();
            $nextDate = $baseDate->copy()->addDay()->toDateString();
        }

        $isAdmin = $user->role === 'admin';
        $isToday = $period === 'today' && $baseDate->isToday();
        $isThisWeek = $period === 'week' && $baseDate->isSameWeek(now());
        $isThisMonth = $period === 'month' && $baseDate->isSameMonth(now());

        return view('dashboard', [
            'chores' => $allChores,
            'period' => $period,
            'baseDate' => $baseDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'progressPercent' => $progressPercent,
            'pointsThisWeek' => $pointsThisWeek,
            'leaderboard' => $leaderboard,
            'isAdmin' => $isAdmin,
            'isToday' => $isToday,
            'isThisWeek' => $isThisWeek,
            'isThisMonth' => $isThisMonth,
        ]);
    }
}