@foreach($data ?? array() as $item)
  <li class="col-sm-6 gallery-item" data-aos="fade-up" data-aos-duration="1500">
    <div class="content">
      <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : '' }}" class="img-fluid" alt="">
      <div class="description">
        <a href="{{ route('frontend.galleries.show', $item['slug']) }}"><h1 class="title">{{ $item['title'] }}</h1></a>
        <a href="{{ route('frontend.galleries.show', $item['slug']) }}" class="btn btn-sm btn-album">See Album</a>
      </div>
    </div>
  </li>
@endforeach
