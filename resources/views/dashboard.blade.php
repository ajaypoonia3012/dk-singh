@extends('layouts.app')

@section('content')
<section class="min-h-screen theme-surface-muted theme-section theme-text-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ========================================== --}}
        {{-- HEADER SECTION                             --}}
        {{-- ========================================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between theme-content-gap mb-12">
            <div>
                <p class="text-xs uppercase tracking-widest theme-text-primary font-bold mb-2">
                    Member Dashboard
                </p>
                <h1 class="text-4xl font-black theme-text-secondary tracking-tight mb-2">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-lg theme-text-neutral max-w-2xl">
                    Manage your fitness journey, membership tier, daily workouts, and custom nutrition parameters.
                </p>
            </div>

            <div class="shrink-0">
                @if($membership)
                    <a href="{{ route('member.my-plan') }}" class="theme-surface-strong hover:theme-surface-strong theme-text-on-strong px-6 py-3.5 theme-radius font-bold tracking-wide text-sm transition duration-200 theme-shadow inline-block">
                        My Fitness Plan
                    </a>
                @else
                    <a href="/plans" class="theme-status-warning hover:theme-status-warning theme-text-secondary px-6 py-3.5 theme-radius font-bold tracking-wide text-sm transition duration-200 theme-shadow inline-block">
                        Upgrade Membership
                    </a>
                @endif
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- PRIMARY CORE STATS                         --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 theme-content-gap mb-8">

            {{-- ACTIVE PLAN CARD --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-4">
                        Active Membership
                    </p>
                    @if($membership)
                        <h2 class="text-3xl font-black theme-text-secondary tracking-tight">
                            {{ $membership->plan->name }}
                        </h2>
                        <div class="flex items-center gap-1.5 theme-text-success font-bold text-sm mt-2">
                            <span class="w-2 h-2 rounded-full theme-status-success0 animate-pulse"></span>
                            Status: Active
                        </div>
                        <div class="mt-4 pt-4 border-t theme-border space-y-1 text-sm theme-text-neutral">
                            <p>Expires: <span class="font-medium theme-text-neutral">{{ $membership->expires_at->format('d M Y') }}</span></p>
                            <p class="theme-text-primary font-semibold">{{ $daysRemaining }} Days Remaining</p>
                        </div>
                    @else
                        <h2 class="text-3xl font-black theme-text-neutral tracking-tight">
                            No Active Plan
                        </h2>
                        <p class="text-rose-500 font-bold text-sm mt-2">Inactive Access</p>
                    @endif
                </div>
                <div class="mt-6">
                    <a href="{{ $membership ? route('member.my-plan') : '/plans' }}" class="text-sm font-semibold theme-text-secondary hover:theme-text-primary underline underline-offset-4 transition">
                        {{ $membership ? 'View My Plan' : 'Choose a Plan' }} <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                    </a>
                </div>
            </div>

            {{-- WORKOUT COMPLIANCE --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">
                        Workout Compliance
                    </p>
                    <svg class="w-5 h-5 theme-text-info" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h2 class="text-5xl font-black theme-text-info tracking-tight mb-2">
                    {{ $workoutCompliance }}%
                </h2>
                <p class="text-sm theme-text-neutral font-medium">
                    {{ $completedWorkouts }} of {{ $totalWorkouts }} workouts log completed
                </p>
            </div>

            {{-- DIET COMPLIANCE --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">
                        Diet Compliance
                    </p>
                    <svg class="w-5 h-5 theme-text-success0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                </div>
                <h2 class="text-5xl font-black theme-text-success tracking-tight mb-2">
                    {{ $dietCompliance }}%
                </h2>
                <p class="text-sm theme-text-neutral font-medium">
                    Nutrition macro-adherence rating
                </p>
            </div>

            {{-- ACHIEVEMENTS SYSTEM --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-4">
                        Unlocked Trophies
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($badges as $badge)
                            <span class="inline-flex items-center px-3 py-1.5 theme-radius text-xs font-bold theme-surface-muted theme-text-warning border theme-border">
                                <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> {{ $badge }}
                            </span>
                        @empty
                            <p class="text-sm theme-text-neutral italic">No achievements unlocked yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- QUICK ACCESS INTERACTION GRID              --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 theme-content-gap mb-8">

            {{-- CURRENT WORKOUT --}}
            <div class="theme-card theme-radius p-6 border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-3">Current Workout</p>
                    @if($currentWorkout)
                        <h3 class="text-xl font-bold theme-text-secondary tracking-tight mb-2">{{ $currentWorkout->title }}</h3>
                        <span class="inline-block text-xs font-bold px-2.5 py-1 theme-radius {{ $currentWorkoutCompleted ? 'theme-status-success theme-text-success' : 'theme-surface-muted theme-text-warning' }}">
                            {{ $currentWorkoutCompleted ? 'Completed' : 'In Progress' }}
                        </span>
                    @else
                        <h3 class="text-lg font-medium theme-text-neutral">No Workout Assigned</h3>
                    @endif
                </div>
                <div class="mt-6 pt-4 border-t theme-border">
                    @if($currentWorkout)
                        <a href="/workout-plans/{{ $currentWorkout->id }}" class="text-sm font-bold theme-text-secondary hover:theme-text-primary transition">Open Schedule <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg></a>
                    @endif
                </div>
            </div>

            {{-- CURRENT DIET --}}
            <div class="theme-card theme-radius p-6 border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-3">Current Diet Plan</p>
                    @if($currentDiet)
                        <h3 class="text-xl font-bold theme-text-secondary tracking-tight mb-2">{{ $currentDiet->title }}</h3>
                        <span class="inline-block text-xs font-bold px-2.5 py-1 theme-radius {{ $currentDietCompleted ? 'theme-status-success theme-text-success' : 'theme-surface-muted theme-text-warning' }}">
                            {{ $currentDietCompleted ? 'Completed' : 'In Progress' }}
                        </span>
                    @else
                        <h3 class="text-lg font-medium theme-text-neutral">No Diet Assigned</h3>
                    @endif
                </div>
                <div class="mt-6 pt-4 border-t theme-border">
                    @if($currentDiet)
                        <a href="/diet-plans/{{ $currentDiet->id }}" class="text-sm font-bold theme-text-secondary hover:theme-text-primary transition">Open Menu <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg></a>
                    @endif
                </div>
            </div>

            {{-- WORKOUT PLANS ACCESS LINK --}}
            <a href="{{ auth()->user()->hasBasicAccess() ? '/workout-plans' : '/plans' }}" class="group theme-radius p-6 theme-shadow flex flex-col justify-between transition duration-200 transform hover:-translate-y-1 {{ auth()->user()->hasBasicAccess() ? 'theme-surface-strong theme-text-on-strong' : 'theme-surface-muted theme-text-neutral border theme-border' }}">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                        @if(!auth()->user()->hasBasicAccess())
                            <span class="text-xs font-bold tracking-widest uppercase px-2 py-1 theme-surface-muted theme-text-secondary rounded">Locked</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold tracking-tight {{ auth()->user()->hasBasicAccess() ? 'theme-text-on-strong' : 'theme-text-secondary' }}">
                        Premium Workouts
                    </h3>
                    <p class="text-xs mt-2 {{ auth()->user()->hasBasicAccess() ? 'theme-text-neutral' : 'theme-text-neutral' }} leading-relaxed">
                        Access high-tier hyper-optimized system workout models and splits.
                    </p>
                </div>
                <span class="text-sm font-bold mt-6 group-hover:underline underline-offset-4">Explore Routines <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg></span>
            </a>

            {{-- DIET PLANS ACCESS LINK --}}
            <a href="{{ auth()->user()->hasProAccess() ? '/diet-plans' : '/plans' }}" class="group theme-radius p-6 theme-shadow flex flex-col justify-between transition duration-200 transform hover:-translate-y-1 {{ auth()->user()->hasProAccess() ? 'theme-status-warning theme-text-secondary' : 'theme-surface-muted theme-text-neutral border theme-border' }}">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                        @if(!auth()->user()->hasProAccess())
                            <span class="text-xs font-bold tracking-widest uppercase px-2 py-1 theme-surface-muted theme-text-secondary rounded">Pro Lock</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold tracking-tight theme-text-secondary">
                        Premium Diet Plans
                    </h3>
                    <p class="text-xs mt-2 theme-text-secondary leading-relaxed opacity-80">
                        Access target-specific custom nutrition pathways and templates.
                    </p>
                </div>
                <span class="text-sm font-bold mt-6 group-hover:underline underline-offset-4">Explore Nutrition <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg></span>
            </a>

        </div>

        {{-- ========================================== --}}
        {{-- SECONDARY DETAILED TRACKING SECTIONS      --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 theme-content-gap mb-8">

            {{-- NEXT CHECK-IN --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-4">Next Coach Check-In</p>
                    @if($latestCheckIn)
                        <div class="space-y-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase theme-text-neutral tracking-wider">Last Sync</span>
                                <p class="font-bold theme-text-secondary">{{ $latestCheckIn->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="pt-2 border-t theme-border">
                                <span class="text-[10px] font-bold uppercase theme-text-primary tracking-wider">Deadline Due</span>
                                <p class="font-black theme-text-secondary text-lg">{{ $nextCheckInDate->format('d M Y') }}</p>
                                <p class="text-xs font-semibold theme-text-neutral mt-0.5">({{ $daysUntilCheckIn }} days remaining)</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-8 pt-4 border-t theme-border">
                    
                </div>
            </div>

            {{-- ACTION PLAN --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">Weekly Objectives</p>
                        <span class="text-xl"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                    </div>
                    <h2 class="text-5xl font-black theme-text-secondary tracking-tight mt-2">
                        {{ $completedActionPlans }}<span class="theme-text-neutral font-light text-3xl">/{{ $totalActionPlans }}</span>
                    </h2>
                    <p class="text-xs font-semibold theme-text-neutral mt-1">Milestones Completed This Week</p>
                </div>
                <div class="mt-8 pt-4 border-t theme-border">
                    <a href="{{ route('member.action-plan') }}" class="inline-flex items-center text-sm font-bold theme-text-secondary hover:theme-text-primary transition">
                        View Action Items <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                    </a>
                </div>
            </div>

            {{-- NOTIFICATIONS --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">Alert Center</p>
                        @if($unreadNotifications > 0)
                            <span class="text-[11px] font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full border border-rose-100">
                                {{ $unreadNotifications }} unread
                            </span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold theme-text-secondary tracking-tight mb-2"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> System Notifications</h3>
                    <p class="text-xs theme-text-neutral leading-relaxed">
                        Stay linked dynamically to real-time adjustments from your personal trainers and coaches.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t theme-border">
                    <a href="{{ route('member.notifications') }}" class="inline-flex items-center text-sm font-bold theme-text-secondary hover:theme-text-primary transition">
                        View Updates Grid <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- UTILITY TOOLS SUB-LINKS GRID               --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 theme-content-gap mb-8">
            {{-- PROGRESS TRACKER LINK --}}
            <a href="{{ route('member.progress') }}" class="group theme-card theme-radius p-6 border theme-border theme-shadow flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl theme-surface-muted p-2 theme-radius"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                <div>
                    <h4 class="font-bold theme-text-secondary group-hover:theme-text-primary transition">Progress Tracker</h4>
                    <p class="text-xs theme-text-neutral">Log custom dimensions</p>
                </div>
            </a>

            {{-- TRANSFORMATIONS LINK --}}
            <a href="{{ route('member.transformations') }}" class="group theme-card theme-radius p-6 border theme-border theme-shadow flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl theme-surface-muted p-2 theme-radius"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                <div>
                    <h4 class="font-bold theme-text-secondary group-hover:theme-text-primary transition">Photo Log Timeline</h4>
                    <p class="text-xs theme-text-neutral">Visual physical timeline</p>
                </div>
            </a>

            {{-- PROGRESS REPORT DOWNLOAD --}}
            <a href="{{ route('member.progress-report') }}" class="group theme-card theme-radius p-6 border theme-border theme-shadow flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl theme-surface-muted p-2 theme-radius"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                <div>
                    <h4 class="font-bold theme-text-secondary group-hover:theme-text-primary transition">Download PDF Report</h4>
                    <p class="text-xs theme-text-neutral">Export transformation stats</p>
                </div>
            </a>

            {{-- PROFILE MANAGEMENT --}}
            <a href="{{ route('member.profile') }}" class="group theme-card theme-radius p-6 border theme-border theme-shadow flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl theme-surface-muted p-2 theme-radius"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                <div>
                    <h4 class="font-bold theme-text-secondary group-hover:theme-text-primary transition">Account Profile</h4>
                    <p class="text-xs theme-text-neutral">Manage credentials & keys</p>
                </div>
            </a>
        </div>

        {{-- ========================================== --}}
        {{-- COACH NOTE & TRANSFORMATION LOG INTERACTIVE--}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 theme-content-gap mb-8">
            
            {{-- COACH FEEDBACK OVERVIEW --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">Latest Coach Note</p>
                        <span class="text-sm theme-text-neutral"><svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg></span>
                    </div>
                    @if($latestCoachNote)
                        <blockquote class="theme-text-neutral italic border-l-2 theme-border pl-4 my-4 text-sm leading-relaxed">
                            "{{ Str::limit($latestCoachNote->note, 140) }}"
                        </blockquote>
                        <span class="text-xs theme-text-neutral block mt-2 font-medium">{{ $latestCoachNote->created_at->format('d M Y') }}</span>
                    @else
                        <p class="theme-text-neutral text-sm italic my-6">Your head coach hasn't logged notes yet this cycle.</p>
                    @endif
                </div>
                <div class="pt-4 border-t theme-border mt-6">
                    <a href="{{ route('member.coach-notes') }}" class="text-xs font-bold theme-text-secondary hover:theme-text-primary transition uppercase tracking-wider">
                        Read Historical Vault <svg class="inline-block w-4 h-4 ms-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                    </a>
                </div>
            </div>

            {{-- TRANSFORMATION SUMMARY DATA BLOCK --}}
            <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow lg:col-span-2 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider mb-6">Transformation Metrics Summary</p>
                    @if($firstCheckIn && $latestCheckIn)
                        <div class="grid grid-cols-1 sm:grid-cols-3 theme-content-gap border-b theme-border pb-6">
                            <div>
                                <p class="text-[10px] uppercase font-bold theme-text-neutral tracking-wider mb-1">Starting Baseline</p>
                                <h3 class="text-2xl font-black theme-text-secondary">{{ $firstCheckIn->weight }} kg</h3>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold theme-text-neutral tracking-wider mb-1">Current Metric</p>
                                <h3 class="text-2xl font-black theme-text-success">{{ $latestCheckIn->weight }} kg</h3>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold theme-text-neutral tracking-wider mb-1">Net Differential</p>
                                @if($weightChange > 0)
                                    <h3 class="text-2xl font-black theme-text-success">{{ $weightChange }} kg Delta Lost</h3>
                                @elseif($weightChange < 0)
                                    <h3 class="text-2xl font-black text-rose-500">{{ abs($weightChange) }} kg Delta Gained</h3>
                                @else
                                    <h3 class="text-2xl font-black theme-text-neutral">Equilibrium</h3>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 text-xs font-medium theme-text-neutral">
                            <p>Active Program Run: <span class="theme-text-secondary font-bold">{{ $journeyDays }} Days</span></p>
                            <p>Data Verification Check-Ins: <span class="theme-text-secondary font-bold">{{ $totalCheckIns }} Logged</span></p>
                        </div>
                    @else
                        <p class="theme-text-neutral text-sm italic my-6">Submit initial check-in forms to formulate progress graphs.</p>
                    @endif
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('member.progress-report') }}" class="px-5 py-2.5 theme-surface-strong hover:theme-surface-strong theme-text-on-strong font-bold theme-radius transition text-xs theme-shadow">
                        Download Comprehensive Metrics Report
                    </a>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- VISUAL CHART RENDERING GRAPH               --}}
        {{-- ========================================== --}}
        <div class="theme-card theme-radius theme-card-padding border theme-border theme-shadow">
            <div class="flex items-center justify-between mb-6">
                <p class="text-xs font-bold theme-text-neutral uppercase tracking-wider">Weight Progress Metrics (Chronological Timeline)</p>
                <span class="w-2.5 h-2.5 rounded-full theme-status-warning"></span>
            </div>
            <div class="h-[320px]">
                <canvas id="weightChart"></canvas>
            </div>
        </div>

    </div>
</section>

{{-- Goal Progress --}}

<div class="theme-card theme-radius theme-card-padding theme-shadow">

    <div class="flex justify-between items-center mb-6">

        <h3 class="text-xl font-bold theme-text-secondary">

            <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> Goal Progress

        </h3>

        <span class="font-bold theme-text-primary">

            {{ $goalProgress }}%

        </span>

    </div>

    <div class="w-full theme-surface-muted rounded-full h-4 mb-4">

        <div
            class="theme-status-warning h-4 rounded-full"
            style="width: {{ $goalProgress }}%">
        </div>

    </div>

    <p class="text-sm theme-text-neutral">

        Goal:
        {{ auth()->user()->goal ?? 'Not Set' }}

    </p>

</div>

{{-- ========================================== --}}
{{-- VISUAL HIDDEN DOM METADATA PRE-CACHE BLOCK --}}
{{-- ========================================== --}}
<div class="hidden" aria-hidden="true">
    <span data-meta="orders">{{ $orders }}</span>
    <span data-meta="completed-workouts">{{ $completedWorkouts }}</span>
    <span data-meta="completed-diets">{{ $completedDiets }}</span>
    <span data-meta="streak">{{ $checkInStreak }}</span>
</div>

{{-- SCRIPTS INJECTION --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartCanvas = document.getElementById('weightChart');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: @json($weightLabels),
                    datasets: [{
                        label: 'Weight Data Matrix (kg)',
                        data: @json($weightData),
                        borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim(),
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim(),
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: false,
                            grid: { color: getComputedStyle(document.documentElement).getPropertyValue('--input-border').trim() }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
