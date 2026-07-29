@extends('layouts.app', ['title' => 'Profil Anak'])

@section('content')
<style>
    .table tbody td { text-align: left !important; }
</style>
<form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Kolom Kiri: Data -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Biodata Anak</h5>
                    
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-muted" width="40%">Nama Anak</td>
                            <td class="fw-semibold">{{ $child->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Umur</td>
                            <td class="fw-semibold">{{ $child->ageMonths() }} Bulan</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Kelamin</td>
                            <td class="fw-semibold">{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tempat Lahir</td>
                            <td class="fw-semibold">{{ $child->birth_place ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted" width="40%">Tanggal Lahir</td>
                            <td class="fw-semibold">{{ $child->birth_date?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    </table>

                    <hr class="my-4">

                    <h5 class="card-title mb-4">Nama Orang Tua</h5>

                    <table class="table table-bordered">
                        <tr>
                            <td class="text-muted" width="40%">Nama Ayah</td>
                            <td class="fw-semibold">{{ $child->father_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Ibu</td>
                            <td class="fw-semibold">{{ $child->mother_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td class="fw-semibold">{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor HP</td>
                            <td class="fw-semibold">{{ auth()->user()->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td class="fw-semibold">{{ auth()->user()->address ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Foto -->
        <div class="col-lg-6">
            <div class="row g-3">
                <!-- Foto Anak -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-3">Foto Anak</h6>
                            @if($child->photo)
                                <img id="preview_img_photo" src="{{ asset('storage/' . $child->photo) }}" class="rounded mb-3 shadow-sm mx-auto d-block" style="width: 200px; height: 200px; object-fit: cover; border: 3px solid var(--green-soft);">
                            @else
                                <img id="preview_img_photo" src="#" class="d-none rounded mb-3 shadow-sm mx-auto" style="width: 200px; height: 200px; object-fit: cover; border: 3px solid var(--green-soft); display: block;">
                                <div id="placeholder_photo" class="bg-light rounded mx-auto mb-3 shadow-sm d-flex align-items-center justify-content-center text-secondary" style="width: 200px; height: 200px; font-size: 4rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <input class="d-none" type="file" name="photo" id="photo_input" accept="image/jpeg,image/png,image/jpg" onchange="previewFile(this, 'preview_img_photo', 'placeholder_photo'); document.getElementById('btn_edit_photo').classList.add('d-none'); document.getElementById('btn_save_photo').classList.remove('d-none');">
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm px-4" id="btn_edit_photo" onclick="document.getElementById('photo_input').click()">Edit</button>
                                <button type="submit" class="btn btn-success btn-sm px-4 d-none" id="btn_save_photo">Simpan</button>
                            </div>
                            @error('photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Foto Ayah -->
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-3">
                            <h6 class="text-muted mb-2 small">Foto Ayah</h6>
                            @if($child->father_photo)
                                <img id="preview_img_father_photo" src="{{ asset('storage/' . $child->father_photo) }}" class="rounded mb-2 shadow-sm mx-auto d-block" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border: 2px solid #e0f2fe;">
                            @else
                                <img id="preview_img_father_photo" src="#" class="d-none rounded mb-2 shadow-sm mx-auto" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border: 2px solid #e0f2fe; display: block;">
                                <div id="placeholder_father_photo" class="bg-light rounded mx-auto mb-2 shadow-sm d-flex align-items-center justify-content-center text-secondary" style="width: 100%; aspect-ratio: 1/1; font-size: 3rem;">
                                    <i class="bi bi-person-standing"></i>
                                </div>
                            @endif
                            <input class="d-none" type="file" name="father_photo" id="father_photo_input" accept="image/jpeg,image/png,image/jpg" onchange="previewFile(this, 'preview_img_father_photo', 'placeholder_father_photo'); document.getElementById('btn_edit_father_photo').classList.add('d-none'); document.getElementById('btn_save_father_photo').classList.remove('d-none');">
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm px-4" id="btn_edit_father_photo" onclick="document.getElementById('father_photo_input').click()">Edit</button>
                                <button type="submit" class="btn btn-success btn-sm px-4 d-none" id="btn_save_father_photo">Simpan</button>
                            </div>
                            @error('father_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Foto Ibu -->
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-3">
                            <h6 class="text-muted mb-2 small">Foto Ibu</h6>
                            @if($child->mother_photo)
                                <img id="preview_img_mother_photo" src="{{ asset('storage/' . $child->mother_photo) }}" class="rounded mb-2 shadow-sm mx-auto d-block" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border: 2px solid #fce7f3;">
                            @else
                                <img id="preview_img_mother_photo" src="#" class="d-none rounded mb-2 shadow-sm mx-auto" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border: 2px solid #fce7f3; display: block;">
                                <div id="placeholder_mother_photo" class="bg-light rounded mx-auto mb-2 shadow-sm d-flex align-items-center justify-content-center text-secondary" style="width: 100%; aspect-ratio: 1/1; font-size: 3rem;">
                                    <i class="bi bi-person-standing-dress"></i>
                                </div>
                            @endif
                            <input class="d-none" type="file" name="mother_photo" id="mother_photo_input" accept="image/jpeg,image/png,image/jpg" onchange="previewFile(this, 'preview_img_mother_photo', 'placeholder_mother_photo'); document.getElementById('btn_edit_mother_photo').classList.add('d-none'); document.getElementById('btn_save_mother_photo').classList.remove('d-none');">
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm px-4" id="btn_edit_mother_photo" onclick="document.getElementById('mother_photo_input').click()">Edit</button>
                                <button type="submit" class="btn btn-success btn-sm px-4 d-none" id="btn_save_mother_photo">Simpan</button>
                            </div>
                            @error('mother_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewFile(input, imgId, placeholderId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById(imgId);
            img.src = e.target.result;
            img.classList.remove('d-none');
            var placeholder = document.getElementById(placeholderId);
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
