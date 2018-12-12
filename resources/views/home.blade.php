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
                    <input id="file_upload" name='file_upload' type="file" style="display:none;" accept="application/pdf, image/jpeg, image/png">
                </div>
            </div>
            <div class="row">
                <div class="col-7 d-flex justify-content-end divButtonParent">
                    <button class="btn btnBig" id="btnsubmit" name="btnsubmit" disabled>
                        Recognize <i class="fas fa-angle-double-right fa-1x"></i>
                    </button>
                </div>
                <div class="col-5 d-flex justify-content-end divButtonParentRight">
                    <div id="download"></div>
                </div>
            </div>
        </form>
        <div class="row homeFileResult" id="div_result">
            <div class="col-6 divWithScrollXY" id="khmer_ocr_img"></div>
            <div class="col-6 divWithScrollXY" id="khmer_ocr_result"></div>
        </div>
        <div id="div_pagination" >
            <ul id="pagination" class="pagination-sm"></ul>
        </div>

        <!-- Modal for waiting while recognize data -->
        <div class="modal fade" id="spinnerLoading" tabindex="-1" role="dialog" aria-labelledby="spinnerLoadingModalCenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="spinnerLoadingTitle">Please Wait .... </h5>
                        <div class="fa fa-spinner fa-spin fa-3x"></div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('script')
  <script>
      $(document).ready(function() {
          // global csrf token variable
          token = $('input[name=_token]').val();
          //show recognize btn disable on page load
          $('#btnsubmit').attr('disabled',true);
          $("#imageFileName").val("");
          $('#div_result').hide();
          $('#div_pagination').hide();

          // When user selects or reselects img, pdf file
          $('#file_upload').change(function() {
              var i = $(this).prev('label').clone();
              var file = $('#file_upload')[0].files[0].name;
              $(this).prev('label').text(file);
              $('#btnsubmit').attr('disabled',false);
          });

          // submitting form value
          $("form[name='frmUploadImg']").submit(function(e) {
              $("#khmer_ocr_result").val("");
              $("#download").html("");

              $("#spinnerLoading").modal({
                  backdrop: "static", //remove ability to close modal with click
                  keyboard: false, //remove option to close with keyboard
                  show: true //Display loader!
              });

              var formData = new FormData($(this)[0]);

              $.ajax({
                  url: "{{ url('/generated_text') }}",
                  type: "POST",
                  data: formData,
                  success: function (response) {
                      var parsed = JSON.parse(response);
                      $("#khmer_ocr_img").html(parsed.firstImg);
                      $("#khmer_ocr_result").html(parsed.firstOCRText);
                      $("#download").html(parsed.download);
                      $('#div_result').show();
                      $('#spinnerLoading').modal('hide');
                      // if pdf with more than 1 page, then show pagination
                      if(parsed.totalPDFPages > 1){
                          $('#div_pagination').show();
                          $('#pagination').twbsPagination({
                              totalPages: parseInt(parsed.totalPDFPages),
                              visiblePages: 5,
                              prev: 'Prev',
                              next: 'Next',
                              onPageClick: function (event, page) {
                                  $.ajax({
                                      type: 'POST',
                                      url: '/pagination_request',
                                      data: {
                                          _token: token,
                                          fname: parsed.imageFileName,
                                          page: page,
                                        },
                                      cache: false,
                                      success: function(result)
                                      {
                                          var parse_result = JSON.parse(result);
                                          $("#khmer_ocr_img").html(parse_result.Img);
                                          $("#khmer_ocr_result").html(parse_result.OCRText);
                                      }
                                  });
                              }
                      });
                      }
                  },
                  cache: false,
                  contentType: false,
                  processData: false,
              });
              e.preventDefault();
          }); // end of frmUploadImg
      });

  </script>
@endpush