<section class="menu-block">
  <ul>
    <li><a href="/"><i class="fas fa-home"></i> <span>Beranda</span></a></li>
    <li class="{{ (request()->segment(1) == 'pages') ? 'active' : '' }}">
      <a href="{{ route('frontend.pages.index') }}"><i class="far fa-file-alt"></i> <span>Pages</span></a></li>
    <li class="{{ (request()->segment(1) == 'berita') ? 'active' : '' }}">
      <a href="{{ route('frontend.berita.index') }}"><i class="far fa-file-alt"></i> <span>Berita</span></a>
    </li>
    <li class="{{ (request()->segment(1) == 'staff') ? 'active' : '' }}"><a href="{{ route('frontend.staff.index') }}"><i class="fas fa-users"></i> <span>Anggota</span></a></li>
    <li class="{{ in_array(request()->segment(1), ['galleries', 'videos']) ? 'active' : '' }}">
      <a href="#">
        <i class="far fa-images"></i> <span>Gallery</span></a>
      <ul>
        <li class="{{ (request()->segment(1) == 'galleries') ? 'active' : '' }}">
          <a href="{{ route('frontend.galleries.index') }}">Gallery Photo</a>
        </li>
        <li class="{{ (request()->segment(1) == 'videos') ? 'active' : '' }}">
          <a href="{{ route('frontend.videos.index') }}">Gallery Video</a>
        </li>
      </ul>
    </li>
    <li class="{{ (request()->segment(1) == 'polling') ? 'active' : '' }}">
      <a href="{{ route('frontend.polling.index') }}"><i class="far fa-chart-bar"></i> <span>Polling</span></a>
    </li>
    <li class="{{ (request()->segment(1) == 'jdih') ? 'active' : '' }}">
      <a href="{{ route('frontend.jdih.index')  }}">
        <i class="fas fa-download"></i> <span>JDIH</span>
      </a>
    </li>
    <li class="{{ (request()->segment(1) == 'contact') ? 'active' : '' }}">
      <a href="{{ route('frontend.contact.index') }}">
        <i class="fas fa-phone-alt"></i> <span>Contact</span>
      </a>
    </li>
  </ul>
</section>
