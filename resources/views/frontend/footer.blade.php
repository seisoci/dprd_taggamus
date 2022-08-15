<footer>
  <div class="logo">
    <img src="{{ asset("/storage/images/assets/".Menu::settings()['logo_left_url']['value']) }}" alt="">
    <img src="{{ asset("/storage/images/assets/".Menu::settings()['logo_right_url']['value']) }}" alt="">
    <div class="text"><h1>Dewan Perwakilan</h1>
      <h1>Rakyat Daerah</h1>
      <h2>Kabupaten Tanggamus</h2></div>
  </div>
  <div class="menu-foot">
    <div class="container">
      <ul>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('frontend.pages.index') }}">Pages</a></li>
        <li><a href="{{ route('frontend.berita.index') }}">Berita</a></li>
        <li><a href="{{ route('frontend.staff.index') }}">Anggota</a></li>
        <li><a href="{{ route('frontend.galleries.index') }}">Gallery</a></li>
        <li><a href="{{ route('frontend.polling.index') }}">Polling</a></li>
        <li><a href="{{ route('frontend.jdih.index') }}">JDIH</a></li>
        <li><a href="{{ route('frontend.contact.index') }}">Contact</a></li>
      </ul>
    </div>
  </div>
  <div class="info-foot">
    <div class="container">
      <div class="row content">
        <div class="col-sm-4">
          <div class="socmed">
            <h2>Connect With Us</h2>
            <ul>
              <li><a href="{{ Menu::settings()['facebook_url']['value'] ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
              </li>
              <li><a href="{{ Menu::settings()['twitter_url']['value'] ?? '#' }}"><i class="fab fa-twitter"></i></a>
              </li>
              <li><a href="{{ Menu::settings()['instagram_url']['value'] ?? '#' }}"><i class="fab fa-instagram"></i></a>
              </li>
              <li><a href="{{ Menu::settings()['youtube_url']['value'] ?? '#' }}"><i class="fab fa-youtube"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="address">
            <h2>Contact Us</h2>
            <h3>{{ Menu::settings()['address']['value'] ?? '' }}</h3>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="call">
            <h2>{{ Menu::settings()['telp']['value'] ?? '' }}</h2>
            <h3>{{ Menu::settings()['email']['value'] ?? '' }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <p>2020 &copy; DPRD Tanggamus - <a href="">Developed By Gink Technology</a></p>
  </div>
</footer>
