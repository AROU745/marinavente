@extends('layouts.app')

@section('content')
<section class="svc-page" style="--svc: {{ $service['color'] }}; --svc-soft: {{ $service['colorSoft'] }};">
    <div class="container svc-hero">
        <div class="svc-hero-text">
            <a href="{{ url('/#services') }}" class="svc-back"><i class="fas fa-arrow-left"></i> Nos services</a>
            <h1>{{ $service['title'] }}</h1>
            <p>{{ $service['intro'] }}</p>
            <a href="{{ url($service['ctaUrl'] ?? '/contact') }}" class="btn btn-primary svc-cta">{{ $service['cta'] }}</a>
        </div>
        <div class="svc-hero-media">
            <img src="{{ asset('images/services/' . $service['image']) }}" alt="{{ $service['title'] }}">
        </div>
    </div>

    <div class="container svc-domains">
        <h2>{{ $service['domainsTitle'] }}</h2>
        <div class="svc-cards">
            @foreach($service['domains'] as $domain)
                <article class="svc-card">
                    <div class="svc-card-icon"><i class="{{ $domain['icon'] }}"></i></div>
                    <h3>{{ $domain['title'] }}</h3>
                    @if(!empty($domain['text']))
                        <p>{{ $domain['text'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>

    <div class="svc-banner">
        <div class="container">
            <h2>{{ $service['bannerTitle'] }}</h2>
            <div class="svc-banner-items">
                @foreach($service['bannerItems'] as $item)
                    <div class="svc-banner-item">
                        <i class="{{ $item['icon'] }}"></i>
                        @if(!empty($item['value']))
                            <strong>{{ $item['value'] }}</strong>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
