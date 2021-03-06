@extends('admin.partials.master')

@section('style')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
@endsection

@section('script')
<!-- Select2 -->
<script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function(){


$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var $company_multi_select = $('.select2').select2();


get_company_list();


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

function api_supplier_info(){

  $.ajax({
        type: 'get',
        url: "{{ route('supplier.api_view',['id' => $suppliers->id ]) }}",
        success: function(data) {
          $("#box_photo").attr("src","/"+data.photo);
           $.each(data, function(key, value){                         
              $('#box_'+key+'').text(value);
            });
        },
        error: function(error){
          console.log('error');
        }
     }); 
}

function get_company_list(){
    $.ajax({
        type: 'get',
        url: "{{ route('supplier.api_company_list') }}",
        success: function(data) {

        JSON.parse(data).data.forEach(row => {
            $("#company").append('<option value="'+row.id+'">'+row.name+'</option>');
        })

        api_selected_company();

        },
        error: function(error){
          console.log('error');
        }
     }); 

}

function api_selected_company(){

    $.ajax({
        type: 'get',
        dataType: 'JSON',
        url: "{{ route('supplier.api_selected_company',['id' => $suppliers->id ]) }}",
        success: function(data) {

            var test = data.data;

            $company_multi_select.val(test).trigger("change");

        },
        error: function(error){
          console.log('error');
        }
     }); 

       

}



function clearError(){
  $( ".is-invalid" ).removeClass("is-invalid");
  $( ".help-block" ).remove();
}

$('#reset').click(function(event){
  event.preventDefault();
  Pace.restart();
  Pace.track(function () {
  $.ajax({
        type: 'get',
        url: "{{ route('supplier.api_view',['id' => $suppliers->id ]) }}",
        success: function(data) {
           $.each(data, function(key, value){                         
              $('#'+key+'').val(value);
              $('#'+key+'').text(value);
           });
           api_selected_company();
        },
        error: function(error){
          console.log('error');
        }
     }); 
  }); 
});


$('#change_photo').click(function(event){
  event.preventDefault();
  Swal.fire({
          title: 'Select a Photo',
          showCancelButton: true,
          confirmButtonText: 'Upload',
          input: 'file',
          inputAttributes: {
            accept: 'image/*',
            'aria-label': 'Upload your profile picture'
          },
          onBeforeOpen: () => {
              $(".swal2-file").change(function () {
                  var reader = new FileReader();
                  reader.readAsDataURL(this.files[0]);
              });
          }
      }).then((file) => {
          if (file.value) {
              var formData = new FormData();
              var file = $('.swal2-file')[0].files[0];
              formData.append("photo", file);
              Pace.restart();
              Pace.track(function () {
              $.ajax({
                  method: 'post',
                  url: "{{ route('supplier.api_upload_photo',['id' => $suppliers->id ]) }}",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (data) {
                    api_supplier_info();
                    Toast.fire({
                            type: 'success',
                            title: name+' Successfully Change.'
                          })
                  },
                  error: function(error) {
                    Toast.fire({
                            type: 'error',
                            title: 'Invalid Input File.'
                          })
                  }
              })
            });

          }
    })
});

$('#update_supplier').on('submit',function(event){

    event.preventDefault();
    Pace.restart();
    var formData = new FormData(this);
    formData.append( 'supplier_id', $('#supplier_id').val());
      Pace.track(function () {
                $.ajax({
                      url: "{{ route('supplier.update',['id' => $suppliers->id ]) }}",
                      type: "post",
                      data:formData,
                      cache:false,
                      contentType: false,
                      processData: false,
                      dataType: 'JSON',
                      success: function(data) {
                        clearError();
                        api_supplier_info();
                        Toast.fire({
                          type: 'success',
                          title: name+' Successfully Updated.'
                        })
                      },
                      error: function(error){
                        Toast.fire({
                          type: 'error',
                          title: 'Invalid Inputs.'
                        })
                        clearError();
                       $.each(error.responseJSON.errors, function(key, value){                         
                                $("input[id="+key+"]").addClass("is-invalid");
                                $("#"+key+"_this").append("<span class='help-block' style='color:red;'>"+value+"</span>");
                          });
                      }
                  }); 
      });
    });  

});
</script>
@endsection

@section('control_sidebar')
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Item</li>
              <li class="breadcrumb-item active">Edit Supplier</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Edit Supplier</h3>
          </div>
          <!-- /.card-header -->
          <form role="form" method="post" id="update_supplier" enctype="multipart/form-data">
            <div class="card-body">
            <div class="row">
            <div class="col-lg-2 col-md-12">
                    <div class="card-body box-profile">
                      <div class="text-center">
                      
                        <div class="row" style="margin-bottom:20px;">
                        <div class="col-md-12">
                        @if ($suppliers->photo != null)
                        <img class="profile-user-img img-fluid"
                            src="{{ asset($suppliers->photo) }}"
                            alt="User profile picture" id="box_photo" style="margin-bottom:10px; width:140px;">
                        @else
                        <img class="profile-user-img img-fluid"
                            src="{{ asset('admin/dist/img/no-photos.png') }}"
                            alt="User profile picture" id="box_photo" style="margin-bottom:10px; width:140px;">
                        @endif
                        </div>
                        <div class="col-md-12">
                        <button id="change_photo" class="btn btn-block btn-primary btn-sm"><i class="nav-icon fas fa-pen" style="color:white; margin-right:10px;"></i>Change</button>
                        <button id="remove_photo" class="btn btn-block btn-primary btn-sm disabled"><i class="nav-icon fas fa-trash" style="color:white; margin-right:10px;"></i>Remove</button>
                        </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                  <div class="form-group">
                      <label for="supplier_id">Supplier ID</label>
                      <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="{{ $suppliers->supplier_id }}" disabled>
                  </div>
                  <div class="form-group" id="fullname_this">
                      <label for="name">Full Name</label>
                      <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Name" value="{{ $suppliers->fullname }}">
                  </div>
                  <div class="form-group" id="company_id_this">
                  <label>Company</label>
                  <a href="{{ route('company.create' )}}" class="btn btn-primary btn-sm float-right"><i class="nav-icon fas fa-plus" style="color:white;"></i></a>
                  <select class="select2" id="company" name="company_id" data-placeholder="Select a State" style="width: 100%;">
                  </select>
                  </div>
                </div>
                <div class="col-lg-5 col-md-12">
                  <div class="form-group" id="email_this">
                      <label for="email">Email Address</label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" value="{{ $suppliers->email }}">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" id="tel_no_this">
                          <label for="tel_no">Telephone No.</label>
                          <input type="text" class="form-control" id="tel_no" name="tel_no" placeholder="Telephone No" value="{{ $suppliers->tel_no }}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="mobile_no_this">
                          <label for="mobile_no">Mobile No.</label>
                          <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile No" value="{{ $suppliers->mobile_no }}">
                      </div>
                    </div>
                  </div>
                  <div class="form-group" id="address_this">
                      <label for="address">Address</label>
                      <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ $suppliers->address }}">
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group">
                        <label>Details</label>
                        <textarea class="form-control" id="details" name="details" rows="2" placeholder="Details...">{{ $suppliers->details }}</textarea>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Remarks...">{{ $suppliers->remarks }}</textarea>
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button id="update" class="btn btn-primary">Update</button>
              <button id="reset" class="btn btn-primary">Reset</button>
              <a href="{{ route('supplier.index') }}" class="btn btn-primary">Back</a>
            </div>
          </form>
          </div>
        <!-- /.card -->
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection