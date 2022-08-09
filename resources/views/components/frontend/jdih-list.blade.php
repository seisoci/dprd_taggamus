@foreach($data ?? array() as $item)
  <li class="col-sm-6" data-aos="fade-up" data-aos-duration="1500">
    <a href="{{ route('backend.jdih.show', $item['slug']) }}" class="cage-download">
      <div class="icon">
        <i class="fas fa-download"></i>
      </div>
      <div class="description">
        <h3>{{ $item['title'] ?? '' }}</h3>
        <p>{{ $item['synopsis'] ?? '' }}</p>
      </div>
    </a>
  </li>
@endforeach
