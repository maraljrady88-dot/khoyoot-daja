@extends('layouts.app')

@section('title', 'قائمة المفضلة | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                قائمة المفضلة
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                العبايات التي نالت إعجابكِ لحفظها والرجوع إليها في أي وقت
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 70px 20px; text-align: center; max-width: 600px; margin: 0 auto;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #E11D48;">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        قائمة المفضلة فارغة حالياً
                    </h2>
                    <p style="font-size: 0.92rem; color: var(--text-muted); margin-bottom: 24px; line-height: 1.7;">
                        اضغطي على رمز القلب في أي عباية تعجبكِ أثناء التصفح لحفظها في هذه القائمة.
                    </p>
                    <a href="{{ route('shop.index') }}" class="btn btn-gold btn-lg">
                        استكشفي تشكيلة العبايات
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection
