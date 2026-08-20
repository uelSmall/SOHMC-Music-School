@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
    {{-- Welcome Banner --}}
    <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #A6128D 0%, #6B025E 100%); color: #fff;">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="mb-1 fw-bold" style="font-family: 'Sora', sans-serif; color: #fff;">
                        @lang("Welcome back, :name", ["name" => Auth::user()->name])
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.75);">
                        @lang("Here's what's happening with your school today.")
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('frontend.index') }}" target="_blank" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); border-radius: 999px;">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                        @lang("View Site")
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    @php
        $totalUsers = \App\Models\User::count();
        $totalRoles = \Spatie\Permission\Models\Role::count();
        $totalLessons = class_exists('App\Models\Lesson') ? \App\Models\Lesson::count() : 0;
        $totalGallery = class_exists('App\Models\GalleryItem') ? \App\Models\GalleryItem::count() : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(166,18,141,0.1); color: #A6128D;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="stat-label">@lang("Total Users")</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(46,184,92,0.1); color: #2eb85c;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalRoles }}</div>
                        <div class="stat-label">@lang("Roles")</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(51,154,240,0.1); color: #339af0;">
                        <i class="fa-solid fa-music"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalLessons }}</div>
                        <div class="stat-label">@lang("Lessons")</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(247,201,72,0.1); color: #d4a017;">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalGallery }}</div>
                        <div class="stat-label">@lang("Gallery Items")</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h6 class="card-title fw-bold mb-3" style="color: #A6128D;">
                        <i class="fa-solid fa-bolt me-2"></i>@lang("Quick Actions")
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('backend.users.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 999px;">
                            <i class="fa-solid fa-users me-1"></i>@lang("Manage Users")
                        </a>
                        <a href="{{ route('backend.roles.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 999px;">
                            <i class="fa-solid fa-shield-halved me-1"></i>@lang("Roles & Permissions")
                        </a>
                        <a href="{{ route('backend.gallery-items.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 999px;">
                            <i class="fa-solid fa-images me-1"></i>@lang("Gallery")
                        </a>
                        <a href="{{ route('backend.settings.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 999px;">
                            <i class="fa-solid fa-gear me-1"></i>@lang("Settings")
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h6 class="card-title fw-bold mb-3" style="color: #A6128D;">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>@lang("Recent Activity")
                    </h6>
                    @php
                        $recentActivity = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(5)->get();
                    @endphp
                    @if($recentActivity->isEmpty())
                        <p class="text-muted mb-0">@lang("No recent activity recorded.")</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach($recentActivity as $activity)
                                <li class="py-2 @if(!$loop->last) border-bottom @endif" style="border-color: rgba(166,18,141,0.08) !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-circle" style="font-size: 0.4rem; color: #D991CD;"></i>
                                        <span class="text-muted" style="font-size: 0.85rem;">
                                            {{ $activity->description }}
                                            @if(isset($activity->causer->name))
                                                <strong style="color: #A6128D;">{{ $activity->causer->name }}</strong>
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
