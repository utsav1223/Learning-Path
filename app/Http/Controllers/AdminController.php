<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->authorizeAdmin($request);

        $users = User::query()
            ->where('is_admin', false)
            ->with('profile', 'assessmentAttempt')
            ->withCount('supportTickets')
            ->latest()
            ->paginate(12);

        $tickets = SupportTicket::query()
            ->with('user')
            ->latest()
            ->paginate(10, ['*'], 'tickets_page');

        $learnerQuery = User::query()->where('is_admin', false);
        $completedAssessments = (clone $learnerQuery)
            ->whereHas('assessmentAttempt', fn ($query) => $query->whereNotNull('completed_at'))
            ->count();
        $learnerCount = (clone $learnerQuery)->count();
        $averageScore = (float) DB::table('assessment_attempts')
            ->join('users', 'users.id', '=', 'assessment_attempts.user_id')
            ->where('users.is_admin', false)
            ->whereNotNull('assessment_attempts.completed_at')
            ->avg('assessment_attempts.percentage');
        $ticketCounts = SupportTicket::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $categoryCounts = SupportTicket::query()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'category');
        $registrationTrend = User::query()
            ->where('is_admin', false)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');
        $registrationTrend = collect(range(6, 0))
            ->mapWithKeys(function (int $daysAgo) use ($registrationTrend) {
                $day = now()->subDays($daysAgo)->toDateString();

                return [$day => (int) ($registrationTrend[$day] ?? 0)];
            });

        $stats = [
            'total_users' => $learnerCount,
            'admins' => User::where('is_admin', true)->count(),
            'completed_assessments' => $completedAssessments,
            'open_tickets' => SupportTicket::whereIn('status', ['Open', 'In Progress'])->count(),
            'average_score' => round($averageScore ?: 0, 1),
            'completion_rate' => $learnerCount > 0 ? round(($completedAssessments / $learnerCount) * 100) : 0,
        ];
        $analytics = [
            'ticket_counts' => $ticketCounts,
            'category_counts' => $categoryCounts,
            'registration_trend' => $registrationTrend,
            'max_registration_count' => max(1, (int) $registrationTrend->max()),
        ];

        return view('admin.dashboard', compact('users', 'tickets', 'stats', 'analytics'));
    }

    public function showUser(Request $request, User $user)
    {
        $this->authorizeAdmin($request);
        abort_if($user->is_admin, 404);

        $user->load([
            'profile',
            'assessmentAttempt.answers.question',
            'supportTickets' => fn ($query) => $query->latest(),
        ]);

        $attempt = $user->assessmentAttempt;
        $answers = $attempt ? $attempt->answers()->with('question')->get() : collect();
        $wrongAnswers = $answers->filter(fn ($answer) => !$answer->is_correct && $answer->question)->values();
        $topicBreakdown = collect($attempt?->insights['topic_breakdown'] ?? []);
        $signals = [
            [
                'label' => 'Account deletion requested',
                'active' => $user->supportTickets->contains(fn ($ticket) => $ticket->category === 'Account deletion' && $ticket->status !== 'Resolved'),
                'detail' => 'User has an open account deletion request.',
            ],
            [
                'label' => 'Malpractice report',
                'active' => $user->supportTickets->contains(fn ($ticket) => $ticket->category === 'Malpractice report' && $ticket->status !== 'Resolved'),
                'detail' => 'A report indicates the admin should review behavior before action.',
            ],
            [
                'label' => 'No onboarding profile',
                'active' => $user->profile === null,
                'detail' => 'User registered but has not completed profile setup.',
            ],
            [
                'label' => 'Many open tickets',
                'active' => $user->supportTickets->whereIn('status', ['Open', 'In Progress'])->count() >= 3,
                'detail' => 'User has three or more unresolved platform reports.',
            ],
        ];

        return view('admin.user-show', compact(
            'user',
            'attempt',
            'answers',
            'wrongAnswers',
            'topicBreakdown',
            'signals'
        ));
    }

    public function deleteUser(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ($request->user()->is($user)) {
            return back()->with('admin_status', 'You cannot delete your own admin account.');
        }

        if ($user->is_admin) {
            return back()->with('admin_status', 'Admin accounts are protected from the learner management table.');
        }

        $user->delete();

        return back()->with('admin_status', 'User deleted successfully.');
    }

    public function updateTicket(Request $request, SupportTicket $ticket)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Open,In Progress,Resolved'],
            'admin_notes' => ['nullable', 'string', 'max:1200'],
        ]);

        $ticket->forceFill([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'resolved_at' => $validated['status'] === 'Resolved' ? now() : null,
        ])->save();

        return back()->with('admin_status', 'Ticket updated.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }
}
