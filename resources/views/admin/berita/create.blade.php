@extends('layouts.main')

@section('title')
    <title>Berita | Admin Panel</title>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('container')
<div class="col-md-8 offset-md-2 pt-4">
    <a href="{{ route('berita.index', ['token' => $token]) }}" class="btn btn-light border-warning px-4 mb-4"><i class="fas fa-arrow-left"></i> Kembali</a>
    <form action="{{ route('berita.store', ['token' => $token]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="news_title">Judul Berita</label>
            <input type="text" name="news_title" id="news_title" class="form-control @error('news_title') is-invalid @enderror" placeholder="Besok ada sesuatu..." aria-describedby="nameId" value="{{ old('news_title') }}">
            <small id="nameId" class="text-muted">Hindari penggunaan slash (/,\)</small>
            @error('news_title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="news_level" class="form-label">Status</label>
            <select class="form-control @error('news_level') is-invalid @enderror" id="news_level" name="news_level">
                <option value="1" {{ old('news_level') == '1' ? 'selected' : '' }}>Biasa</option>
                <option value="0" {{ old('news_level') == '0' ? 'selected' : '' }}>Penting</option>
            </select>
            @error('news_level')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="news" class="form-label">Kategori Berita</label>
            <select class="form-control @error('id_category') is-invalid @enderror" name="id_category">
                @foreach ($news as $n)
                    <option value="{{ $n->id_category }}" {{ old('id_category') == $n->id_category ? 'selected' : '' }}>{{ $n->category_name }}</option>
                @endforeach
            </select>
            @error('id_category')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="news_location">Lokasi</label>
            <input type="text" name="news_location" id="news_location" class="form-control @error('news_location') is-invalid @enderror" value="{{ old('news_location') }}" aria-describedby="waktuId">
            <small id="waktuId" class="text-muted d-none"></small>
            @error('news_location')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <input type="hidden" name="news_viewer" id="news_viewer" class="form-control" value="0" aria-describedby="viewId">
            <small id="viewId" class="text-muted d-none"></small>
        </div>
        <div class="form-group">
            <label for="news_content">Deskripsi Berita</label>
            <textarea name="news_content" id="texteditor" cols="30" rows="10" class="form-control @error('news_content') is-invalid @enderror" placeholder="Isi dari news.." aria-describedby="textId">{{ old('news_content') }}</textarea>
            <small id="textId" class="text-muted d-none"></small>
            @error('news_content')
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
                    <label for="news_image">Thumbnail Berita</label>
                    <input onchange="loadFile(event)" type="file" name="news_image" id="image" class="form-control @error('news_image') is-invalid @enderror" placeholder="Purwosari, Pasuruan" aria-describedby="imageId">
                    <small id="imageId" class="text-muted d-none"></small>
                    @error('news_image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img class="w-100 rounded" id="preview" src="{{ asset('img/no_image.png') }}" alt="">
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
