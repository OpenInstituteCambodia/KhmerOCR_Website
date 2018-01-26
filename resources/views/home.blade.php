@extends('layouts.master')
@section('content')
  <section id="formlayout">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 align="center"> Khmer OCR for Limon & Unicode Images </h2>
            </div>
        </div>
        <div class="row">
        <div class="col-lg-12 mx-auto">

          {{--<form method="post" action={{url("/generated_text")}} enctype="multipart/form-data">--}}
          <form id="frmUploadImg" name="frmUploadImg" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="row justify-content-md-center" style="padding-top: 20px">
                    <div class=".col-md-6 .offset-md-3">
                        <input type="file" class="form-control-file" id="image_file" name="image_file" accept="image/*">
                    </div>
                    <div class=".col-md-6 .offset-md-3">
                        <button class="btn btn-primary pull-right mb-2" id="btnsubmit" name="btnsubmit">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i> Recognize
                        </button>
                    </div>
                </div>
            </div>
          </form>
          <textarea class="form-control" id="khmer_ocr_result" rows="20"></textarea>
        </div>
      </div>
    </div>
    <!--show waiting loading dialog -->
    <div class="modal fade fixed-dialog-center" id="modal_waiting" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog">
          <div><h3></h3></div><br/>
          <div class="spinner"></div>
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </section>
@endsection

@push('script')
  <script>
      $(document).ready(function() {
          $("form[name='frmUploadImg']").submit(function(e) {
              $('#modal_waiting').modal('show');
              var formData = new FormData($(this)[0]);
              $.ajax({
                  url: "{{ url('/generated_text') }}",
                  type: "POST",
                  data: formData,
                  async: false,
                  success: function (result) {
                      $('#modal_waiting').modal('hide');
                      $("#khmer_ocr_result").html(result).show();
                  },
                  cache: false,
                  contentType: false,
                  processData: false
              });
              e.preventDefault();
          });
      });
  </script>
@endpush