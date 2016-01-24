<?php

class InstallerController extends \BaseController {

    public function install() {
        if (Request::isMethod('post')) {
            $step = Session::get('step');
            $inputs = Input::all();
            foreach ($inputs as $key => $value) {
                Session::put($key, $value);
            }
            if (Input::exists('back'))
                $step--;
            else
                $step++;
            Session::put('step', $step);
            if ($step < 7) {
                return Redirect::to("/install?step=" . $step);
            } else {
                return Redirect::to("/install/complete");
            }
        } else {
            $step = Input::get('step') ? Input::get('step') : 1;
            Session::put('step', $step);
            return View::make("installer.step" . $step);
        }
    }

    public function finish_install() {

        // Modifying Database Config
        $host = Session::get('host');
        $username = Session::get('username');
        $password = Session::get('password');
        $database = Session::get('database');

        $dbfile = fopen(app_path() . "/config/database.php", "w") or die("Unable to open file!");
        $dbfile_config = generate_db_config($host, $username, $password, $database);
        fwrite($dbfile, $dbfile_config);
        fclose($dbfile);

        // Modifying App Config File

        $url = Session::get('url');
        $website_title = Session::get('website_title');
        $timezone = Session::get('timezone');

        $twillo_account_sid = Session::get('twillo_account_sid');
        $twillo_auth_token = Session::get('twillo_auth_token');
        $twillo_number = Session::get('twillo_number');

        $default_payment = Session::get('default_payment');
        $default_storage = Session::get('default_storage');

        if ($default_payment == 'stripe') {
            $stripe_secret_key = Session::get('stripe_secret_key');
            $stripe_publishable_key = Session::get('stripe_publishable_key');
            $braintree_environment = '';
            $braintree_merchant_id = '';
            $braintree_public_key = '';
            $braintree_private_key = '';
            $braintree_cse = '';
        } else {
            $stripe_secret_key = '';
            $stripe_publishable_key = '';
            $braintree_environment = Session::get('braintree_environment');
            $braintree_merchant_id = Session::get('braintree_merchant_id');
            $braintree_public_key = Session::get('braintree_public_key');
            $braintree_private_key = Session::get('braintree_private_key');
            $braintree_cse = Session::get('braintree_cse');
        }

        if ($default_storage == 2) {
            $s3_bucket = Session::get('s3_bucket');
        } else {
            $s3_bucket = '';
        }

        $appfile = fopen(app_path() . "/config/app.php", "w") or die("Unable to open file!");
        $appfile_config = generate_app_config($braintree_cse, $stripe_publishable_key, $url, $timezone, $website_title, $s3_bucket, $twillo_account_sid, $twillo_auth_token, $twillo_number, $default_payment, $stripe_secret_key, $braintree_environment, $braintree_merchant_id, $braintree_public_key, $braintree_private_key);
        fwrite($appfile, $appfile_config);
        fclose($appfile);

        // Importing Database

        $re = mysqli_connect($host, $username, $password, $database);

        $result = mysqli_query($re, "SHOW TABLES FROM $database");

        if (!$result) {

            import_db($username, $password, $host, $database);
        }
        // Modifying Mail Config File

        $mail_driver = Session::get('mail_driver');
        $email_name = Session::get('email_name');
        $email_address = Session::get('email_address');
        $mandrill_secret = Session::get('mandrill_secret');

        $mailfile = fopen(app_path() . "/config/mail.php", "w") or die("Unable to open file!");
        $mailfile_config = generate_mail_config($host, $mail_driver, $email_name, $email_address);
        fwrite($mailfile, $mailfile_config);
        fclose($mailfile);

        if ($mail_driver == 'mandrill') {
            $mandrill_username = Input::get('mandrill_username');
            $servicesfile = fopen(app_path() . "/config/services.php", "w") or die("Unable to open file!");
            $servicesfile_config = generate_services_config($mandrill_secret, $mandrill_username);
            fwrite($servicesfile, $servicesfile_config);
            fclose($servicesfile);
        }

        return View::make("installer.step7");
    }

}
