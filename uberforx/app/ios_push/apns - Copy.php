<?php

session_start();

//require_once  'database.php';
//error_reporting(false);

$cers = Certificates::where('client','apple')->where('file_type','certificate')->get();
foreach ($cers as $key) {
    if($key->default==1){
        $cert_def = $key->type;
    }else{
        $cert_def = 0;
    }
}


define("DefA", $cert_def);
$certi_user_a = Certificates::where('client','apple')->where('user_type',0)->where('type',$cert_def)->where('file_type','certificate')->first();
define("Certi_Path", $certi_user_a->name);
$pass_user_a = Certificates::where('client','apple')->where('user_type',0)->where('type',$cert_def)->where('file_type','passphrase')->first();
define("PassPH", $pass_user_a->name);


class Apns_old {

    private $passphrase = PassPH;
    private $certificateP = Certi_Path;
    private $defa = DefA;

    public $ctx;
    public $fp;
    private $ssl = 'ssl://gateway.push.apple.com:2195';
    private $sandboxSsl = 'ssl://gateway.sandbox.push.apple.com:2195';
    private $sandboxFeedback = 'ssl://feedback.sandbox.push.apple.com:2196';
    private $message = "ManagerMaster";

    private function getCertificatePath() {
        Log::info('path user = '.print_r(public_path().'/apps/ios_push/iph_cert/'.$this->certificateP,true));
        return public_path().'/apps/ios_push/iph_cert/'.$this->certificateP;        
    }

    public function __construct() {
        $this->initialize_apns();
    }

    public function initialize_apns() {
        try {
            $this->ctx = stream_context_create();

            //stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');
            stream_context_set_option($this->ctx, 'ssl', 'local_cert', $this->getCertificatePath());
            stream_context_set_option($this->ctx, 'ssl', 'passphrase', $this->passphrase); // use this if you are using a passphrase
            // Open a connection to the APNS servers

            if($this->defa==1){
                $this->fp = stream_socket_client($this->ssl, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $this->ctx);
            }else{
                $this->fp = stream_socket_client($this->sandboxSsl, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $this->ctx);
            }

            if ($this->fp) {
                Log::info('Successfully connected to server of APNS');
                //echo 'Successfully connected to server of APNS ckUberForXOwner.pem';
            } else {
                Log::error("Error in connection while trying to connect to APNS");
                //echo "Error in connection while trying to connect to APNS ckUberForXOwner.pem";
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function send_notification($devices, $message) {
        try {
            $errCounter = 0;
            $payload = json_encode(array("aps" => $message));
            $result = 0;
            $bodyError = "";
            foreach ($devices as $key => $value) {
                $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $value)) . pack('n', (strlen($payload))) . $payload;
                $result = fwrite($this->fp, $msg);
                $bodyError .= 'result: ' . $result . ', devicetoken: ' . $value;
                if (!$result) {
                    $errCounter = $errCounter + 1;
                }
            }
			//echo "Result :- ".$result;
            if ($result) {
                Log::info('Delivered Message to APNS' . PHP_EOL);
                //echo 'Delivered Message to APNS' . PHP_EOL;
                $bool_result = true;
            } else {
                Log::error('Could not Deliver Message to APNS' . PHP_EOL);
                //echo 'Could not Deliver Message to APNS' . PHP_EOL;
                $bool_result = false;
            }

            fclose($this->fp);
            return $bool_result;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

}
