<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/kolkata");
class Employee extends CI_Controller{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('EmployeeModel');
      $this->load->model('DesignationModel');
  }

  public function index()
  {
      $this->load->helper('url');
      $data['list'] = $this->DesignationModel->get_rows();
      $this->load->view('employee/index', $data);
  }

  public function ajax_list()
  {
      $list = $this->EmployeeModel->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $employee) {
          $no++;
          $row = array();
          $row[] = '<input type="checkbox" class="data-check" value="'.$employee->emp_id.'" onclick="showBottomDelete()"/>';
          $row[] = $employee->emp_name;
          $row[] = $employee->email;
          $row[] = $employee->contact;
          $row[] = $employee->designation_name;
          
          $row[] = '<a class="btn btn-sm btn-warning" href="javascript:void()" title="Show" data-toggle="modal" data-target="#myModal" onclick="showEmpInfo('."'".$employee->emp_id."'".')"><i class="glyphicon glyphicon-eye-open"></i> Show</a>
                   <a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="editEmployee('."'".$employee->emp_id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="deleteEmployee('."'".$employee->emp_id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
          $data[] = $row;
      }
      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->EmployeeModel->count_all(),
                      "recordsFiltered" => $this->EmployeeModel->count_filtered(),
                      "data" => $data,
              );

      echo json_encode($output);
  }

  public function ajax_edit($id)
  {
      $data = $this->EmployeeModel->get_by_id($id);
      echo json_encode($data);
  }

  public function ajax_add()
  {
      $datetime = date('Y-m-d H:i:s');
      $this->_validate();
      
      $data = array(
              'emp_name' => $this->input->post('name'),
              'email' => $this->input->post('email'),
              'gender'=>$this->input->post('gender'),
              'dob'=> $this->input->post('dob'),
              'contact' => $this->input->post('contact'),
              'designation_id' => $this->input->post('designation'),
              'address' => $this->input->post('address'),
              'datetime'=>$datetime
          );
      
      if(!empty($_FILES['image']['name']))
      {
          $upload = $this->do_upload();
          $data['emp_image'] = $upload;
      }
      
      $insert = $this->EmployeeModel->save($data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_update()
  {
      $upd_datetime = date('Y-m-d H:i:s');
      $this->_validate();
      $data = array(
              'emp_name' => $this->input->post('name'),
              'email' => $this->input->post('email'),
              'gender'=>$this->input->post('gender'),
              'dob'=> $this->input->post('dob'),
              'contact' => $this->input->post('contact'),
              'designation_id' => $this->input->post('designation'),
              'address' => $this->input->post('address'),
              'upd_datetime'=>$upd_datetime
          );
      
      if(!empty($_FILES['image']['name']))
      {
          $upload = $this->do_upload();
          $data['emp_image'] = $upload;
      }
      
      $this->EmployeeModel->update(array('emp_id' => $this->input->post('emp_id')), $data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_delete($id)
  {
      $this->EmployeeModel->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_list_delete()
   {
       $list_id = $this->input->post('id');
       foreach ($list_id as $id) {
           $this->EmployeeModel->delete_by_id($id);
       }
       echo json_encode(array("status" => TRUE));
   }

  private function _validate()
  {
      $data = array();
      $data['error_string'] = array();
      $data['inputerror'] = array();
      $data['status'] = TRUE;

      if($this->input->post('name') == '')
      {
          $data['inputerror'][] = 'name';
          $data['error_string'][] = 'Name is required';
          $data['status'] = FALSE;
      }else{

        if(!$this->_validate_string($this->input->post('name')))
        {
          $data['inputerror'][] = 'name';
          $data['error_string'][] = 'Invalid value';
          $data['status'] = FALSE;
        }

      }

      if($this->input->post('email') == '')
      {
          $data['inputerror'][] = 'email';
          $data['error_string'][] = 'Email is required';
          $data['status'] = FALSE;
      }
      else{

        if(!$this->_check_email($this->input->post('email')))
        {
          $data['inputerror'][] = 'email';
          $data['error_string'][] = 'Enter correct email address';
          $data['status'] = FALSE;
        }

      }

      if($this->input->post('contact') == '')
      {
          $data['inputerror'][] = 'contact';
          $data['error_string'][] = 'Contact is required';
          $data['status'] = FALSE;
      }else{

        if(!$this->_validate_number($this->input->post('contact')))
        {
          $data['inputerror'][] = 'contact';
          $data['error_string'][] = 'Invalid value';
          $data['status'] = FALSE;
        }
      }
      
      if($this->input->post('address') == '')
      {
          $data['inputerror'][] = 'address';
          $data['error_string'][] = 'Address is required';
          $data['status'] = FALSE;
      }
      
      if($this->input->post('dob') == '')
      {
          $data['inputerror'][] = 'dob';
          $data['error_string'][] = 'You should select a dob ';
          $data['status'] = FALSE;
      }
      
      if($this->input->post('gender') == '')
      {
          $data['inputerror'][] = 'gender';
          $data['error_string'][] = 'You should select a gender ';
          $data['status'] = FALSE;
      }
      
      if(empty($_FILES['image']['name']))
      {
          $data['inputerror'][] = 'image';
          $data['error_string'][] = 'You should select a image ';
          $data['status'] = FALSE;
      }
      
      if($this->input->post('designation') == '')
      {
          $data['inputerror'][] = 'designation';
          $data['error_string'][] = 'You should select a designation ';
          $data['status'] = FALSE;
      }

      if($data['status'] === FALSE)
      {
          echo json_encode($data);
          exit();
      }
  }

  private function _validate_string($string)
  {
      $allowed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz ";
      for ($i=0; $i<strlen($string); $i++)
      {
          if (strpos($allowed, substr($string,$i,1))===FALSE)
          {
              return FALSE;
          }
      }

     return TRUE;
  }
  
  private function _check_email($string)
  {
      $echeck = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@.com";
     
      for ($i=0; $i<strlen($string); $i++)
      {
          if (strpos($echeck, substr($string,$i,1))===FALSE)
          {
              return FALSE;
          }
      }
      if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
      return FALSE;
    }

     return TRUE;
  }

  private function _validate_number($string)
  {
      $allowed = "0123456789";
      for ($i=0; $i<strlen($string); $i++)
      {
          if (strpos($allowed, substr($string,$i,1))===FALSE)
          {
              return FALSE;
          }
      }

     return TRUE;
  }
  
  private function do_upload()
  {
        $config['upload_path']          = 'upload/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 100; //set max size allowed in Kilobyte
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
        
        $this->load->library('upload',$config);
        
        if(!$this->upload->do_upload('image')) //upload and validate
        {
            $data['inputerror'][] = 'image';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
        
  }
}
