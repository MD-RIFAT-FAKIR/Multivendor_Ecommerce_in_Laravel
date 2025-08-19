<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{asset('adminbackend/assets/images/favicon-32x32.png')}}" type="image/png" />
	<!--plugins-->
	<link href="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
	<link href="{{asset('adminbackend/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('adminbackend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('adminbackend/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{asset('adminbackend/assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{asset('adminbackend/assets/js/pace.min.js')}}"></script>

		<!-- tag input -->
		<link href=" {{ asset('adminbackend/assets/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet" />
	<!-- tinymce -->
	<script
      src="https://cdn.tiny.cloud/1/73yzz9jn4sdvexn9tlsgxri1ula6pve6gsdbr2ykt5gwehzg/tinymce/7/tinymce.min.js"
      referrerpolicy="origin"
    ></script>
	
	<!-- Bootstrap CSS -->
	<link href="{{asset('adminbackend/assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('adminbackend/assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('adminbackend/assets/css/icons.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/header-colors.css')}}" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
	 <!-- font awesome cdn -->
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	 <!-- data table CSS -->
	<link href="{{asset('adminbackend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
	<title>Vendor Dashboard</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		@include('vendor.body.sidebar')
		<!--end sidebar wrapper -->

		<!--start header -->
            @include('vendor.body.header')
		<!--end header -->

		<!--start page wrapper -->
		<div class="page-wrapper">
			@yield('vendor')
		</div>
		<!--end page wrapper -->

		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->

		<!--Start Back To Top Button-->
         <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->

        <!-- footerstart -->
            @include('vendor.body.footer')
         <!-- footerend -->

	</div>
	<!--end wrapper-->
    
	
	<!-- Bootstrap JS -->
		<script src="{{asset('adminbackend/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('adminbackend/assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/chartjs/js/Chart.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery-knob/excanvas.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery-knob/jquery.knob.js')}}"></script>
	  <script>
		  $(function() {
			  $(".knob").knob();
		  });
	  </script>
	  <script src="{{asset('adminbackend/assets/js/index.js')}}"></script>
	<!--app JS-->
	<script src="{{asset('adminbackend/assets/js/app.js')}}"></script>
	<!-- add brand validation -->
	<script src="{{asset('adminbackend/assets/js/validate.min.js')}}"></script>

	<!--Datatable JS-->
	<script src="{{asset('adminbackend/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	</script>
	<!--Datatable JS-->

<!--toster message-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif 
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <script src="{{ asset('adminbackend/assets/js/code.js') }}"></script>
 <!-- input tag -->
 <script src=" {{asset('adminbackend/assets/plugins/input-tags/js/tagsinput.js')}}"></script>




 <!-- tinymce -->
 <script>
      tinymce.init({
        selector: "#mytextarea",
        plugins: [
          // Core editing features
          "anchor",
          "autolink",
          "charmap",
          "codesample",
          "emoticons",
          "image",
          "link",
          "lists",
          "media",
          "searchreplace",
          "table",
          "visualblocks",
          "wordcount",
          // Your account includes a free trial of TinyMCE premium features
          // Try the most popular premium features until Mar 17, 2025:
          "checklist",
          "mediaembed",
          "casechange",
          "export",
          "formatpainter",
          "pageembed",
          "a11ychecker",
          "tinymcespellchecker",
          "permanentpen",
          "powerpaste",
          "advtable",
          "advcode",
          "editimage",
          "advtemplate",
          "ai",
          "mentions",
          "tinycomments",
          "tableofcontents",
          "footnotes",
          "mergetags",
          "autocorrect",
          "typography",
          "inlinecss",
          "markdown",
          "importword",
          "exportword",
          "exportpdf",
        ],
        toolbar:
          "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
        tinycomments_mode: "embedded",
        tinycomments_author: "Author name",
        mergetags_list: [
          { value: "First.Name", title: "First Name" },
          { value: "Email", title: "Email" },
        ],
        ai_request: (request, respondWith) =>
          respondWith.string(() =>
            Promise.reject("See docs to implement AI Assistant")
          ),
      });
    </script>
</body>

</html>