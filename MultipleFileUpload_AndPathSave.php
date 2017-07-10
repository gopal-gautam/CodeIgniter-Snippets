$input1 = $this->input->post('input1'); //Input field value
$input2 = $this->input->post('input2'); //Input field value
$photo1 = ''; //File field value
$photo2 = ''; //File field value

$config['upload_path'] = './photo_gallery/';
$config['allowed_types'] = 'gif|jpg|png';
$config['max_size']    = '';
$this->load->library('upload', $config);

foreach($_FILES as $key=>$file) {
	//$key will be the field name
	$out_dir = "./photo_gallery/$key";
	if(!file_exists($out_dir)) {
		mkdir($out_dir, '0777', true);
	}
	if ( ! $this->upload->do_upload($key))
	{
		$error = array('error' => $this->upload->display_errors());
	}
	else
	{
		$data = array('upload_data' => $this->upload->data());
		$fileName = $data['upload_data']['file_name'];
		$newName = sha1_file("./photo_gallery/$fileName") . "." . pathinfo("./photo_gallery/$fileName", PATHINFO_EXTENSION);
		rename("./photo_gallery/$fileName", "$out_dir/$newName");
		$$key = "$out_dir/$newName";    //This will assign values for $photo1 and $photo2 with new paths
	}              
}

$data_to_insert = array(
	'input1' => $input1,
	'input2' => $input2,
	'photo1' => $photo1, //Updated with new file path
	'photo2' => $photo2, //Updated with new file path
);

$success = $this->db->insert('my_table', $data_to_insert);
return $this->db->insert_id();
