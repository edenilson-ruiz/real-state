@extends('backend.layouts.app')

@section('title', 'Properties')

@push('styles')

    <!-- JQuery DataTable Css -->
<!--    <link rel="stylesheet" href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}">-->

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/> --}}
<!--    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">-->
    <link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">  
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('content')

    <div class="block-header">
        <a href="{{route('admin.properties.create')}}" class="waves-effect waves-light btn right m-b-15 addbtn">
            <i class="material-icons left">add</i>
            <span>CREATE </span>
        </a>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-indigo">
                    <h2>PROPERTY LIST</h2>
                </div>
                <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="users-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Type</th>
                                    <th>Purpose</th>
                                    <th>Beds</th>
                                    <th>Baths</th>
                                    <th><i class="material-icons small">comment</i></th>
                                    <th><i class="material-icons small">stars</i></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
<!--                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-exportable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Type</th>
                                    <th>Purpose</th>
                                    <th>Beds</th>
                                    <th>Baths</th>
                                    <th><i class="material-icons small">comment</i></th>
                                    <th><i class="material-icons small">stars</i></th>
                                    <th style="width: 150px">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach( $properties as $key => $property )
                                    <tr>
                                        <td>{{$property->id}}</td>
                                        <td>
                                            @if(Storage::disk('public')->exists('property/'.$property->image) && $property->image)
                                                <img src="{{Storage::url('property/'.$property->image)}}" alt="{{$property->title}}" width="60" class="img-responsive img-rounded">
                                            @endif
                                        </td>
                                        <td>
                                        <span title="{{$property->title}}">
                                            {{ str_limit($property->title,10) }}
                                        </span>
                                        </td>
                                        <td>{{$property->user->name}}</td>
                                        <td>{{$property->type}}</td>
                                        <td>{{$property->purpose}}</td>
                                        <td>{{$property->bedroom}}</td>
                                        <td>{{$property->bathroom}}</td>

                                        <td>
                                            <span class="badge bg-indigo">{{ $property->comments_count }}</span>
                                        </td>

                                        <td>
                                            @if($property->featured == true)
                                                <span class="badge bg-indigo"><i class="material-icons small">star</i></span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{route('admin.properties.show',$property->slug)}}" class="btn btn-success btn-sm waves-effect">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{route('admin.properties.edit',$property->slug)}}" class="btn btn-info btn-sm waves-effect">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect" onclick="deletePost({{$property->id}})">
                                                <i class="material-icons">delete</i>
                                            </button>
                                            <form action="{{route('admin.properties.destroy',$property->slug)}}" method="POST" id="del-post-{{$property->id}}" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

    <!-- Custom Js -->
<!--    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>-->

    <script type="text/javascript">

        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.datatables.properties') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'image', name: 'image' },
                    { data: 'title', name: 'Title' },
                    { data: 'author', name: 'author' },
                    { data: 'type', name: 'type' },
                    { data: 'purpose', name: 'purpose' },
                    { data: 'bedroom', name: 'bedroom' },
                    { data: 'bathroom', name: 'bathroom' },
                    { data: 'comments_count', name: 'comments_count' },
                    { data: 'star', name: 'star' },
                    { data: 'action', name: 'action',
                        render:function(data, type, full, meta) {
                            // console.log(full);
                            console.log(type);
                            // console.log(meta);
                            let url_show = "{{ route('admin.properties.show',':slug') }}";
                            url_show = url_show.replace(":slug", data);

                            let url_edit = "{{ route('admin.properties.edit',':slug') }}";
                            url_edit = url_edit.replace(":slug", data);

                            let url_del = "{{ route('admin.properties.destroy',':slug') }}";
                            url_del = url_del.replace(":slug", data);

                            let id = full.id;

                            return '<a href=\"'+url_show+'\" class=\"btn btn-success btn-sm waves-effect\"> ' +
                                        '<i class=\"material-icons\">visibility</i>' +
                                   '</a>' +
                                    '<a href=\"'+url_edit+'\" class=\"btn btn-info btn-sm waves-effect\"> ' +
                                        '<i class=\"material-icons\">edit</i>' +
                                    '</a>' +
                                    '<button type="button" class="btn btn-danger btn-sm waves-effect" onclick="deletePost('+id+')"> ' +
                                       ' <i class="material-icons">delete</i>' +
                                    '</button>'+
                                    '<form action=\"'+url_del+'\" method="POST" id="del-post-'+id+'" style="display:none;">' +
                                        '@csrf' +
                                        '@method('DELETE')' +
                                    '</form>';
                        }
                    },
                ]
            });
        });


        /*$(function() {
            if ( $.fn.dataTable.isDataTable( '#example' ) ) {
                table = $('#example').DataTable();
            }
            else {
                table = $('#example').DataTable( {
                    retrieve: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('properties.index') }}",
                    paging: true,
                    searching: true,
                    data: {
                    "_token": "{{ csrf_token() }}",
                    },
                    columns: [
                        {data: 'id', name: 'id', orderable: true, searchable: true },
                        {data: 'image', name: 'image'},
                        {data: 'title', name: 'title'},
                        {data: 'user.name', name: 'user.name'},
                        {data: 'type', name: 'type'},
                        {data: 'purpose', name: 'purpose'},
                        {data: 'bedroom', name: 'bedroom'},
                        {data: 'bathroom', name: 'bathroom'},
                        {data: 'comments_count', name: 'comments_count'},
                        {data: 'feature', name: 'feature'}
                    ]
                } );
            }
        });*/


        function deletePost(id){
            
            swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    document.getElementById('del-post-'+id).submit();
                    swal(
                    'Deleted!',
                    'Post has been deleted.',
                    'success'
                    )
                }
            })
        }
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>-->

@endpush