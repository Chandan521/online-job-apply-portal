<h5 class="fw-bold mb-3 text-primary">Step 3: Media</h5>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Company Logo</label>
        <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*" onchange="previewImage(event, 'logoPreview')">
        @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="mt-2">
            <img id="logoPreview" style="max-height: 100px;" class="img-thumbnail d-none" />
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Cover Image</label>
        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*" onchange="previewImage(event, 'coverPreview')">
        @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="mt-2">
            <img id="coverPreview" style="max-height: 150px;" class="img-thumbnail d-none" />
        </div>
    </div>
</div>


