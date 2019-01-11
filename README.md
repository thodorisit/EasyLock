![My image](https://thodorisit.github.io/EasyLock/img/easyLock.png)
# EasyLock
 A simple way to secure with password your webpage with just two lines of code.

# Instructions

Include class
```
require "easylock.class.php";
```
Call class and settings functions (features below). 
```
(new EasyLock)->setPasswords(array('password1','password2'))->setFailLoginLimit(10)
```
In the end of each call, call lock function.
```
->lock();
```
# Examples
```
require 'easylock.class.php';
(new EasyLock)->setPasswords(array('password1','password2'))->lock();
```
```
require 'easylock.class.php';
(new EasyLock)->setPasswords(array('password1','password2'))
              ->setFailLoginLimit(10)
              ->setTimeout(500)
              ->setBanIPs(array('ip1', 'ip2'))
              ->setByPassIPs(array('ip1', 'ip2', 'ip3'))
              ->setTemplateDesign(array(
                  'textFormLabel'            => 'New Form Label',
                  'textInputPlaceholder'     => 'New Password Placeholder',
                  'textLoginButton'          => 'New LOGIN Button',
                  'bodyBackgroundColor'      => '#ededed',
                  'formBackgroundColor'      => 'green',
                  'formBorderRadius'         => '10px',
                  'formShadow'               => '5px 10px #888888',
                  'loginButtonBgColorNormal' => 'red',
                  'loginButtonBgColorAction' => 'blue',
                  'inputLabelColor'          => 'blue',
                  'inputFormBackgroundColor' => 'yellow',
                  'inputFormColor'           => '#0A0A0A'
              ))
              ->lock();
```

# Features
This class is capable of even preparing you a coffee.
- Set multiple passwords
```
->setPasswords(array('pass1','pass2', 'pass3', 'pass4'))
```
- Set fail login limit
```
->setFailLoginLimit(10)
```
- Set time limit for logged in users (in seconds)
```
->setTimeout(500)
```
- Block access to specific IPs
```
->setBanIPs(array('ip1', 'ip2'))
```
- Allow access without password for specific IPs
```
->setByPassIPs(array('ip1', 'ip2', 'ip3'))
```
- Change login template
```
->setTemplateDesign(array(
      'textFormLabel'            => 'New Form Label',
      'textInputPlaceholder'     => 'New Password Placeholder',
      'textLoginButton'          => 'New LOGIN Button',
      'bodyBackgroundColor'      => '#ededed',
      'formBackgroundColor'      => 'green',
      'formBorderRadius'         => '10px',
      'formShadow'               => '5px 10px #888888',
      'loginButtonBgColorNormal' => 'red',
      'loginButtonBgColorAction' => 'blue',
      'inputLabelColor'          => 'blue',
      'inputFormBackgroundColor' => 'yellow',
      'inputFormColor'           => '#0A0A0A'
  ))
```

# Logout
In order to logout, redirect to [youpage]?logout=1
# IMPORTANT
Keep in  mind, this class use session to store plain information such as password.
