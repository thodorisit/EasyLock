<?php
    class EasyLock {
        
        private $passwords;
        private $banIPs;
        private $byPassIPs;
        private $timeout;
        private $failLoginAttempts;
        private $templateDesign;
        
        public function __construct() {
            session_start();
            $this->passwords = array();
            $this->banIPs = array();
            $this->byPassIPs = array();
            $this->timeout = 7 * 24 * 60 * 60;
            $this->failLoginAttempts = -1;
            $this->templateDesign = array();
        }
        function lock() {
            if ( ( (isset($_SESSION["logedin"])) && (isset($_GET["logout"])) ) || ( !self::checkTimeout() ) ) {
                self::logout();
                self::refreshPage();
            }
            if (self::checkIPbyPass()) {
                if ( !isset($_SESSION["logedin"]) && $_SESSION["logedin"] != 1 ) {
                    $_SESSION["logedin"] = 1;
                    self::refreshPage();
                }
            }
            if ( isset($_POST['easyLockSubmit']) ) {
                $_SESSION["password"] = $_POST["easyLockPassword"];
                $_SESSION["timeout"] = time();
                
                $checkPassword      = self::checkPassword();
                $checkTimeout       = self::checkTimeout();
                $checkIPban         = self::checkIPban();
                $checkLoginAttempts = self::checkLoginAttempts();
                if ($checkPassword && $checkTimeout && $checkIPban && $checkLoginAttempts) {
                    if ( !isset($_SESSION["logedin"]) && $_SESSION["logedin"] != 1 ) {
                        $_SESSION["logedin"] = 1;
                        self::refreshPage();
                    }
                } else {
                    if ( isset($_SESSION["loginAttempts"]) ) {
                        $loginAttemptsTemp = $_SESSION["loginAttempts"];
                    }
                    self::logout();
                    if ( !empty($loginAttemptsTemp) ) {
                        $_SESSION["loginAttempts"] = $loginAttemptsTemp;
                    }
                    echo self::loginTemplate1();
                    exit();
                }
            
            }
            
            if ( !isset($_SESSION["logedin"]) ) {
                echo self::loginTemplate1();
                exit();
            }
            
        }
        
        function checkPassword() {
            if ( (isset($_SESSION["password"])) && (in_array($_SESSION["password"], $this->passwords)) ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        function checkTimeout() {
            if ( isset($_SESSION["timeout"]) ) {
                $timeDiff = time() - $_SESSION["timeout"];
                if ( $timeDiff <= $this->timeout ) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return TRUE;
            }
        }
        
        function checkIPban() {
            if ( (count($this->banIPs) > 0) && (in_array(self::userIP(), $this->banIPs)) ) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        
        function checkIPbyPass() {
            if ( (count($this->byPassIPs) > 0) && (in_array(self::userIP(), $this->byPassIPs)) ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        function checkLoginAttempts() {
            if ( ($this->failLoginAttempts != -1) && (!isset($_SESSION["logedin"])) ) {
                if ( !isset($_SESSION["loginAttempts"]) ) {
                    $loginAttemptsTemp = 1;
                } else {
                    $loginAttemptsTemp = $_SESSION["loginAttempts"] + 1;
                }
                if ( ($loginAttemptsTemp - 1) > $this->failLoginAttempts ) {
                    $_SESSION["loginAttempts"] = $loginAttemptsTemp;
                    return FALSE;
                } else {
                    $_SESSION["loginAttempts"] = $loginAttemptsTemp;
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        }
        
        function setPasswords($passwords = array()) {
            $this->passwords = $passwords;
            return $this;
        }
        
        function setBanIPs($ips = array()) {
            $this->banIPs = $ips;
            return $this;
        }
        
        function setByPassIPs($ips = array()) {
            $this->byPassIPs = $ips;
            return $this;
        }
        
        function setFailLoginLimit($number) {
            $this->failLoginAttempts = $number;
            return $this;
        }
        
        function setTimeout($number) {
            $this->timeout = $number;  //seconds
            return $this;
        }
        
        function setTemplateDesign($design = array()) {
            $this->templateDesign = $design;
            return $this;
        }
        
        function logout() {
            session_destroy();
            session_start();
        }
        
        function userIP() {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = '';
            return $ipaddress;
        }
        
        function refreshPage() {
            unset($_POST);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
        
        function loginTemplate1() {
            $textFormLabel = 'Login Form';
            $textInputPlaceholder = 'Insert Password';
            $textLoginButton = 'LOGIN';
            $bodyBackgroundColor = '#b33939';
            $formBackgroundColor = '#FFFFFF';
            $formBorderRadius = '1px;';
            $formShadow = '0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24)';
            $loginButtonBgColorNormal = '#ff5252';
            $loginButtonBgColorAction = '#b33939';
            $inputLabelColor = '#808080';
            $inputFormBackgroundColor = '#f2f2f2';
            $inputFormColor = '#0A0A0A';
            
            //Overwrite design
            foreach($this->templateDesign AS $key => $value) {
                ${$key} = $value;
            }
            
            $template = 
                    '<html>
                        <head>
                            <title>This page is locked.</title>
                            <style>
                                @import url(https://fonts.googleapis.com/css?family=Roboto:300);
                                .login-page {
                                    width: 360px;
                                    padding: 8% 0 0;
                                    margin: auto;
                                }
                                .form {
                                    position: relative;
                                    z-index: 1;
                                    background: '.$formBackgroundColor.';
                                    max-width: 360px;
                                    margin: 0 auto 100px;
                                    padding: 45px;
                                    text-align: center;
                                    box-shadow: '.$formShadow.';
                                    border-radius: '.$formBorderRadius.';
                                }
                                .form input {
                                    font-family: "Roboto", sans-serif;
                                    outline: 0;
                                    background: '.$inputFormBackgroundColor.';
                                    width: 100%;
                                    border: 0;
                                    margin: 0 0 15px;
                                    padding: 15px;
                                    box-sizing: border-box;
                                    font-size: 14px;
                                    color: '.$inputFormColor.';
                                }
                                .form button {
                                    font-family: "Roboto", sans-serif;
                                    outline: 0;
                                    background: '.$loginButtonBgColorNormal.';
                                    width: 100%;
                                    border: 0;
                                    padding: 15px;
                                    color: #FFFFFF;
                                    font-size: 14px;
                                    -webkit-transition: all 0.3 ease;
                                    transition: all 0.3 ease;
                                    cursor: pointer;
                                }
                                .form button:hover,.form button:active,.form button:focus {
                                    background: '.$loginButtonBgColorAction.';
                                }
                                .input-label {
                                    display:block;
                                    position:relative;
                                    padding-bottom:10px;
                                    color: '.$inputLabelColor.';
                                }
                                body {
                                    background: '.$bodyBackgroundColor.';
                                    font-family: "Roboto", sans-serif;  
                                }
                            </style>
                        </head>
                        <body>
                            <div class="login-page">
                                <div class="form">
                                    <form class="login-form" method="post">
                                        <label class="input-label">'.$textFormLabel.'</label>
                                        <input type="password" name="easyLockPassword" placeholder="'.$textInputPlaceholder.'"/>
                                        <button name="easyLockSubmit" type="submit">'.$textLoginButton.'</button>
                                    </form>
                                </div>
                            </div>
                        </body>
                    </html>';
            return $template;
        }
        
    }
?>
