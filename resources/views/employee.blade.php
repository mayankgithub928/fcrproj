<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }} " />

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
	<title>FCR Project</title>
</head>
<body>
	<div class="container mt-5">
		<h2 class="mb-4">Employees</h2>
		@if(Session::has('success'))
		<div class="alert alert-sucess alert-dismissible fade show" role="alert">
			<b style="color:#00FF00"> {{ Session::get('success') }}</b>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		@endif
		<div><button type="button" id="createEmpBtn" class="btn btn-primary">Add Employee</button></div>
		<table class="table table-bordered yajra-datatables" id="yajra-table">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Country</th>
					<th>State</th>
					<th>Action</th>
				</tr>
			</thead>
		</table>
	</div>

	<div id="createEditEmployeeModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="emp_action_label"></h4>
					<button type="button" class="close" data-dismiss="modal" id="emp_section_btn">&times;</button>
				</div>
				<div class="modal-body">
					<form id="createEditEmployeeForm">
						@csrf
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name">
							<p id="name_err"></p>
							<label for="name">Email:</label>
							<input type="text" class="form-control" id="email" name="email">
							<p id="email_err"></p>
							<label for="name">Country:</label>
							
							<select name="country" id="country" class="form-control" required>
								<option value="">Select a country</option>
								@if(isset($countries) && $countries->isNotEmpty())
								@foreach($countries as $country)
								<option value="{{ $country->id }}">{{ $country->name }}</option>
								@endforeach
								@endif
							</select>
							<p id="country_err"></p>
							<label for="name">State:</label>
							<select name="state" id="state" class="form-control" required>
								<option value="">Select a state</option> 
							</select>
							<p id="state_err"></p>
							<input type="hidden" id="emp_id" name="emp_id" value="">
						</div>
						<button type="button" id="saveEmpBtn" class="btn btn-primary" style="display: none;">Add</button>
						<button type="button" id="updateEmpBtn" class="btn btn-primary" style="display: none;">Update</button>
					</form>
					<form name="deleteEmp" id="deleteEmp">
						@csrf
						<input type="hidden" name="delete_emp_id" id="delete_emp_id">
					</form>
				</div>
			</div>
		</div>


		<!-- Optional JavaScript; choose one of the two! -->

		<!-- Option 1: Bootstrap Bundle with Popper -->
		<!--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js" integrity="sha512-OQlawZneA7zzfI6B1n1tjUuo3C5mtYuAWpQdg+iI9mkDoo7iFzTqnQHf+K5ThOWNJ9AbXL4+ZDwH7ykySPQc+A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script>
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$(document).ready(function() {
				$('.yajra-datatables').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": "{{ route('employeeList') }}",

					"columns": [
						{ data: "DT_RowIndex",name:"DT_RowIndex" },
						{ data: "name",name:"name" },
						{ data: "email",name:"email" },
						{ data: "country_name",name:"country_name" },
						{ data: "state_name",name:"state_name" },
						{
							data:'action',
							name:'action',
							orderable:true,
							searchable:true
						},
		            // Add more columns as needed
						]
				});
				$('#createEmpBtn').on('click', function(){

					$('#createEditEmployeeModal').modal('show');
					$('#createEditEmployeeModal').find('form')[0].reset();
					$('#saveEmpBtn').show();
					$('#updateEmpBtn').hide();
					$('#emp_action_label').html('Add');
					$('#state').html(' <option value="">Select a state</option> ');
					resetErrorMsg();
					

				});
				$('#saveEmpBtn').on('click', function(){

					$.ajax({
						url:'employee/add',
						type:'post',
						dataType:'json',
						data:$('#createEditEmployeeForm').serializeArray(),
						success:function(response){
							if(response.status){
								$('#name').removeClass('is-invalid')
								.siblings('p')
								.removeClass('invalid-feedback')
								.html('');
								$('#email').removeClass('is-invalid')
								.siblings('p')
								.removeClass('invalid-feedback')
								.html('');


								window.location.href=window.location.href; 

							}else{
								var errors= response.errors;
								if(errors.name){

									$('#name').addClass('is-invalid');
									$('#name_err').addClass('invalid-feedback');
									$('#name_err').html(errors.name);

								}else{
									$('#name').removeClass('is-invalid')
									$('#name_err').removeClass('invalid-feedback');
									$('#name_err').html('');
								}
								if(errors.email){
									$('#email').addClass('is-invalid')
									$('#email_err').addClass('invalid-feedback');
									$('#email_err').html(errors.email);

								}else{
									$('#email').removeClass('is-invalid')
									$('#email_err').removeClass('invalid-feedback');
									$('#email_err').html('');
								}
								if(errors.country){
									$('#country').addClass('is-invalid')
									$('#country_err').addClass('invalid-feedback');
									$('#country_err').html(errors.country);

								}else{
									$('#country').removeClass('is-invalid')
									$('#country_err').removeClass('invalid-feedback');
									$('#country_err').html('');
								}
								if(errors.state){
									$('#state').addClass('is-invalid')
									$('#state_err').addClass('invalid-feedback');
									$('#state_err').html(errors.state);

								}else{
									$('#state').removeClass('is-invalid')
									$('#state_err').removeClass('invalid-feedback');
									$('#state_err').html('');
								}



							}


						}

					});

				});
				$('#updateEmpBtn').on('click', function(){
					
					$.ajax({
						url:'employee/update',
						type:'put',
						dataType:'json',
						data:$('#createEditEmployeeForm').serializeArray(),
						success:function(response){
							if(response.status){
								$('#name').removeClass('is-invalid')
								.siblings('p')
								.removeClass('invalid-feedback')
								.html('');
								$('#email').removeClass('is-invalid')
								.siblings('p')
								.removeClass('invalid-feedback')
								.html('');
								
								
								window.location.href=window.location.href; 

							}else{
								var errors= response.errors;
								if(errors.name){

									$('#name').addClass('is-invalid');
									$('#name_err').addClass('invalid-feedback');
									$('#name_err').html(errors.name);
									
								}else{
									$('#name').removeClass('is-invalid')
									$('#name_err').removeClass('invalid-feedback');
									$('#name_err').html('');
								}
								if(errors.email){
									$('#email').addClass('is-invalid')
									$('#email_err').addClass('invalid-feedback');
									$('#email_err').html(errors.email);
									
								}else{
									$('#email').removeClass('is-invalid')
									$('#email_err').removeClass('invalid-feedback');
									$('#email_err').html('');
								}
								if(errors.country){
									$('#country').addClass('is-invalid')
									$('#country_err').addClass('invalid-feedback');
									$('#country_err').html(errors.country);

								}else{
									$('#country').removeClass('is-invalid')
									$('#country_err').removeClass('invalid-feedback');
									$('#country_err').html('');
								}
								if(errors.state){
									$('#state').addClass('is-invalid')
									$('#state_err').addClass('invalid-feedback');
									$('#state_err').html(errors.state);

								}else{
									$('#state').removeClass('is-invalid')
									$('#state_err').removeClass('invalid-feedback');
									$('#state_err').html('');
								}

							}

						}
						
					});

				});
				$('#country').change(function() {
					var country_id = $(this).val();
			        //alert(country_id);
					$.ajax({
						url: '/get-states/' + country_id,
						type: 'GET',
						success: function(response) {
							var options = '';
							$.each(response, function(key, value) {
								options += '<option value="' + key + '">' + value + '</option>';
							});
							$('#state').html(options);
						}
					});
				});

				$('#emp_section_btn').on('click',function() {
					resetErrorMsg();
							
					$('#createEditEmployeeModal').modal('hide');

				});



			});


