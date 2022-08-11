@foreach($data ?? array() as $item)
  <li class="col-sm-6 col-lg-4">
    <div class="cage-staff-card">
      <div class="staff-card">
        <img src="{{ $item['image'] ? asset("/storage/images/thumbnail/".$item['image']) : asset('assets/plugins/dist/images/staff-empty.png') }}" alt="">
        <h1 class="name">{{ $item['name'] }}</h1>
        <p>{{ $item['komisi_name'] }}</p>
        <div class="cage-button">
          <a href="{{ route('frontend.staff.show', $item['id']) }}" class="btn-card">Lihat Profile</a>
        </div>
      </div>
    </div>
  </li>
@endforeach
