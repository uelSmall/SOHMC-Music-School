@php
    $isEdit = isset($galleryItem);
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label for="title" class="form-label">Title</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title', $galleryItem->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
            required
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="sort_order" class="form-label">Sort Order</label>
        <input
            id="sort_order"
            type="number"
            min="0"
            name="sort_order"
            value="{{ old('sort_order', $galleryItem->sort_order ?? 0) }}"
            class="form-control @error('sort_order') is-invalid @enderror"
        >
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="caption" class="form-label">Caption</label>
        <textarea
            id="caption"
            name="caption"
            rows="3"
            class="form-control @error('caption') is-invalid @enderror"
            placeholder="Optional caption shown on the gallery card"
        >{{ old('caption', $galleryItem->caption ?? '') }}</textarea>
        @error('caption')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="1" @selected((string) old('status', $galleryItem->status ?? 1) === '1')>Active</option>
            <option value="0" @selected((string) old('status', $galleryItem->status ?? 1) === '0')>Inactive</option>
            <option value="2" @selected((string) old('status', $galleryItem->status ?? 1) === '2')>Pending</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="image" class="form-label">Image {{ $isEdit ? '(optional to replace)' : '(required)' }}</label>
        <input
            id="image"
            type="file"
            name="image"
            accept="image/*"
            class="form-control @error('image') is-invalid @enderror"
            {{ $isEdit ? '' : 'required' }}
        >
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($isEdit)
        <div class="col-md-12">
            <label class="form-label d-block">Current Image</label>
            @php
                $currentImage = $galleryItem->getFirstMediaUrl('gallery', 'thumb') ?: $galleryItem->getFirstMediaUrl('gallery');
            @endphp
            @if ($currentImage)
                <img src="{{ $currentImage }}" alt="{{ $galleryItem->title }}" class="img-thumbnail" style="max-height: 180px;">
            @else
                <p class="text-muted mb-0">No image attached.</p>
            @endif
        </div>
    @endif

    <div class="col-md-12 d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Image' : 'Create Image' }}</button>
        <a href="{{ route('backend.gallery-items.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</div>
