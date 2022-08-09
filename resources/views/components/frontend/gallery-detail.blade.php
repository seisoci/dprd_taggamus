@foreach($data ?? array() as $item)
  <a href="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : '' }}" class="col-sm-6 mb-4 gallery-item" data-aos="fade-up" data-aos-duration="1500">
    <div class="cage-image">
      <img src="{{ $item['name'] ? asset("/storage/images/thumbnail/".$item['name']) : '' }}" class="img-fluid" alt="">
    </div>
  </a>
@endforeach
