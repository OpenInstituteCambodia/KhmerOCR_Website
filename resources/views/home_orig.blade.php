@extends('layouts.master')
@section('content')
    <div style="padding:2rem; right:2rem">
        <form id="frmUploadImg" name="frmUploadImg" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group borderDashing heightAuto">
                        <input type="file" id="upload_file" name="upload_file" accept="image/*, application/pdf" />
                    </div>
                    <!-- Simple progress bar -->
                    <div class="progress" id="progress_bar" style="display: none; height: 25px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="borderDashing heightSize">
                        display image
                    </div>
                </div>
                <div class="col-lg-1 centerMiddle">
                    <button class="btn btn-primary" id="btnsubmit" name="btnsubmit" disabled>
                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Recognize
                    </button>
                </div>
                <div class="col-lg-6 borderDashing ">
                    <!-- Textarea for showing recognition text -->
                    <textarea autocomplete="off" class="form-control" id="khmer_ocr_result" rows="20"></textarea>
                    <!-- Showing Download button -->
                    <br><div id="download"></div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
  <script>
      $(document).ready(function() {
          $('#upload_file').change(function(){
                  if ($(this).val()) {
                      $('#btnsubmit').attr('disabled',false);
                  }
              }
          );

          // submitting form vale
          $("form[name='frmUploadImg']").submit(function(e) {
              alert('hi');
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