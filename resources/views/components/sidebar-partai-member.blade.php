<div class="list-group">
<a href="{{ $type != 'educations' ? route('backend.partai-member.educations.index', ['partai_member' => $partaiMemberId]) : '#' }}" class="list-group-item list-group-item-action {{ $type == 'educations' ? 'bg-primary text-white' : '' }}">Pendidikan</a>
<a href="{{ $type != 'professions' ? route('backend.partai-member.professions.index', ['partai_member' => $partaiMemberId]) : '#' }}" class="list-group-item list-group-item-action {{ $type == 'professions' ? 'bg-primary text-white' : '' }}">Pekerjaan</a>
<a href="{{ $type != 'organizations' ? route('backend.partai-member.organizations.index', ['partai_member' => $partaiMemberId]) : '#' }}" class="list-group-item list-group-item-action {{ $type == 'organizations' ? 'bg-primary text-white' : '' }}">Organisasi</a>
<a href="{{ $type != 'movements' ? route('backend.partai-member.movements.index', ['partai_member' => $partaiMemberId]) : '#' }}" class="list-group-item list-group-item-action {{ $type == 'movements' ? 'bg-primary text-white' : '' }}">Pergerakan</a>
<a href="{{ $type != 'awards' ? route('backend.partai-member.awards.index', ['partai_member' => $partaiMemberId]) : '#' }}" class="list-group-item list-group-item-action {{ $type == 'awards' ? 'bg-primary text-white' : '' }}">Penghargaan</a>
</div>
