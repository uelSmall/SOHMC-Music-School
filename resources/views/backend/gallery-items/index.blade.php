@extends('backend.layouts.app')

@section('title', 'Gallery Manager')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Gallery Manager</h4>
                    <small class="text-muted">Upload images, set display status, and reorder gallery cards.</small>
                </div>
                <a href="{{ route('backend.gallery-items.create') }}" class="btn btn-primary">Add Image</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Preview</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th style="width: 220px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($galleryItems as $galleryItem)
                            @php
                                $thumb = $galleryItem->getFirstMediaUrl('gallery', 'thumb') ?: $galleryItem->getFirstMediaUrl('gallery');
                            @endphp
                            <tr>
                                <td>
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $galleryItem->title }}" class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                    @else
                                        <span class="badge text-bg-secondary">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $galleryItem->title }}</div>
                                    @if ($galleryItem->caption)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($galleryItem->caption, 80) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ((int) $galleryItem->status === 1)
                                        <span class="badge text-bg-success">Active</span>
                                    @elseif ((int) $galleryItem->status === 0)
                                        <span class="badge text-bg-danger">Inactive</span>
                                    @else
                                        <span class="badge text-bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $galleryItem->sort_order }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Gallery item actions">
                                        <form method="POST" action="{{ route('backend.gallery-items.move', [$galleryItem, 'up']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-outline-secondary" title="Move Up">↑</button>
                                        </form>
                                        <form method="POST" action="{{ route('backend.gallery-items.move', [$galleryItem, 'down']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-outline-secondary" title="Move Down">↓</button>
                                        </form>
                                        <a href="{{ route('backend.gallery-items.edit', $galleryItem) }}" class="btn btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('backend.gallery-items.destroy', $galleryItem) }}" onsubmit="return confirm('Delete this gallery image?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No gallery images yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $galleryItems->links() }}
            </div>
        </div>
    </div>
@endsection
