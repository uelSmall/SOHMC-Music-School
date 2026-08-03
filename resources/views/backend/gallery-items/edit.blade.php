@extends('backend.layouts.app')

@section('title', 'Edit Gallery Image')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Edit Gallery Image</h4>
            <form method="POST" action="{{ route('backend.gallery-items.update', $galleryItem) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('backend.gallery-items.form', ['galleryItem' => $galleryItem])
            </form>
        </div>
    </div>
@endsection