function resetErrorMsg(){
	$('#name').removeClass('is-invalid');
	$('#name_err').removeClass('invalid-feedback');
	$('#name_err').html('');
	$('#email').removeClass('is-invalid');
	$('#email_err').removeClass('invalid-feedback');
	$('#email_err').html('');
	$('#country').removeClass('is-invalid');
	$('#country_err').removeClass('invalid-feedback');
	$('#country_err').html('');
	$('#state').removeClass('is-invalid');
	$('#state_err').removeClass('invalid-feedback');
	$('#state_err').html('');
}


function editEmployee(empId){
	resetErrorMsg();
	$.ajax({
		url: '/employee/edit/'+empId,
		type: 'GET',
		success: function(response) {


			$('#createEditEmployeeModal').modal('show');
			$('#country').val(response.country_id);

			$('#country').trigger('change');
			$('#emp_id').val(response.id);
			$('#name').val(response.name);
			$('#email').val(response.email);
			setTimeout(function() {
				$('#state').val(response.state_id);
			}, 1000);


			$('#saveEmpBtn').hide();
			$('#updateEmpBtn').show();
			$('#emp_action_label').html('Edit');

		}
	});
}
function deleteEmployee(emp_id) {
	if(confirm('Do you want to delete this record?')){

		$.ajax({
			url: 'employee/delete/' + emp_id,
			type: 'DELETE',
			dataType:'json',
			success: function(response) {
				if(response.status){
					window.location.href=window.location.href; 
				}
			}
		});
	}
}


</script>
</div>
</div>
</body>
</html>