@extends('backend.layouts.app')

@section('title', 'Add Gallery Image')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Add Gallery Image</h4>
            <form method="POST" action="{{ route('backend.gallery-items.store') }}" enctype="multipart/form-data">
                @csrf
                @include('backend.gallery-items.form')
            </form>
        </div>
    </div>
@endsection
