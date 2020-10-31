<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Employee management</title>
    <link rel="icon" href="http://localhost/crud-demo/images/favicon.png">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
<!--     HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries 
     WARNING: Respond.js doesn't work if you view the page via file:// 
    [if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style>
    
        .navcolor{
            background-color: slategray;
        }
        .mtitlecolor{
            background-color: aliceblue;
        }
        .outimg{
            width: 120px;
            height: 143px;
        }
        .namesize{
            font-size: large;
        }
        .infotitle{
            font-size: xx-large;
            background-color: beige;
        }
        .outimg1{
            width: 125px;
            height: 117px;
        }
    
    </style>
    
    </head>
<body>

    <nav class="navbar navbar-inverse navbar-fixed-top navcolor">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" style="color:#fff" href="<?php site_url('employee/index');?>">Home</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <div class="nav navbar-nav navbar-right">
            <li><a href="https://github.com/nikhil141/Codeigniter_Employee_Crud_Module" style="color:#F3F3F3">Github (URL)</a></li>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container" style="margin-top:60px">
        <h2 class="text-center">Employee Crud Operation</h2>
        <button class="btn btn-success" onclick="addEmployee()"><i class="glyphicon glyphicon-plus"></i>Add Employee</button>
        <button class="btn btn-default" onclick="reloadTable()"><i class="glyphicon glyphicon-refresh"></i>Reload</button>
        <button id="deleteList" class="btn btn-danger" style="display: none;" onclick="deleteList()"><i class="glyphicon glyphicon-trash"></i>Delete list</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Name</th>
                    <th>Email-Id</th>
                    <th>Contact</th>
                    <th>Designation</th>
                    <th style="width:200px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <script src="<?php echo base_url('assets/jquery/jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';
$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "index.php/employee/ajax_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            {
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },

        ],


    });
    
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "bottom auto",
        todayBtn: true,
        
    });
    
    
    //set input/textarea/select event when change value, remove class error and remove text help block
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
        showBottomDelete();
    });

    

});

function showBottomDelete()
{
  var total = 0;

  $('.data-check').each(function()
  {
     total+= $(this).prop('checked');
  });

  if (total > 0)
      $('#deleteList').show();
  else
      $('#deleteList').hide();
}

function addEmployee()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Employee'); // Set Title to Bootstrap modal title]
    
    $('#photo-preview').hide(); // hide photo preview modal
 
    $('#label-photo').text('Upload Photo'); // label photo upload
}

function editEmployee(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "index.php/employee/ajax_edit/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="emp_id"]').val(data.emp_id);
            $('[name="name"]').val(data.emp_name);
            $('[name="email"]').val(data.email);
            $('[name="gender"]').val(data.gender);
            $('[name="designation"]').val(data.designation_id);
            $('[name="contact"]').val(data.contact);
            $('[name="address"]').val(data.address);
            $('[name="dob"]').val(data.dob);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Employee'); // Set title to Bootstrap modal title
            
            $('#photo-preview').show(); // show photo preview modal
 
            if(data.emp_image)
            {
                $('#label-photo').text('Change Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'upload/'+data.emp_image+'" class="img-responsive">'); // show photo 
            }
            else
            {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }
            
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error getting data from ajax');
        }
    });
}

function showEmpInfo(id)
{
    //Ajax Load data from ajax
    $.ajax({
        url : "index.php/employee/ajax_edit/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#myModal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text(data.emp_name.toUpperCase()+" 'S Info"); // Set title to Bootstrap modal title
            $('#name').text(data.emp_name.toUpperCase());
            $('#email').text(data.email);
            $('#contact').text(data.contact);
            $('#gender').text(data.gender.toUpperCase());
            $('#dob').text(data.dob);
            $('#image').html('<img src="'+base_url+'upload/'+data.emp_image+'" class="outimg1 img-responsive">'); // show photo
            $('#address').text(data.address);
            $('#designation').text(data.designation_name.toUpperCase());
            // show photo preview modal
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error getting data from ajax');
        }
    });
}

function reloadTable()
{
    table.ajax.reload(null,false); //reload datatable ajax
    $('#deleteList').hide();
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "index.php/employee/ajax_add";
    } else {
        url = "index.php/employee/ajax_update";
    }
    var formData = new FormData($('#form')[0]);
    // ajax adding data to database
        $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reloadTable();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error Adding/Updating');
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function deleteEmployee(id)
{
    if(confirm('Are you sure to remove the student?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "index.php/employee/ajax_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reloadTable();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function deleteList()
{
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "index.php/employee/ajax_list_delete",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reloadTable();
                    }
                    else
                    {
                        alert('Failed.');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header mtitlecolor">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center">Form student</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                      <div class="form-group">
                          <div class="col-md-9">
                              <input name="emp_id" class="form-control" type="hidden">
                              <span class="help-block"></span>
                          </div>
                      </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Employee Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Employee name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Email-Id</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date Of Birth</label>
                            <div class="col-md-9">
                                <input name="dob" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Designation</label>
                            <div class="col-md-9">
                                <select name="designation" class="form-control">
                                    <option value="">--Select Designation--</option>
                                    <?php
                                    foreach($list as $value){
                                      echo '<option value="'.$value->designation_id.'">'.$value->designation_name.'</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Contact</label>
                            <div class="col-md-9">
                                <input name="contact" placeholder="Contact" class="form-control" type="text" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9 outimg">
                                (No photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Employee Image</label>
                            <div class="col-md-9">
                                <input type="file" name="image" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Address</label>
                            <div class="col-md-9">
                                <textarea name="address" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" style="margin-top: -20px;">
   <div class="modal-dialog modal-md">
     <div class="modal-content">
       <div class="modal-header infotitle">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         <h4 class="modal-title text-center infotitle" id="myModalLabel">Phone Details</h4>
       </div>
       <div class="modal-body">
        <!-- Place to print the fetched phone -->
         <div id=""></div>
         <div class="row">
             
         <div class="col-lg-12 col-md-12 col-sm-12">
             <div class="col-lg-5 col-sm-12 col-md-2 namesize">
             <label>Employee Name </label>
                            
             </div>
             <div class="col-lg-1 col-sm-12 col-md-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6 col-sm-3 col-md-1">
             
             <div class="namesize" id="name">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>
             <br/><br/>   
         <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Email-Id </label>
                            
             </div>
             <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="email">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>
             
             <br/><br/>
          <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Contact </label>
                            
             </div>
              <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="contact">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>
             
             <br/><br/>
         <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Gender </label>
                            
             </div>
             <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="gender">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>
           <br/><br/>  
             
         <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Date Of Birth </label>
                            
             </div>
             <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="dob">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>
             <br/><br/>
         <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Designation </label>
                            
             </div>
             <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="designation">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div> 
             <br/><br/>
         <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Employee Image </label>
                            
             </div>
             <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6 outimg">
                 <div class="namesize outimg1" id="image">
                 <span class="help-block"></span>
                 </div>
             
             </div>
         </div>
            
          <div class="col-lg-12">
             <div class="col-lg-5 namesize">
             <label>Address </label>
                            
             </div>
              <div class="col-lg-1 namesize">
             <label>:</label>
                            
             </div>
             <div class="col-lg-6">
             
             <div class="namesize" id="address">
             <span class="help-block"></span>
             </div>    
             
             </div>
         </div>   
             <br/><br/>
       </div>
         </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>
   </div>
 </div>


</body>
</html>
