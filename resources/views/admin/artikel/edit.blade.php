@extends('layouts.main')

@section('title')
    <title>Artikel | Admin Panel</title>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('container')
<div class="col-md-8 offset-md-2 pt-4">
    <a href="{{ route('artikel.index', ['token' => $token]) }}" class="btn btn-light border-warning px-4 mb-4"><i class="fas fa-arrow-left"></i> Kembali</a>
    <form action="{{ route('artikel.update', ['token' => $token, 'artikel' => $artikel->id_pemberitahuan]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="artikel_title">Judul Artikel</label>
            <input type="text" name="artikel_title" id="artikel_title" class="form-control" placeholder="Besok ada sesuatu..." aria-describedby="nameId" value="{{ $artikel->nama }}">
            <small id="nameId" class="text-muted">Hindari penggunaan slash (/,\)</small>
            @error('artikel_title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="artikel_level" class="form-label">Status</label>
            <select class="form-control" id="artikel_level" name="artikel_level">
                <option value="1" {{ $artikel->level == 1 ? 'selected' : '' }}>Biasa</option>
                <option value="0" {{ $artikel->level == 0 ? 'selected' : '' }}>Penting</option>
            </select>
            @error('artikel_level')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="artikel" class="form-label">Kategori artikel</label>
            <select class="form-control" name="id_category">
                @foreach ($categories as $category)
                    <option value="{{ $category->id_pemberitahuan_category }}" {{ $artikel->category == $category->id_pemberitahuan_category ? 'selected' : '' }}>
                        {{ $category->pemberitahuan_category_name }}
                    </option>
                @endforeach
            </select>
          @error('id_category')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
            @enderror
        </div>
        <div class="form-group">
            <input type="hidden" name="artikel_viewer" id="artikel_viewer" class="form-control" value="{{ $artikel->viewer }}" aria-describedby="viewId">
                <small id="viewId" class="text-muted d-none"></small>
        </div>
        <div class="form-group">
            <label for="artikel_text">Deskripsi artikel</label>
            <textarea name="artikel_text" id="texteditor" cols="30" rows="10" class="form-control" placeholder="Isi dari artikel.." aria-describedby="textId">{{ $artikel->text }}</textarea>
            <small id="textId" class="text-muted d-none"></small>
            @error('artikel_text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
            @enderror
        </div>
        <script>
            CKEDITOR.replace('texteditor');
        </script>
        <div class="row">
            <div class="col-md-6 py-md-5 py-3">
                <div class="form-group">
                    <label for="thumbnail">Thumbnail artikel</label>
                    <input onchange="loadFile(event)" type="file" name="thumbnail" id="image" class="form-control" placeholder="Purwosari, Pasuruan" aria-describedby="imageId">
                    <small id="imageId" class="text-muted d-none"></small>
                @error('thumbnail')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img class="w-100 rounded" id="preview"
                src="{{ asset('img/artikel/'.$artikel->thumbnail) }}"
                alt="">
            </div>
        </div>
        <div class="text-right mb-4">
            <button type="submit" class="btn btn-warning mt-2 px-5 rounded-pill shadow-warning"><i class="fas fa-paper-plane"></i> Submit</button>
        </div>
    </form>
</div>
<script>
    function loadFile(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var preview = document.getElementById('preview');
            preview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
