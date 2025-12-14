@props(['model' => null])

@php
    $seo = $model?->seo ?? null;
@endphp

<div class="card">
    <div class="card-header bg-light-primary">
        <h5 class="card-title text-primary mb-0">
            <i class="bi bi-search me-2"></i>SEO Settings
        </h5>
    </div>
    <div class="card-body">
        <div class="row">

            {{-- Meta Title --}}
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" id="meta_title"
                           name="meta_title"
                           class="form-control @error('meta_title') is-invalid @enderror"
                           value="{{ old('meta_title', $seo->meta_title ?? '') }}"
                           placeholder="Custom SEO title for this page">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Meta Description --}}
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea id="meta_description"
                              name="meta_description"
                              rows="2"
                              class="form-control @error('meta_description') is-invalid @enderror"
                              placeholder="150–160 characters summary">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Keywords --}}
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="meta_keywords" class="form-label">Meta Keywords (optional)</label>
                    <input type="text" id="meta_keywords"
                           name="meta_keywords"
                           class="form-control @error('meta_keywords') is-invalid @enderror"
                           value="{{ old('meta_keywords', $seo->meta_keywords ?? '') }}"
                           placeholder="mobile review, specs, price in Pakistan">
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Used mainly by old search engines.</small>
                </div>
            </div>

            {{-- Canonical URL --}}
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="canonical_url" class="form-label">Canonical URL</label>
                    <input type="text" id="canonical_url"
                           name="canonical_url"
                           class="form-control @error('canonical_url') is-invalid @enderror"
                           value="{{ old('canonical_url', $seo->canonical_url ?? '') }}"
                           placeholder="{{ url()->current() }}">
                    @error('canonical_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave empty to auto-generate from current URL.</small>
                </div>
            </div>

        </div>

        <hr>

        {{-- Social / OG --}}
        <div class="row">
            <div class="col-12">
                <h6 class="text-muted mb-2">Open Graph (Facebook, LinkedIn, WhatsApp)</h6>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="og_title" class="form-label">OG Title</label>
                    <input type="text" id="og_title"
                           name="og_title"
                           class="form-control @error('og_title') is-invalid @enderror"
                           value="{{ old('og_title', $seo->og_title ?? '') }}">
                    @error('og_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="og_description" class="form-label">OG Description</label>
                    <textarea id="og_description"
                              name="og_description"
                              rows="2"
                              class="form-control @error('og_description') is-invalid @enderror">{{ old('og_description', $seo->og_description ?? '') }}</textarea>
                    @error('og_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="og_image" class="form-label">OG Image URL</label>
                    <input type="text" id="og_image"
                           name="og_image"
                           class="form-control @error('og_image') is-invalid @enderror"
                           value="{{ old('og_image', $seo->og_image ?? '') }}"
                           placeholder="https://example.com/images/og-image.jpg">
                    @error('og_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>

        {{-- Twitter Card --}}
        <div class="row">
            <div class="col-12">
                <h6 class="text-muted mb-2">Twitter Card</h6>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="twitter_title" class="form-label">Twitter Title</label>
                    <input type="text" id="twitter_title"
                           name="twitter_title"
                           class="form-control @error('twitter_title') is-invalid @enderror"
                           value="{{ old('twitter_title', $seo->twitter_title ?? '') }}">
                    @error('twitter_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="twitter_description" class="form-label">Twitter Description</label>
                    <textarea id="twitter_description"
                              name="twitter_description"
                              rows="2"
                              class="form-control @error('twitter_description') is-invalid @enderror">{{ old('twitter_description', $seo->twitter_description ?? '') }}</textarea>
                    @error('twitter_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="twitter_image" class="form-label">Twitter Image URL</label>
                    <input type="text" id="twitter_image"
                           name="twitter_image"
                           class="form-control @error('twitter_image') is-invalid @enderror"
                           value="{{ old('twitter_image', $seo->twitter_image ?? '') }}"
                           placeholder="https://example.com/images/twitter-image.jpg">
                    @error('twitter_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>

        {{-- JSON-LD --}}
        <div class="row">
            <div class="col-12">
                <h6 class="text-muted mb-2">Custom JSON-LD (Optional)</h6>
                <div class="form-group">
                    <label for="json_ld" class="form-label">JSON-LD Schema</label>
                    <textarea id="json_ld"
                              name="json_ld"
                              rows="6"
                              class="form-control @error('json_ld') is-invalid @enderror"
                              placeholder='{"context": "https://schema.org", ...}'>{{ old('json_ld', $seo->json_ld ?? '') }}</textarea>
                    
                    <small class="text-muted">
                        Optional per-page schema. If empty, global templates will be used.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
