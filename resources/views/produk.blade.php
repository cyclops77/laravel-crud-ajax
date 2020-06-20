<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>CRUD</title>
  </head>
  <body>
    
    {{-- MODAL TAMBAH--}}
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <label for="recipient-name" class="col-form-label">Nama : </label>
                   <input type="text" class="form-control" id="nama">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Stok : </label>
                    <input type="number" class="form-control" id="stok">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="button" class="btn btn-primary btnBuat">Buat</button>
            </div>
          </div>
        </div>
      </div>
      {{-- MODAL EDIT --}}
      <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <label for="recipient-name" class="col-form-label">Nama : </label>
                   <input type="text" class="form-control" id="edit_nama">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Stok : </label>
                    <input type="number" class="form-control" id="edit_stok">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="button" class="btn btn-primary btnUbahData" idEdit="">Ubah Data</button>
            </div>
          </div>
        </div>
      </div>

    {{-- END MODAL --}}
    <div class="container p-3">
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <a class="btn btn-primary col-md-12 btnAdd">
                    Tambah Data
                </a>
            </div>
        </div>
        <table class="mt-2 text-center table table-bordered table-stripped">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>#</th>
            </thead>
            <tbody id="tujuan">
                
            </tbody>
        </table>
    </div>



    <script>
        $(document).ready(function(){
            getDataFromDB();
            function resetModal(){
                $("#modalTambah").modal('hide');
                $("#nama").val('');
                $("#stok").val('');
            }
            function resetModalEdit(){
                $("#modalEdit").modal('hide');
                $("#edit_nama").val('');
                $("#edit_stok").val('');
            }
            // ADD DATA
            $(".btnAdd").on('click', function(){
                $("#modalTambah").modal('show');

                $(".btnBuat").on('click', function(){
                    var namaProduk = $("#nama").val();
                    var stokProduk = $("#stok").val();
                    $.ajaxSetup({
                        headers : {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method : "POST",
                        url : '{{url("/storeData")}}',
                        data : {
                            'namaProduk' : namaProduk,
                            'stokProduk' : stokProduk,
                        },
                        success : function(e){
                            resetModal();
                            getDataFromDB();
                        }
                    });
                });
            })


            // Read Data
            function getDataFromDB(){
                var x = '';
                $.ajax({
                    method : 'GET',
                    url : '{{url("/getData")}}',
                    success : function(res){
                        $.each(res, function(index, item){
                            x = x + '<tr>'+
                            '<td>'+(index+1)+'</td>'+
                            '<td>'+item.nama+'</td>'+
                            '<td>'+item.stok+'</td>'+
                            '<td>'+
                                '<a class="btn btn-danger btn-sm btnDelete" itemID='+item.id+'>Hapus</a>&nbsp'+
                                '<a class="btn btn-warning btn-sm btnUbah" itemID='+item.id+'>Ubah</a>'+
                            '</td>'+
                            '</tr>';
                        })
                        $("#tujuan").html(x)
                        $(".btnUbah").on('click', function(){
                            var idData = $(this).attr("itemID");
                            $.ajax({
                                url : "{{url('/editData')}}/" + idData,
                                method : "GET",
                                success : function(data){
                                    $("#modalEdit").modal('show');
                                    $("#edit_nama").val(data.nama);
                                    $("#edit_stok").val(data.stok);
                                    $(".btnUbahData").attr("idEdit", data.id);
                                    $(".btnUbahData").on('click', function(){
                                        idData = $(this).attr("idEdit");
                                        namaData = $("#edit_nama").val();
                                        stokData = $("#edit_stok").val();
                                        $.ajaxSetup({
                                            headers : {
                                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        $.ajax({
                                            method : "POST",
                                            url : "{{url('/updateData')}}",
                                            data : {
                                                'idData' : idData,
                                                'namaData' : namaData,
                                                'stokData' : stokData,
                                            },
                                            success : function(o){
                                                resetModalEdit();
                                                getDataFromDB();
                                            }
                                        });
                                    }); 
                                }
                            });
                        });
                        $(".btnDelete").on('click', function(){
                            var idData = $(this).attr("itemID");
                            $.ajaxSetup({
                                headers : {
                                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                method : "POST",
                                url : "{{url('/destroyData')}}",
                                data : {
                                    'idData' : idData,
                                },
                                success : function(e){
                                    getDataFromDB();
                                }
                            });
                        })
                    }
                });
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>