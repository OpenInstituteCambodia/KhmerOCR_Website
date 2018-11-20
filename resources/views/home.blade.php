@extends('layouts.master')
@section('content')
        <div class="row h-25 d-inline-block d-flex justify-content-center">
            <div class="col-12 homeTitle">
                <img src="ocr_icon_small.png" width="90px">
                <h1> Khmer OCR Engine for Limon & Unicode </h1>
            </div>
        </div>
        <form name="frmUploadImg">
            {{ csrf_field() }}
            <div class="row d-flex justify-content-center homeFileUpload">
                <div class="col-12 ">
                    <label for="file_upload" class="custom-file-upload">
                        <i class="fas fa-file-upload fa-4x"></i>
                        <br> Choose Image or PDF file
                    </label>
                    <input id="file_upload" name='file_upload' type="file" style="display:none;">
                </div>
            </div>
            <div class="d-flex justify-content-center divButtonParent">
                <button class="btn btnBig" id="btnsubmit" name="btnsubmit" disabled>
                    Recognize <i class="fas fa-angle-double-right fa-1x"></i>
                </button>
            </div>
        </form>
        <div class="row homeFileResult">
            <div class="col-6 divWithScrollXY">
                <img src="1.jpg">
            </div>
            <div class="col-6 divWithScrollXY">
                sdfsdf sfsdfsdf asdsdf
            </div>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            page 1 2 3
        </div>
@endsection

@push('script')
  <script>
      $(document).ready(function() {
          //show recognize btn disable on page load
          $('#btnsubmit').attr('disabled',true);

          // When user selects or reselects img, pdf file
          $('#file_upload').change(function() {
              var i = $(this).prev('label').clone();
              var file = $('#file_upload')[0].files[0].name;
              $(this).prev('label').text(file);
              $('#btnsubmit').attr('disabled',false);
          });

          // submitting form value
          $("form[name='frmUploadImg']").submit(function(e) {
              alert('form upload image');
//              $('#progress_bar').show();
//              $("#khmer_ocr_result").val("");
//              $("#download").html("");

              var formData = new FormData($(this)[0]);
              $.ajax({
                  url: "{{ url('/generated_text') }}",
                  type: "POST",
                  data: formData,
                  success: function (response) {
                      //$('#progress_bar').hide();
                      var parsed = JSON.parse(response);
                      alert('result= ' + parsed.result);
                      //$("#khmer_ocr_result").val(parsed.result);
                      //$("#download").html(parsed.download);
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