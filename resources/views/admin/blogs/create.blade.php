@extends('layouts.admin')
@section('title', isset($blog) ? 'Edit Blog' : 'Add Blog')
@section('page-title', isset($blog) ? 'Edit Blog' : 'Add Blog')

@push('styles')
<style>
    .tox-tinymce { border-radius: 8px !important; border: 1.5px solid #e2e8f0 !important; }
    .tox-tinymce:focus-within { border-color: #1565C0 !important; }

    .img-preview-wrap { margin-top: 0.75rem; }
    .img-preview-wrap img { max-height: 160px; border-radius: 8px; border: 1px solid #e2e8f0; }

    .tag-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 0.5rem; }
    .tag-pill {
        background: #e3f2fd;
        color: #1565c0;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .tag-pill button {
        background: none;
        border: none;
        color: #1565c0;
        cursor: pointer;
        font-size: 0.7rem;
        padding: 0;
        line-height: 1;
    }
</style>
@endpush

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}" enctype="multipart/form-data" id="blogForm">
    @csrf
    @if(isset($blog)) @method('PUT') @endif

    <div class="grid-2">

        <!-- LEFT: Main Fields -->
        <div style="display: flex; flex-direction: column; gap: 0; grid-column: 1 / -1;">

            <div class="card" style="margin-bottom: 1.25rem;">
                <div class="card-header"><h2>Blog Details</h2></div>
                <div class="card-body">

                    <div class="grid-2" style="margin-bottom: 1.25rem;">
                        <!-- Title -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Title <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   placeholder="Write blog title..." value="{{ old('title', $blog->title ?? '') }}" required>
                            @error('title')<p class="invalid-feedback">{{ $message }}</p>@enderror
                        </div>

                        <!-- Category -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Category <span class="required">*</span></label>
                            <select name="category_id" class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="invalid-feedback">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="form-group">
                        <label class="form-label">Short Description <span class="required">*</span></label>
                        <textarea name="short_description" class="form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }}"
                                  rows="3" placeholder="Brief description (shown in listing, max 500 chars)..."
                                  maxlength="500">{{ old('short_description', $blog->short_description ?? '') }}</textarea>
                        @error('short_description')<p class="invalid-feedback">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tags -->
                    <div class="form-group">
                        <label class="form-label">Tags <span style="font-weight:400; color:#94a3b8;">(comma separated)</span></label>
                        <input type="text" name="tags" id="tagsInput" class="form-control"
                               placeholder="e.g. sarkari job, admit card, result"
                               value="{{ old('tags', isset($blog) && $blog->tags ? implode(', ', $blog->tags) : '') }}">
                        <p class="tag-hint"><i class="fas fa-info-circle"></i> Separate tags with commas</p>
                        <div class="tag-wrap" id="tagPreview"></div>
                    </div>

                    <!-- Published Date -->
                    <div class="form-group" style="max-width: 240px;">
                        <label class="form-label">Published Date</label>
                        <input type="date" name="published_at" class="form-control"
                               value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d') : date('Y-m-d')) }}">
                    </div>

                </div>
            </div>

            <!-- Image Upload -->
            <div class="card" style="margin-bottom: 1.25rem;">
                <div class="card-header"><h2><i class="fas fa-image"></i> Blog Image</h2></div>
                <div class="card-body">
                    <label class="upload-area" for="imageUpload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p style="font-weight: 500; margin-bottom: 0.25rem;">Choose a file or drag & drop</p>
                        <p style="font-size: 0.78rem; color: #94a3b8;">JPG, PNG, WebP – Max 2MB</p>
                    </label>
                    <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;">
                    <div class="img-preview-wrap" id="imgPreviewWrap" style="{{ isset($blog) && $blog->image ? '' : 'display:none' }}">
                        <img id="imgPreview" src="{{ isset($blog) && $blog->image ? asset('storage/'.$blog->image) : '' }}" alt="Preview">
                    </div>
                </div>
            </div>

            <!-- Rich Text Content Editor -->
            <div class="card" style="margin-bottom: 1.25rem;">
                <div class="card-header"><h2><i class="fas fa-align-left"></i> Content <span class="required">*</span></h2></div>
                <div class="card-body">
                    @error('content')
                        <p class="invalid-feedback" style="display:block; margin-bottom: 0.5rem;">{{ $message }}</p>
                    @enderror
                    <textarea name="content" id="contentEditor">{{ old('content', $blog->content ?? '') }}</textarea>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    {{ isset($blog) ? 'Update Blog' : 'Publish Blog' }}
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#contentEditor',
    height: 480,
    menubar: 'file edit view insert format tools table help',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount',
        'emoticons', 'codesample'
    ],
    toolbar:
        'undo redo | blocks | ' +
        'bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | table | link image | code fullscreen | help',
    toolbar_mode: 'sliding',
    content_style: 'body { font-family: Inter, sans-serif; font-size: 15px; line-height: 1.75; color: #334155; }',
    skin: 'oxide',
    table_default_attributes: {
        border: '1'
    },
    table_default_styles: {
        'border-collapse': 'collapse',
        'width': '100%'
    },
    setup: function(editor) {
        editor.on('change', function() {
            editor.save();
        });
    }
});

// Image preview
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('imgPreview').src = ev.target.result;
        document.getElementById('imgPreviewWrap').style.display = '';
    };
    reader.readAsDataURL(file);
});

// Tag preview
const tagsInput = document.getElementById('tagsInput');
const tagPreview = document.getElementById('tagPreview');

function renderTags() {
    const val = tagsInput.value;
    const tags = val.split(',').map(t => t.trim()).filter(Boolean);
    tagPreview.innerHTML = tags.map(t =>
        `<span class="tag-pill"><i class="fas fa-tag"></i>${t}</span>`
    ).join('');
}

tagsInput.addEventListener('input', renderTags);
renderTags();

// Submit: make sure TinyMCE content is saved
document.getElementById('blogForm').addEventListener('submit', function() {
    tinymce.triggerSave();
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
});
</script>
@endpush
