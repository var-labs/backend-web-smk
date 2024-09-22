@extends('layouts.main')

@section('title')
    <title>Edit  | Admin Panel</title>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('container')
<div class="col-md-8 offset-md-2 pt-4">
    <a href="{{ route('lainnya.index', ['token' => $token]) }}" class="btn btn-light border-warning px-4 mb-4"><i class="fas fa-arrow-left"></i> Kembali</a>
    <form action="{{route('lainnya.update',['token' => $token,'id'=>$data->id_link])}}" enctype="multipart/form-data" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <button class="btn btn-warning px-4 rounded-pill shadow-warning">Update</button>
            <div class="col-12">
                <div class="bg-white shadow-sm p-2">
                    <div class="form-group">
                        <label for="type" class="my-2"> Select Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="text" {{ $data->type == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="file" {{ $data->type == 'file' ? 'selected' : '' }}>File</option>
                            <option value="url" {{ $data->type == 'url' ? 'selected' : '' }}>URL</option>
                        </select>
                    </div>
                </div>

                <div id="text" class="bg-white shadow-sm pt-2 pr-1 pl-1">
                    <div class="form-group">
                        <label for="description" class="my-2">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ $data->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div id="file" class="bg-white shadow-sm pt-2">
                    <div class="form-group">
                        <label for="file" class="my-2">File</label>
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" placeholder="File">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div id="url" class="bg-white shadow-sm pt-2">
                    <label for="url" class="my-2">Url</label>
                    <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" placeholder="File">
                    @error('url')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
 <script  type="text/javascript" >
     var data= @json($data);

     function changeField(type) {
         if (type == 'text') {
             $('#text').show();
             $('#file').hide();
             $('#url').hide();
         } else if (type == 'file') {
             $('#text').hide();
             $('#file').show();
             $('#url').hide();
         } else {
             $('#text').hide();
             $('#file').hide();
             $('#url').show();
         }
     }

     $(document).ready(function () {
         CKEDITOR.replace('description');
         var type = $('#type');
         changeField(type.val())
         type.change(function () {
             value = $(this).val();
             changeField(value)
         });
     })
 </script>
@endsection