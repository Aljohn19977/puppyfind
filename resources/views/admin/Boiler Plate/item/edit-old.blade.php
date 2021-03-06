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


// get_company_list();


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

// function api_supplier_info(){

//   $.ajax({
//         type: 'get',
//         url: "{{ route('supplier.api_view',['id' => $items->id ]) }}",
//         success: function(data) {
//           $("#box_photo").attr("src","/"+data.photo);
//            $.each(data, function(key, value){                         
//               $('#box_'+key+'').text(value);
//             });
//         },
//         error: function(error){
//           console.log('error');
//         }
//      }); 
// }

// function get_company_list(){
//     $.ajax({
//         type: 'get',
//         url: "{{ route('supplier.api_company_list') }}",
//         success: function(data) {

//         JSON.parse(data).data.forEach(row => {
//             $("#company").append('<option value="'+row.id+'">'+row.name+'</option>');
//         })

//         api_selected_company();

//         },
//         error: function(error){
//           console.log('error');
//         }
//      }); 

// }

// function api_selected_company(){

//     $.ajax({
//         type: 'get',
//         dataType: 'JSON',
//         url: "{{ route('supplier.api_selected_company',['id' => $items->id ]) }}",
//         success: function(data) {

//             var test = data.data;

//             $company_multi_select.val(test).trigger("change");

//         },
//         error: function(error){
//           console.log('error');
//         }
//      }); 

       

// }



// function clearError(){
//   $( ".is-invalid" ).removeClass("is-invalid");
//   $( ".help-block" ).remove();
// }

// $('#reset').click(function(event){
//   event.preventDefault();
//   Pace.restart();
//   Pace.track(function () {
//   $.ajax({
//         type: 'get',
//         url: "{{ route('supplier.api_view',['id' => $items->id ]) }}",
//         success: function(data) {
//            $.each(data, function(key, value){                         
//               $('#'+key+'').val(value);
//               $('#'+key+'').text(value);
//            });
//            api_selected_company();
//         },
//         error: function(error){
//           console.log('error');
//         }
//      }); 
//   }); 
// });


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
                  url: "{{ route('supplier.api_upload_photo',['id' => $items->id ]) }}",
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
    formData.append( 'supplier_id', $('#supplier_id').val() );

      Pace.track(function () {
                $.ajax({
                      url: "{{ route('supplier.update',['id' => $items->id ]) }}",
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
            <h1>Item</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Item Info</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3 col-sm-12">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                
                  <div class="row" style="margin-bottom:20px;">
                  <div class="col-md-12">
                  <img class="profile-user-img img-fluid"
                       src="{{ asset($items->photo) }}"
                       alt="User profile picture" id="box_photo" style="margin-bottom:10px;">
                  </div>
                  <div class="col-md-12">
                  <button id="change_photo" class="btn btn-block btn-primary btn-sm"><i class="nav-icon fas fa-pen" style="color:white; margin-right:10px;"></i>Change</button>
                  <button id="remove_photo" class="btn btn-block btn-primary btn-sm disabled"><i class="nav-icon fas fa-trash" style="color:white; margin-right:10px;"></i>Remove</button>
                  </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
              <a href="{{ route('item.index') }}" class="btn btn-danger float-right"><i class="nav-icon fas fa-long-arrow-alt-left" style="color:white; margin-right:10px;"></i>Back</a>
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="active nav-link" href="#settings" data-toggle="tab"><i class="fas fa-pen mr-1"></i>Edit Info</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal" role="form" method="post" id="update_supplier">
            <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                      <label for="item_id">Item ID</label>
                      <input type="text" class="form-control" id="item_id" name="item_id" disabled>
                  </div>
                  <div class="form-group" id="name_this">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                  </div>  
                  <div class="form-group" id="supplier_this">
                    <label>Supplier</label>
                    <a href="{{ route('supplier.create' )}}" class="btn btn-primary btn-sm float-right"><i class="nav-icon fas fa-plus" style="color:white;"></i></a>
                    <select class="select2" id="supplier" name="supplier[]" multiple="multiple" data-placeholder="Select a Supplier" style="width: 100%;">
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="row">
                <div class="col-md-6">
                      <div class="form-group" id="weight_this">
                          <label for="name">Weight</label>
                          <input type="number" class="form-control" id="weight" name="weight" placeholder="Weight">
                      </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group" id="weight_uom_this">
                    <a href="{{ route('uom.index' )}}" class="btn btn-primary btn-sm float-right"><i class="nav-icon fas fa-plus" style="color:white;"></i></a>  
                        <label>UOM <small>(Weight)</small></label>
                          <select class="select2" id="weight_uom" name="weight_uom" data-placeholder="UOM" style="width: 100%;">
                          </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="low_stock_this">
                          <label for="name">Low Stock <small>(Alert Qty)</small></label>
                          <input type="number" class="form-control" id="low_stock" name="low_stock" placeholder="Qty">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="item_uom_this">
                      <a href="{{ route('uom.index' )}}" class="btn btn-primary btn-sm float-right"><i class="nav-icon fas fa-plus" style="color:white;"></i></a>
                        <label>UOM <small>(Item)</small></label>
                          <select class="select2" id="item_uom" name="item_uom" data-placeholder="UOM" style="width: 100%;">
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" id="category_id_this">
                    <label>Category</label>
                    <a href="{{ route('uom.index' )}}" class="btn btn-primary btn-sm float-right"><i class="nav-icon fas fa-plus" style="color:white;"></i></a>
                    <select class="select2" id="category_id" name="category_id" data-placeholder="Select a Category" style="width: 100%;">
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                          <label>Description</label>
                          <textarea class="form-control" id="description" name="description" rows="5" placeholder="Details..."></textarea>
                    </div>
                    <div class="form-group" id="photo_this">
                          <label for="photo">Image File</label>
                          <div class="input-group">
                                  <input type="file" id="photo" name="photo">
                          </div>
                    </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button id="update" class="btn btn-primary">Update</button>
              <button id="reset" class="btn btn-primary">Reset</button>
            </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection