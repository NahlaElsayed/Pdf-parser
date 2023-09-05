<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	 var $data = array();

	 function  __construct() {
		parent::__construct();
	
	}


	public function index()
	{
		
		if($this->input->post('pdf_submit')  &&  !empty($_FILES['pdf_upload']['name'])){

			$number_of_files = sizeof($_FILES['pdf_upload']['tmp_name']);
			$files = $_FILES['pdf_upload'];
			for($i=0 ; $i<$number_of_files ; $i++){
				if($_FILES['pdf_upload']['error'][$i] !=0){
					$this->form_validation->set_message('pdf_upload','Some problems occured, please try again.');
					return false;
				}
			}

			$config['upload_path'] = FCPATH.'uploads/';
			$config['allowed_types'] = 'pdf';
			$config['encrypt_name'] =true;

			for($i=0 ; $i<$number_of_files ; $i++ ){

				$_FILES['pdf_upload']['name'] = $files['name'][$i];
				$_FILES['pdf_upload']['type'] = $files['type'][$i];
				$_FILES['pdf_upload']['tmp_name'] = $files['tmp_name'][$i];
				$_FILES['pdf_upload']['error'] = $files['error'][$i];
				$_FILES['pdf_upload']['size'] = $files['size'][$i];

				$this->upload->initialize($config);
				if($this->upload->do_upload('pdf_upload')){
					$data = $this->upload->data();
					chmod($data['full_path'],0777);

					// echo '<pre>';
					// print_r($data);
					// echo '</pre>';

					// insert 
					$insert[$i]['file_name'] = $data['file_name'];
					$insert[$i]['file_size'] = $data['file_size'];

				}	
			}
			$this->db->insert_batch('upload_pdf',$insert);
		}

		$this->data=array(
			'quary'=>$this->db->get('upload_pdf')
		);
		$this->load->view('welcome_message',$this->data);
	}
}
