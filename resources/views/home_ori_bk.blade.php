@extends('layouts.master')
@section('content')
  <section id="formlayout" >
      <div class="container">
          <div class="page-header">
              <h1 align="center"> Khmer OCR for Limon & Unicode Images </h1>
          </div>

          <div class="row">
              <div class="col-lg-12 mx-auto">
                  <form id="frmUploadImg" name="frmUploadImg" method="post" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="form-group">
                          <div class="row justify-content-md-center" style="padding-top: 20px">
                              <div class=".col-md-6 .offset-md-3">
                                  <input type="file" class="form-control-file" id="image_file" name="image_file" accept="image/*">
                              </div>
                              <div class=".col-md-6 .offset-md-3">
                                  <button class="btn btn-primary pull-right mb-2" id="btnsubmit" name="btnsubmit" disabled>
                                      <i class="fa fa-file-text-o" aria-hidden="true"></i> Recognize
                                  </button>
                              </div>
                          </div>
                      </div>
                  </form>

                  <!-- Simple progress bar -->
                  <div class="progress" id="progress_bar" style="display: none; height: 25px">
                      <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                  </div><br>

                  <!-- Textarea for showing recognition text -->
                  <textarea autocomplete="off" class="form-control" id="khmer_ocr_result" rows="20"></textarea>

                  <!-- Showing Download button -->
                  <br><div id="download"></div>
              </div>
          </div>
      </div>
  </section>
@endsection

@push('script')
  <script>
      $(document).ready(function() {

          $('#image_file').change(function(){
                  if ($(this).val()) {
                      $('#btnsubmit').attr('disabled',false);
                  }
              }
          );

          // submitting form vale
          $("form[name='frmUploadImg']").submit(function(e) {
              $('#progress_bar').show();
              $("#khmer_ocr_result").val("");
              $("#download").html("");

              var formData = new FormData($(this)[0]);
              $.ajax({
                  url: "{{ url('/generated_text') }}",
                  type: "POST",
                  data: formData,
                  success: function (response) {
                      $('#progress_bar').hide();
                      var parsed = JSON.parse(response);
                      $("#khmer_ocr_result").val(parsed.result);
                      $("#download").html(parsed.download);
                  },
                  cache: false,
                  contentType: false,
                  processData: false
              });
              e.preventDefault();
          }); // end of frmUploadImg
      });
  </script>
@endpush