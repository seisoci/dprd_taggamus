@foreach($data ?? array() as $item)
  <li>
    <div class="row g-0">
      <div class="col-sm-6 order-2 order-sm-1" data-aos="fade-right" data-aos-duration="1500">
        <div class="description">
          <h1>{{ $item['title'] ?? '' }}</h1>
          <div class="head">{{ isset($item['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}</div>
          <div class="share-container">
            <ul>
              <li><span>Share</span></li>
              <li><a href="https://www.facebook.com/sharer.php?u={{ route('frontend.videos.index') }}"><i class="fab fa-facebook-f"></i></a></li>
              <li><a href="https://twitter.com/share?url={{ route('frontend.videos.index') }}"><i class="fab fa-twitter"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6 order-1 order-sm-2 prv" data-aos="fade-left" data-aos-duration="1500">
        <div class="video-cage">
          <iframe width="100%" height="100%" src="{{ $item['body'] ?? '' }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </li>
@endforeach
