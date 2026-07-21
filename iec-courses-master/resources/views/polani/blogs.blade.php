@extends('polani.layout')

@section('title', 'The Polani Journal — Fragrance Stories & Wisdom')
@section('meta_description', 'Discover fragrance guides, olfactory stories, and luxury perfume insights from the Polani Fragrance editorial team.')

@section('content')
  <x-polani.page-banner
    page-key="blogs"
    eyebrow="POLANI JOURNAL"
    title="Blogs"
    subtitle="Discover fragrance guides, olfactory stories, and luxury perfume insights."
    fallback-image="polani/assets/home_banner_1.jpeg"
    image-position="center center"
  />

  <section class="section section--ivory" style="padding: 80px 0 100px;">
    <div class="container">
      
      {{-- Breadcrumbs --}}
      <div class="breadcrumbs" style="margin-bottom: 40px; font-size: 0.9rem; color: #555;">
        <a href="{{ route('home') }}" style="text-decoration: none; color: inherit; transition: color 0.2s;">Home</a> 
        <span aria-hidden="true" style="margin: 0 8px; color: #ccc;">&rsaquo;</span> 
        <span style="color: #111; font-weight: 600;">Blog Journal</span>
      </div>

      {{-- Blog Grid --}}
      @if($blogs->isNotEmpty())
        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-bottom: 50px;">
          @foreach($blogs as $blog)
            <article class="blog-card" style="background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.02); height: 100%;">
              @php
                $blogImg = $blog->image_path ? asset($blog->image_path) : asset('polani/assets/story-packaging.svg');
              @endphp
              <div class="blog-card__image" style="position: relative; height: 220px; overflow: hidden; background: #000;">
                <!-- Blurred Background -->
                <div style="position: absolute; inset: 0; background-image: url('{{ $blogImg }}'); background-size: cover; background-position: center; filter: blur(15px) brightness(0.65); transform: scale(1.15); z-index: 1;"></div>
                <!-- Main Image -->
                <img src="{{ $blogImg }}" 
                     alt="{{ $blog->title }}" 
                     style="position: relative; width: 100%; height: 100%; object-fit: contain; z-index: 2; transition: transform 0.5s ease;" />
                <div class="blog-card__date" style="position: absolute; bottom: 16px; left: 16px; background: rgba(10, 10, 10, 0.85); color: #d4a658; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; letter-spacing: 0.05em; border: 1px solid rgba(212,166,88,0.2); z-index: 3;">
                  {{ $blog->created_at->format('M d, Y') }}
                </div>
              </div>
              
              <div class="blog-card__content" style="padding: 24px; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                <div>
                  <h3 class="blog-card__title" style="font-family: 'Playfair Display', serif; font-size: 1.35rem; color: #111; margin: 0 0 12px; line-height: 1.4; font-weight: 700;">
                    <a href="{{ route('polani.blog.detail', $blog->slug) }}" style="text-decoration: none; color: inherit; transition: color 0.2s;">
                      {{ $blog->title }}
                    </a>
                  </h3>
                  <p class="blog-card__excerpt" style="font-size: 0.9rem; line-height: 1.6; color: #555; margin: 0 0 20px;">
                    {{ Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 110) }}
                  </p>
                </div>
                
                <a href="{{ route('polani.blog.detail', $blog->slug) }}" class="blog-card__link" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #d4a658; font-weight: 700; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em; transition: color 0.2s;">
                  Read Article <span style="font-size: 1.1rem; line-height: 1;">&rarr;</span>
                </a>
              </div>
            </article>
          @endforeach
        </div>

        {{-- Pagination --}}
        <div class="blogs-pagination" style="display: flex; justify-content: center; margin-top: 50px;">
          {{ $blogs->links() }}
        </div>
      @else
        <div style="text-align: center; padding: 100px 20px; color: #555;">
          <div style="font-size: 3rem; color: rgba(212,166,88,0.25); margin-bottom: 20px;">✦</div>
          <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #111; margin: 0 0 10px;">No Articles Found</h2>
          <p style="font-size: 0.95rem; margin: 0;">Our journal articles are currently being prepared. Check back soon!</p>
        </div>
      @endif

    </div>
  </section>

  {{-- Blog card hover & pagination styles --}}
  <style>
    .blog-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
      border-color: rgba(212,166,88,0.25) !important;
    }
    .blog-card:hover img {
      transform: scale(1.06);
    }
    .blog-card__title a:hover {
      color: #d4a658 !important;
    }
    .blog-card__link:hover {
      color: #9d6f20 !important;
    }

    /* Laravel Pagination Style overrides to match Polani dark/gold */
    .blogs-pagination .pagination {
      display: flex;
      gap: 6px;
      list-style: none;
      padding: 0;
    }
    .blogs-pagination .page-link {
      background: #ffffff;
      border: 1px solid rgba(0,0,0,0.08);
      color: #111111;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s;
    }
    .blogs-pagination .page-link:hover {
      border-color: #d4a658;
      color: #d4a658;
      background: rgba(212,166,88,0.05);
    }
    .blogs-pagination .page-item.active .page-link {
      background: #d4a658;
      border-color: #d4a658;
      color: #ffffff;
    }
    .blogs-pagination .page-item.disabled .page-link {
      background: rgba(0,0,0,0.02);
      color: #ccc;
      cursor: not-allowed;
    }
  </style>
@endsection
