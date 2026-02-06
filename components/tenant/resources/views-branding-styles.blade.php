@php
    use App\Services\TenantBrandingService;

    $brandingService = app(TenantBrandingService::class);
    $tenant = $tenant ?? auth()->user()->tenant ?? null;
    $brandingCss = $tenant ? $brandingService->getBrandingCss($tenant) : '';
    $branding = $tenant ? $brandingService->getBrandingArray($tenant) : [];

    $fontFamilyLabel = $branding['font_body'] ?? 'Figtree';
    $fontFamilyLabel = trim(explode(',', $fontFamilyLabel)[0]);
    $primaryColor = $branding['primary_color'] ?? null;
    $secondaryColor = $branding['secondary_color'] ?? null;
    $faviconPath = $branding['favicon_url'] ?? null;
    $sidebarPosition = $branding['sidebar_position'] ?? 'left';
    $customBrandName = $branding['custom_brand_name'] ?? null;
@endphp

{!! $brandingCss !!}
