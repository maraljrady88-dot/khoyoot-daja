@extends('layouts.app')

@section('title', 'فروع خيوط دعجاء | حيث تلتقي الأصالة بالفخامة')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 36px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                فروع خيوط دعجاء بالمملكة
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                تفضلي بزيارة فروعنا لتجربة أرقى خامات العبايات الخليجية على أرض الواقع
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="branches-grid">
                @forelse($branches as $branch)
                    <div class="branch-card">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <h2 class="branch-title" style="margin-bottom: 0;">
                                <i class="fa-solid fa-store" style="color: var(--brand-gold-dark);"></i>
                                {{ $branch->name }}
                            </h2>
                            <span style="background: #FAF8F5; border: 1px solid var(--border-gold); color: var(--brand-gold-dark); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                {{ $branch->city }}
                            </span>
                        </div>

                        <div class="branch-detail">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <strong>العنوان:</strong>
                                <div>{{ $branch->address }}</div>
                            </div>
                        </div>

                        @if($branch->working_hours)
                            <div class="branch-detail">
                                <i class="fa-regular fa-clock"></i>
                                <div>
                                    <strong>أوقات وساعات العمل:</strong>
                                    <div>{{ $branch->working_hours }}</div>
                                </div>
                            </div>
                        @endif

                        @if($branch->phone)
                            <div class="branch-detail">
                                <i class="fa-solid fa-phone"></i>
                                <div>
                                    <strong>رقم هاتف الفرع:</strong>
                                    <div><a href="tel:{{ $branch->phone }}" dir="ltr" style="color: var(--brand-primary); font-weight: 600;">{{ $branch->phone }}</a></div>
                                </div>
                            </div>
                        @endif

                        @if($branch->google_maps_url)
                            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-light);">
                                <a href="{{ $branch->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-gold btn-sm" style="width: 100%;">
                                    <i class="fa-solid fa-location-arrow"></i> فتح الموقع في خرائط Google Maps
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                        لا توجد فروع مسجلة حالياً.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
