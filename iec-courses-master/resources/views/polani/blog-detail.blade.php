@extends('polani.layout')

@section('title', $blog->title . ' — Polani Fragrance')
@section('meta_description', Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 150))

@section('content')
<article style="background: #0a0a0a; color: #f8e7d0; min-height: 100vh; padding: 60px 0 100px; font-family: 'Montserrat', sans-serif;">
  <div class="container" style="max-width: 860px;">
    
    {{-- Back button --}}
    <div style="margin-bottom: 30px;">
      <a href="{{ route('home') }}#blogs-section" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #d4a658; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; transition: color 0.2s;">
        &larr; Back to Home
      </a>
    </div>

    {{-- Blog Header --}}
    <header style="margin-bottom: 40px; text-align: center;">
      <div style="color: #d4a658; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 12px;">
        POLANI JOURNAL
      </div>
      <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.2rem, 5vw, 3.5rem); line-height: 1.25; color: #f8e7d0; margin: 0 0 20px; font-weight: 700;">
        {{ $blog->title }}
      </h1>
      <div style="display: flex; align-items: center; justify-content: center; gap: 14px; font-size: 0.88rem; color: rgba(248, 231, 208, 0.55);">
        <span>By <strong>Polani Editor</strong></span>
        <span aria-hidden="true">&bull;</span>
        <span>{{ $blog->created_at->format('F d, Y') }}</span>
      </div>
      <div style="width: 60px; height: 1px; background: #d4a658; margin: 30px auto 0;"></div>
    </header>

    {{-- Blog Cover Image --}}
    @if($blog->image_path)
      @php
        $blogImg = asset($blog->image_path);
      @endphp
      <div style="position: relative; margin-bottom: 50px; height: 450px; border-radius: 20px; overflow: hidden; border: 1px solid rgba(212, 166, 88, 0.2); box-shadow: 0 20px 40px rgba(0,0,0,0.35); background: #000;">
        <!-- Blurred Background -->
        <div style="position: absolute; inset: 0; background-image: url('{{ $blogImg }}'); background-size: cover; background-position: center; filter: blur(20px) brightness(0.6); transform: scale(1.15); z-index: 1;"></div>
        <!-- Main Image -->
        <img src="{{ $blogImg }}" alt="{{ $blog->title }}" style="position: relative; width: 100%; height: 100%; object-fit: contain; z-index: 2;" />
      </div>
    @endif

    {{-- Blog Content --}}
    <div class="blog-body" style="font-size: 1.1rem; line-height: 1.85; color: rgba(248, 231, 208, 0.85); font-family: inherit;">
      {!! htmlspecialchars_decode($blog->content) !!}
    </div>

    {{-- Blog Footer --}}
    <footer style="margin-top: 60px; padding-top: 30px; border-top: 1px solid rgba(212, 166, 88, 0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <img src="{{ asset('polani/assets/logos/logo-white-trans.png?v=4') }}" alt="Polani Fragrance Logo" style="width: 44px; height: auto; object-fit: contain;" />
        <div>
          <div style="font-weight: 700; font-size: 0.9rem; color: #f8e7d0;">Polani Fragrance</div>
          <div style="font-size: 0.78rem; color: rgba(248, 231, 208, 0.5);">Pakistan's Signature Extrait de Parfum</div>
        </div>
      </div>
      <div>
        <a href="{{ route('home') }}" class="btn btn--primary" style="padding: 10px 24px; font-size: 0.85rem; border-radius: 999px;">
          Shop Collection
        </a>
      </div>
    </footer>

  </div>
</article>

<style>
  /* Content styling for rich text rendering */
  .blog-body p {
    margin-bottom: 24px;
  }
  .blog-body p:last-child {
    margin-bottom: 0;
  }
  .blog-body h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: #f8e7d0;
    margin: 44px 0 18px;
    font-weight: 700;
  }
  .blog-body h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    color: #f8e7d0;
    margin: 36px 0 16px;
    font-weight: 700;
  }
  .blog-body ul, .blog-body ol {
    margin: 20px 0 24px;
    padding-left: 28px;
  }
  .blog-body ul {
    list-style-type: disc;
  }
  .blog-body ol {
    list-style-type: decimal;
  }
  .blog-body li {
    margin-bottom: 10px;
    padding-left: 4px;
  }
  .blog-body blockquote {
    border-left: 3px solid #d4a658;
    background: rgba(212, 166, 88, 0.04);
    padding: 20px 28px;
    margin: 30px 0;
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: #f4d7ab;
    line-height: 1.7;
  }
  .blog-body strong {
    color: #ffffff;
    font-weight: 700;
  }
  .blog-body a {
    color: #d4a658;
    text-decoration: underline;
    transition: color 0.15s;
  }
  .blog-body a:hover {
    color: #f4d7ab;
  }
  .blog-body img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 28px 0;
    border: 1px solid rgba(212, 166, 88, 0.15);
  }
</style>
@endsection
