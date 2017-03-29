<?php
require_once ("secure_area.php");
class Phpmailer extends Secure_area
{
	function __construct()
	{
		parent::__construct();	
		$this->load->model('transaction');
		$this->load->model('phpemailsender');
	}
	
	function send_trans_message($items_id=-1)
	{	
		$user_email = $this->input->post('user_email');
		$user_mobile_no = $this->input->post('user_mobile_no');
		if($user_mobile_no!='') { echo json_encode(array('success'=>false,'message'=>lang('common_mobile_message_worning'))); die; }
		
		$data['items_info']=$this->transaction->get_info($items_id);
		$data['person_info']=$this->Customer->get_info($data['items_info']->Customer_ID); 
		if($user_email!='')
		{
		  if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ echo json_encode(array('success'=>false,'message'=>lang('common_email_invalid_format'))); die; }
		  //**SEND TRANACTION DETAILS VIA EMAIL FOR SELECTED MEMEBERS**//	
		  $signature = lang('emessage_email_signature');
		  $sucess = $this->phpemailsender->mail_send($user_email, lang('emessage_tranaction_confirmation'), $this->phpemailsender->Transaction_EmailBody($user_email,$items_id,lang('emessage_tranaction_confirmation'),$signature ));
		  if($sucess){echo json_encode(array('success'=>true,'message'=>lang('transaction_send_mail_transaction_details')));die; } 
		}
		echo json_encode(array('success'=>true,'message'=>lang('transaction_send_mail_failed')));
	}	
	

			
}



?>
