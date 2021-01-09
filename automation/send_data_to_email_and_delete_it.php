<?php
namespace automation;
define('DS',DIRECTORY_SEPARATOR);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor'.DS.'autoload.php';
class send_data_to_email_and_delete_it
{
    private $archive_model;
    private $data;
    private $file_path = '..'.DS.'app'.DS.'api'.DS.'data_for_mail.json';
    public function __construct()
    {
        $this->prepare_tools();
        $this->prepare_date();
        $this->put_data_in_file_json();
        if($this->send_file_to_mail()){
            $this->empty_file_data();
            $this->delete_data();
        }
    }
    private function prepare_tools()
    {
        define('APP_PATH',realpath(dirname(__FILE__)).DS.'..'.DS.'app'.DS);
        require '..'.DS.'app'.DS.'lib'.DS.'vendor'.DS.'autoloader.php';
        require '..'.DS.'app'.DS.'config'.DS.'db_config.php';
        new \lib\database\db_connection;
        $this->archive_model = new \models\public_models\archive_model;
    }
    private function prepare_date()
    {
        $this->data = $this->archive_model->getWCond('DATEDIFF(CURRENT_DATE(),request_date) >= 90');
    }
    private function put_data_in_file_json()
    {
        if(!empty($this->data)){
            $f_content = json_decode(file_get_contents($this->file_path),JSON_UNESCAPED_UNICODE);
            foreach($this->data as $data){
                $data_arr = json_decode($data['order_details'],JSON_UNESCAPED_UNICODE);
                $f_content['id'][] = $data_arr['id'];
                $f_content['full_name'][] = $data_arr['full_name'];
                $f_content['government_name'][] = $data_arr['government_name'];
                $f_content['address'][] = $data_arr['address'];
                $f_content['phone_number'][] = $data_arr['phone_number'];
                $f_content['inventory_name'][] = $data_arr['inventory_name'];
                $f_content['request_date'][] = $data_arr['request_date'];
                $f_content['delivery_date'][] = $data_arr['delivery_date'];
                file_put_contents($this->file_path,json_encode($f_content,JSON_UNESCAPED_UNICODE));
            }
        }else{
            exit();
        }
    }
    private function send_file_to_mail()
    {
        $mail = new PHPMailer();
        try {
            echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
            //Server settings
            $my_email = 'aled14123@gmail.com';
            $pass = '@AHmed01024404534';
            $mail->SMTPDebug  = 4;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 587;
            $mail->SMTPAuth   = true;
            $mail->Username   = $my_email;
            $mail->Password   = $pass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        
            // Attachments
            $mail->addAttachment($this->file_path, 'Data.json');
        
            // Content
            $mail->isHTML(true);
            $mail->From = $my_email;
            $mail->FromName = $my_email;
            $mail->addAddress('aled14123@gmail.com');
            $mail->addReplyTo('no-reply-'.$my_email);
            $mail->Subject = 'Clients Data';
            $mail->Body    = '<b>يرجى تحميل الملف والذهاب الى هذا <a href="https://www.convertcsv.com/json-to-csv.htm">الموقع</a> لتحويل الملف لملف آكسل</b>';
            $mail->AltBody = 'Clients Data';
        
            if($mail->send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
    private function empty_file_data()
    {
        $f_content = json_decode(file_get_contents($this->file_path),JSON_UNESCAPED_UNICODE);
        $f_content['id'] = [];
        $f_content['full_name'] = [];
        $f_content['government_name'] = [];
        $f_content['address'] = [];
        $f_content['phone_number'] = [];
        $f_content['inventory_name'] = [];
        $f_content['request_date'] = [];
        $f_content['delivery_date'] = [];
        file_put_contents($this->file_path,json_encode($f_content,JSON_UNESCAPED_UNICODE));
    }
    private function delete_data()
    {
        $this->archive_model->delete_with_cond('DATEDIFF(CURRENT_DATE(),request_date) >= 90');
    }
}
new send_data_to_email_and_delete_it;