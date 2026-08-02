@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="mb-1" style="color:#A6128D; font-weight:700;">@lang("Admin Dashboard")</h2>
                    <p class="mb-0 text-muted">@lang("Manage users, content, and system controls from a single hub.")</p>
                </div>
                <button class="btn btn-outline-dark" type="button">
                    <i class="fa-solid fa-bullhorn me-1"></i>
                    @lang("Announcements")
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100 border" style="border-color:#D991CD !important;">
                        <div class="card-body">
                            <p class="text-muted mb-2">@lang("Access")</p>
                            <h5 class="mb-0">@lang("Users & Roles")</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border" style="border-color:#D991CD !important;">
                        <div class="card-body">
                            <p class="text-muted mb-2">@lang("Content")</p>
                            <h5 class="mb-0">@lang("Lessons & Assignments")</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border" style="border-color:#D991CD !important;">
                        <div class="card-body">
                            <p class="text-muted mb-2">@lang("System")</p>
                            <h5 class="mb-0">@lang("Settings & Backups")</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
