@extends('backend.layouts.app')

@section('title', 'Profile')

@push('styles')

@endpush


@section('content')

    <div class="block-header"></div>

    <div class="row clearfix">

        <div class="col-xs-12">
            <div class="card">
                <div class="header bg-indigo">
                    <h2>
                        PROFILE
                        <a href="{{route('admin.changepassword')}}" class="btn waves-effect waves-light right headerightbtn">
                            <i class="material-icons left">lock</i>
                            <span>CHANGE PASSWORD </span>
                        </a>
                    </h2>
                </div>
                <div class="body">
                    <form action="{{route('admin.profile')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="name" class="form-control" value="{{ $profile->name }}">
                                <label class="form-label">Name</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="username" class="form-control" value="{{ $profile->username}}">
                                <label class="form-label">User Name</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                <input type="email" name="email" class="form-control" value="{{ $profile->email}}">
                                <label class="form-label">Email</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="file" name="image">
                            {{-- <img src="" id="profile-imgsrc" class="img-responsive">
                            <input type="file" name="image" id="profile-image-input" style="display:none;">
                            <button type="button" class="btn bg-grey btn-sm waves-effect m-t-15" id="profile-image-btn">
                                <i class="material-icons">image</i>
                                <span>UPLOAD IMAGE</span>
                            </button> --}}
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                <label for="about">About Us</label>
                                <textarea name="about" id="about">{{ $profile->about}}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-indigo btn-lg m-t-15 waves-effect">
                            <i class="material-icons">save</i>
                            <span>SAVE</span>
                        </button>

                    </form>
                    
                </div>
            </div>
        </div>

    </div>

@endsection


@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/fileinput.min.js"></script>
    <script src="{{asset('backend/plugins/tinymce/tinymce.js')}}"></script>

    <script>
        $(function(){
            function showImage(fileInput,imgID){
                if (fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e){
                        $(imgID).attr('src',e.target.result);
                        $(imgID).attr('alt',fileInput.files[0].name);
                    }
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }

            $(function () {
                tinymce.init({
                    selector: "textarea#about",
                    theme: "modern",
                    height: 300,
                    plugins: [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern imagetools'
                    ],
                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                    toolbar2: 'print preview media | forecolor backcolor emoticons',
                    image_advtab: true
                });
                tinymce.suffix = ".min";
                tinyMCE.baseURL = '{{asset('backend/plugins/tinymce')}}';
            });

            $('#profile-image-btn').on('click', function(){
                $('#profile-image-input').click();
            });
            $('#profile-image-input').on('change', function(){
                showImage(this, '#profile-imgsrc');
            });
        })
    </script>

@endpush
