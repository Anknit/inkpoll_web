<?php
    class mailAccess {
        private $mailConfig, $error, $data, $baseUrl;
        public function __construct () {
            $this->mailConfig = array(
                'smtpAuth' => 'true',
                'smtpHostName'	=>	'mail.veneratech.com',
                'smtpPort'		=>	'25',
                'smtpUsername'	=>	'rajarshi@veneratech.com',
                'smtpPassword'	=>	'#Roger',
                'sender'		=>	'Administrator'
            );
            $this->baseUrl = "http://localhost/feeddasm";
            $this->error = 0;
            $this->data = array();
        }
        public function __destruct () {
            
        }
        public function sendVerificationLink ($recipient, $encryptLink) {
            $mailSubject = 'Account Verification Link from Inkpoll';
            $MailBody = '<html><body><p>Click&nbsp;<a href="'.$this->baseUrl.'/accounts/verify/'.$encryptLink.'">here</a></p></body></html>';
            $config = $this->mailConfig;
            $mailResponse = send_Email($recipient, $mailSubject, $MailBody, '', '', $config);
            if(!$mailResponse) {
                $this->error = 1;
            }
            return array('data'=>$this->data, 'error'=> $this->error);
        }
        public function sendPasswordResetLink ($recipient, $encryptLink) {
            $mailSubject = 'Account Password Reset Link from Inkpoll';
            $MailBody = '<html><body><p>Click&nbsp;<a href="'.$this->baseUrl.'/accounts/reset/'.$encryptLink.'">here</a></p></body></html>';
            $config = $this->mailConfig;
            $mailResponse = send_Email($recipient, $mailSubject, $MailBody, '', '', $config);
            if(!$mailResponse) {
                $this->error = 1;
            }
            return array('data'=>$this->data, 'error'=> $this->error);
        }
        public function sendWelcomeMail ($recipient) {
            $mailSubject = 'Welcome to Inkpopll';
            $MailBody = '<html><body><p>Welcome to &nbsp;<a href="'.$this->baseUrl.'">Inkpoll</a></p></body></html>';
            $config = $this->mailConfig;
            $mailResponse = send_Email( $recipient, $mailSubject, $MailBody, '', '', $config);
            if(!$mailResponse) {
                $this->error = 1;
            }
            return array('data'=>$this->data, 'error'=> $this->error);
        }
    }
?>