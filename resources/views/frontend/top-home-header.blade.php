<header>
  <div class="top-header">
    <a href="{{ '/' }}" class="logo">
      <img src="{{ asset("/storage/images/assets/".Menu::settings()['logo_left_url']['value']) }}" alt="">
      <img src="{{ asset("/storage/images/assets/".Menu::settings()['logo_right_url']['value']) }}" alt="">
      <div class="text"><h1>Dewan Perwakilan</h1>
        <h1>Rakyat Daerah</h1>
        <h2>Kabupaten Tanggamus</h2></div>
    </a>
    <div class="trigger-header">
      <form method="GET" action="{{ route('frontend.search') }}" class="form-search">
        <input name="q" type="text" placeholder="Cari disini ...">
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
      <div class="cage-nav">
        <div class="navTrigger">
          <i></i><i></i><i></i>
        </div>
      </div>
    </div>
  </div>
</header>
