@foreach($data ?? array() as $item)
  <li>
    <div class="row no-gutters">
      <div class="col-sm-6 order-2 order-sm-1" data-aos="fade-right" data-aos-duration="1500">
        <div class="description">
          <div
            class="head">{{ isset($item['publish_at']) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['publish_at'])->isoFormat('DD MMMM YYYY') : '' }}
            | {{ $item['categories'] ?? '' }}</div>
          <a href="">
            <h1 class="title">{{ $item['title'] ?? '' }}</h1>
            <p>{{ $item['synopsis'] ?? '' }}</p>
          </a>
          <div class="cage-link">
            <a href="{{ route('frontend.news.detail', $item['slug']) }}" class="btn btn-sm btn-link">Read
              More</a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 order-1 order-sm-2 nli" data-aos="fade-left" data-aos-duration="1500">
        <a href="{{ route('frontend.news.detail', $item['slug']) }}" class="image-cage">
          <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : '' }}" alt="">
        </a>
      </div>
    </div>
  </li>
@endforeach
