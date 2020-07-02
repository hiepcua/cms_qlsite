<?php
// CRERATE BY TUYENNX-nxtuyen.pro@gmail.com
// AUTHOR SITE igf.com.vn
class CLS_MAILER {
	var $pro=array(
		"FROM"=>"",
		"TO"=>"",
		"CC"=>"",
		"BCC"=>"",
		"HEADER"=>"",
		"SUBJECT"=>"",
		"CONTENT"=>"",
		"SMTP_MAIL"=>"",
		"SMTP_SERVER"=>"mail.igf.com.vn",
		"SMTP_PORT"=>"25",
		"SMTP_USER"=>"",
		"SMTP_PASS"=>"",
		"time"=>""
		);
	var $numrows=0;
	// property set value
	function CLS_MAILER() {
        //$this->time=date("Y-m-d h:i:s");
	}
	function __set($proname,$value){
		if(!isset($this->pro[$proname])){
			echo "Error";
			return;
		}
		$this->pro[$proname]=$value;
	}
	function __get($proname){
		if(!isset($this->pro[$proname])){
			echo "Error";
			return;
		}
		return $this->pro[$proname];
	}
	function getSMTP(){
		$sql="SELECT * FROM `gf_config_mail` WHERE id=1";
		echo $sql;
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
		
		$type='phpmail';
		if($objdata->Num_rows()>0) {
			$r = $objdata->FetchArray();
			if($r["type"]=="smtp") {
				$this->pro["SMTP_SERVER"] = $r["smtp_server"];
				$this->pro["SMTP_PORT"] = $r["smtp_port"];
				$this->pro["SMTP_USER"] = base64_encode($r["smtp_user"]);
				$this->pro["SMTP_PASS"] = base64_encode($r["smtp_pass"]);
				$this->pro["SMTP_MAIL"] = $r["email"];
			}
			$type=$r["type"];
			return $type;
		}
		return $type;
	}
	function sendMail_SMTP () {
		if ($this->pro["SMTP_PORT"] == ""){
			$this->pro["SMTP_PORT"] = 25;
		}
		if ($SMTPIN = fsockopen ($this->pro["SMTP_SERVER"], $this->pro["SMTP_PORT"])){
			fputs ($SMTPIN, "EHLO ".$HTTP_HOST."\r\n");
			$talk["hello"] = fgets ( $SMTPIN, 1024 );
			fputs($SMTPIN, "auth login\r\n");
			$talk["res"]=fgets($SMTPIN,1024);
			fputs($SMTPIN, $this->pro["SMTP_USER"]."\r\n");
			$talk["user"]=fgets($SMTPIN,1024);
			fputs($SMTPIN, $this->pro["SMTP_PASS"]."\r\n");
			$talk["pass"]=fgets($SMTPIN,256);
			fputs ($SMTPIN, "MAIL FROM: <".$this->pro["SMTP_MAIL"].">\r\n");
			$talk["From"] = fgets ( $SMTPIN, 1024 );
			fputs ($SMTPIN, "RCPT TO: <".$this->pro["SMTP_MAIL"].">\r\n");
			$talk["To"] = fgets ($SMTPIN, 1024);
			fputs($SMTPIN, "DATA\r\n");
			$talk["data"]=fgets( $SMTPIN,1024 );
			fputs($SMTPIN, "To: <".$this->pro["TO"].">\r\nFrom: <".$this->pro["FROM"].">\r\nSubject:".$this->pro["SUBJECT"]."\r\n\r\n\r\n".$this->pro["CONTENT"]."\r\n.\r\n");
			$talk["send"]=fgets($SMTPIN,256);
			//CLOSE CONNECTION AND EXIT ...
			fputs ($SMTPIN, "QUIT\r\n");
			fclose($SMTPIN);
		}
		return $talk; 
	}
	function EmailServer () {
		$sql="SELECT `email` FROM `gf_config_mail` WHERE id=1";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
		
		if($objdata->Num_rows()>0) {
			$r = $objdata->FetchArray();
			return $r["email"];
		}
		return '';
	}
	
