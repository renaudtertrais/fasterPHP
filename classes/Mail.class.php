<?php
require_once ("phpmailer/class.phpmailer.php");

class Mail{

	private $_error; 
	private $_to;
	private $_cc;
	private $_cci;
	private $_from;
	private $_subject;
	private $_message;
	private $_attachment;
	private $_attachmentName;
	private $_fromName;

	/* @function constructor of the class
	 * @param (array) $fields 				: fields of the email
	 * - @key (string) $to 					: email address(es) (required)
	 * - @key (string) $cc 					: email address(es) (optional)
	 * - @key (string) $cci 				: email address(es) (optional)
	 * - @key (string) $from 				: email address (required)
	 * - @key (string) $fromName 			: name of the sender (recommended)
	 * - @key (string) $subject 			: name of the subject (required)
	 * - @key (string) $message 			: message (i.e. email content) (required)
	 * - @key (string) $attachement 		: path to a file (optional)
	 * - @key (string) $attachementName 	: name of the attachement file (optional)
	 * @return (Mail) : this object
	 */
	public function __construct($fields = array() ) {
		$fields = array_merge(array( 'to' => '', 'cc' => '' , 'cci' => '' , 'from' => '' , 'message' => '' , 'subject' => '' , 'fromName' => '', 'attachmentName' => '' ) , $fields);
		
		$this->set('to',$fields['to']);
		$this->set('cc',$fields['cc']);		
		$this->set('cci',$fields['cci']);
		$this->set('from',$fields['from']);
		$this->set('fromName',$fields['fromName']);
		$this->set('message',$fields['message']);
		$this->set('subject',$fields['subject']);
		$this->set('attachment',$fields['attachment']);
		$this->set('attachmentName',$fields['attachmentName']);

		return self;
	}

	/* @function get a field from the Mail object
	 * @param (string) $field : field of the object
	 * @return (string) : field value
	 */
	public function get($field){
		if($field == 'to' || $field == 'cc' || $field == 'cci' || $field == 'from' || $field == 'message' || $field == 'subject' || $field == 'fromName'){
			return $this->{'_'.$field};
		}
	}

	/* @function set a new value for the a field of the Mail object
	 * @param (string) $field 	: field name
	 * @param (string) $content : new value
	 */
	public function set($field , $content){
		if($field == 'to' || $field == 'cc' || $field == 'cci'){
			if(is_array($content)){
				$this->{'_'.$field} = implode(',', $content);
			}else{
				$this->{'_'.$field} = $content;
			}
		 }else if($field == 'from' || $field == 'message' || $field == 'subject' || $field == 'attachment' || $field == 'fromName' || $field == 'attachmentName'){
			$this->{'_'.$field} = $content;
		}
	}

	/* @function send the mail
	 * @return (bool) : TRUE = no error, FALSE : error
	 */
	public function send(){  
        $mail = new PHPmailer();
        $mail->IsSMTP();        
        $mail->Host='localhost'; 
        $mail->Port = 25;	
        $mail->IsHTML(true); 
        $mail->CharSet = 'UTF-8';
        $mail->From = $this->_from; 
        $mail->FromName = $this->_fromName;
        $mail->AddAddress($this->_to); 
 
        if( !empty($this->_cc) ) 	$mail->AddCC($this->_cc);
        if( !empty($this->_cci) ) 	$mail->AddBCC($this->_cci);

        $mail->Subject=$this->_subject;
        $mail->Body=$this->_message; 
       

        if(!empty($this->_attachment)) $mail->AddAttachment($this->_attachment,$this->_attachmentName); 

        
        $retour = $mail->Send();

        $mail->SmtpClose();
        unset($mail); 

        return $retour;
    }
} 

?>