	function sendMail_PHPmail() {
		ini_set("SMTP",$this->pro["SMTP_SERVER"]);
		ini_set("smtp_port",80);
		ini_set("sendmail_from", $this->pro["SMTP_MAIL"]);
		mail($this->pro["TO"],$this->pro["SUBJECT"],$this->pro["CONTENT"],$this->pro["HEADER"]);
	}
	function SendMail(){
		if($this->getSMTP()=="phpmail") {
			$this->sendMail_PHPmail();
		}
		else {
			$this->sendMail_SMTP();
		}
	}
	function listMail() {
		$sql="SELECT * FROM `tbl_mail` WHERE `to`='".$_SESSION["MEMLOGIN"]."' ORDER BY `mail_id` DESC";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
		$this->numrows=$objdata->Num_rows();
		return $objdata;
	}

	function getMail($id) {
		$sql="SELECT * FROM `tbl_mail` WHERE `to`='".$_SESSION["MEMLOGIN"]."' AND `mail_id`='".$id."'";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
		return $objdata;
	}
	function Update($id) {
		$sql="UPDATE `tbl_mail` SET `isactive`=0 where `mail_id`='".$id."'";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
	}
	function Add_new($tieude,$noidung,$nguoinhan) {
		$sql="INSERT INTO `tbl_mail`(`mail_id`,`subject`,`body`,`isactive`,`to`,`time`) VALUES ( NULL,'".mysql_escape_string($tieude)."','".mysql_escape_string($noidung)."','1','".$nguoinhan."', '".$this->time."')";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
	}
	function MailUnread() {
		$sql="SELECT * FROM `tbl_mail` WHERE `to`='".$_SESSION["MEMLOGIN"]."' and `isactive`=1";
		$objdata= new CLS_MYSQL();
		$objdata->Query($sql);
		return $objdata->Num_rows();
	}
	function Numrows() {
		return $this->numrows;
	}
    public function send_email_html($to, $from, $subject, $html) {
        preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si',$html,$matches);
        $i = 0;
        $paths = array();
        foreach ($matches[1] as $img) {
            $img_old = $img;
            if(strpos($img, "http://") == false) {
                $uri = parse_url($img);
                $paths[$i]['path'] = $_SERVER['DOCUMENT_ROOT'].$uri['path'];
                $content_id = md5($img);
                $html = str_replace($img_old,'cid:'.$content_id,$html);
                $paths[$i++]['cid'] = $content_id;
            }
        }
        $uniqid   = md5(uniqid(time()));
        $boundary = "--==_mimepart_".$uniqid;

        $headers = "From: ".$from."\n".
            'Reply-to: '.$from."\n".
            'Return-Path: '.$from."\n".
            'Message-ID: <'.$uniqid.'@'.RÔ.">\n".
            'Date: '.gmdate('D, d M Y H:i:s', time())."\n".
            'Mime-Version: 1.0'."\n".
            'Content-Type: multipart/related;'."\n".
            '  boundary='.$boundary.";\n".
            '  charset=UTF-8'."\n".
            'X-Mailer: PHP/' . phpversion();

        $multipart = '';
        $multipart .= "--$boundary\n";
        $kod = 'UTF-8';
        $multipart .= "Content-Type: text/html; charset=$kod\n";
        $multipart .= "Content-Transfer-Encoding: 7-bit\n\n";
        $multipart .= "$html\n\n";
        foreach ($paths as $path) {
            if (file_exists($path['path']))
                $fp = fopen($path['path'],"r");
            if (!$fp)  {
                return false;
            }
            $imagetype = substr(strrchr($path['path'], '.' ),1);
            $file = fread($fp, filesize($path['path']));
            fclose($fp);
            $message_part = "";
            switch ($imagetype) {
                case 'png':
                case 'PNG':
                    $message_part .= "Content-Type: image/png";
                    break;
                case 'jpg':
                case 'jpeg':
                case 'JPG':
                case 'JPEG':
                    $message_part .= "Content-Type: image/jpeg";
                    break;
                case 'gif':
                case 'GIF':
                    $message_part .= "Content-Type: image/gif";
                    break;
            }
            $message_part .= "; file_name = \"$path\"\n";
            $message_part .= 'Content-ID: <'.$path['cid'].">\n";
            $message_part .= "Content-Transfer-Encoding: base64\n";
            $message_part .= "Content-Disposition: inline; filename = \"".basename($path['path'])."\"\n\n";
            $message_part .= chunk_split(base64_encode($file))."\n";
            $multipart .= "--$boundary\n".$message_part."\n";
        }
        $multipart .= "--$boundary--\n";
        mail($to, $subject, $multipart, $headers);
    }
}

?>