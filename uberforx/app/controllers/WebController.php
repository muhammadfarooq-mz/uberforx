<?php

class WebController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     *
     * @return Response
     */
    public function __construct() {
        if (Config::get('app.production')) {
            echo "Something cool is going to be here soon.";
            die();
        }
    }

    private function _braintreeConfigure() {
        Braintree_Configuration::environment(Config::get('app.braintree_environment'));
        Braintree_Configuration::merchantId(Config::get('app.braintree_merchant_id'));
        Braintree_Configuration::publicKey(Config::get('app.braintree_public_key'));
        Braintree_Configuration::privateKey(Config::get('app.braintree_private_key'));
    }

    public function index() {
        return View::make('website.index');
    }

    public function termsncondition() {
        $theme = Theme::all();
        $logo = '/image/logo.png';
        $favicon = '/image/favicon.ico';
        foreach ($theme as $themes) {
            $favicon = '/uploads/' . $themes->favicon;
            $logo = '/uploads/' . $themes->logo;
        }
        if ($logo == '/uploads/') {
            $logo = '/image/logo.png';
        }
        if ($favicon == '/uploads/') {
            $favicon = '/image/favicon.ico';
        }
        $app_name = Config::get('app.website_title');
        return View::make('website.termsandconditions')
                        ->with('title', 'Terms and Conditions')
                        ->with('logo', $logo)
                        ->with('favicon', $favicon)
                        ->with('app_name', $app_name);
    }

    public function banking_provider_mobile() {
        $id = Request::segment(2);
        $provider = Walker::where('id', $id)->first();

        $theme = Theme::all();
        $logo = '/image/logo.png';
        $favicon = '/image/favicon.ico';
        foreach ($theme as $themes) {
            $favicon = '/uploads/' . $themes->favicon;
            $logo = '/uploads/' . $themes->logo;
        }
        if ($logo == '/uploads/') {
            $logo = '/image/logo.png';
        }
        if ($favicon == '/uploads/') {
            $favicon = '/image/favicon.ico';
        }
        $provider_first_name = $provider->first_name;
        $provider_last_name = $provider->last_name;
        $provider_email = $provider->email;
        $app_name = Config::get('app.website_title');
        if ($provider->merchant_id == NULL) {
            if (Config::get('app.default_payment') == 'stripe') {
                return View::make('website.banking_provider_stripe')
                                ->with('title', 'Banking Provider')
                                ->with('logo', $logo)
                                ->with('favicon', $favicon)
                                ->with('app_name', $app_name)
                                ->with('provider', $provider)
                                ->with('provider_id', $id)
                                ->with('provider_first_name', $provider_first_name)
                                ->with('provider_last_name', $provider_last_name)
                                ->with('provider_email', $provider_email);
            } else {
                return View::make('website.banking_provider_braintree')
                                ->with('title', 'Banking Provider')
                                ->with('logo', $logo)
                                ->with('favicon', $favicon)
                                ->with('app_name', $app_name)
                                ->with('provider', $provider)
                                ->with('provider_id', $id);
            }
        } else {
            return View::make('website.banking_done')
                            ->with('title', 'Banking Provider')
                            ->with('logo', $logo)
                            ->with('favicon', $favicon)
                            ->with('app_name', $app_name)
                            ->with('provider', $provider);
        }
    }

    public function providerB_bankingSubmit() {
        $this->_braintreeConfigure();
        $result = new stdClass();
        $result = Braintree_MerchantAccount::create(
                        array(
                            'individual' => array(
                                'firstName' => Input::get('first_name'),
                                'lastName' => Input::get('last_name'),
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'dateOfBirth' => date('Y-m-d', strtotime(Input::get('dob'))),
                                'ssn' => Input::get('ssn'),
                                'address' => array(
                                    'streetAddress' => Input::get('streetAddress'),
                                    'locality' => Input::get('locality'),
                                    'region' => Input::get('region'),
                                    'postalCode' => Input::get('postalCode')
                                )
                            ),
                            'funding' => array(
                                'descriptor' => 'UberForX',
                                'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                                'email' => Input::get('bankemail'),
                                'mobilePhone' => Input::get('bankphone'),
                                'accountNumber' => Input::get('accountNumber'),
                                'routingNumber' => Input::get('routingNumber')
                            ),
                            'tosAccepted' => true,
                            'masterMerchantAccountId' => Config::get('app.masterMerchantAccountId'),
                            'id' => "taxinow" . Input::get('id')
                        )
        );

        Log::info('res = ' . print_r($result, true));
        if ($result->success) {
            $pro = Walker::where('id', Input::get('id'))->first();
            $pro->merchant_id = $result->merchantAccount->id;
            $pro->save();
            Log::info(print_r($pro, true));
            Log::info('Adding banking details to provider from Admin = ' . print_r($result, true));
            return Redirect::to("/");
        } else {
            Log::info('Error in adding banking details: ' . $result->message);
            return Redirect::to("banking_provider_mobile");
        }
    }

    public function providerS_bankingSubmit() {
        $id = Input::get('id');
        Log::info('id = ' . print_r($id, true));
        Stripe::setApiKey(Config::get('app.stripe_secret_key'));
        $token_id = Input::get('stripeToken');
        Log::info('token_id = ' . print_r($token_id, true));
        // Create a Recipient
        try {
            $recipient = Stripe_Recipient::create(array(
                        "name" => Input::get('first_name') . " " . Input::get('last_name'),
                        "type" => Input::get('type'),
                        "bank_account" => $token_id,
                        "email" => Input::get('email')
                            )
            );

            Log::info('recipient = ' . print_r($recipient, true));

            $pro = Walker::where('id', Input::get('id'))->first();
            $pro->merchant_id = $recipient->id;
            $pro->account_id = $recipient->active_account->id;
            $pro->last_4 = $recipient->active_account->last4;
            $pro->save();

            Log::info('recipient added = ' . print_r($recipient, true));
        } catch (Exception $e) {
            //Log::info('Error in Stripe = ' . print_r($e, true));
            return Redirect::route("banking_provider_mobile", $id);
        }
        return Redirect::to("/");
    }

    public function page($title) {

        $theme = Theme::all();
        $logo = '/image/logo.png';
        $favicon = '/image/favicon.ico';
        foreach ($theme as $themes) {
            $favicon = '/uploads/' . $themes->favicon;
            $logo = '/uploads/' . $themes->logo;
        }
        if ($logo == '/uploads/') {
            $logo = '/image/logo.png';
        }
        if ($favicon == '/uploads/') {
            $favicon = '/image/favicon.ico';
        }
        $app_name = Config::get('app.website_title');
        return View::make('website.' . $title)
                        ->with('title', $title)
                        ->with('logo', $logo)
                        ->with('favicon', $favicon)
                        ->with('app_name', $app_name);
    }

    public function track_ride() {
        $id = Request::segment(2);
        $request = Requests::where('security_key', $id)->where('is_started', 1)->where('is_completed', 0)->first();
        if ($request) {
            $owner = Owner::where('id', $request->owner_id)->first();
            $user_name = $owner->first_name . " " . $owner->last_name;
            $theme = Theme::all();
            $logo = '/image/logo.png';
            $favicon = '/image/favicon.ico';
            foreach ($theme as $themes) {
                $favicon = '/uploads/' . $themes->favicon;
                $logo = '/uploads/' . $themes->logo;
            }
            if ($logo == '/uploads/') {
                $logo = '/image/logo.png';
            }
            if ($favicon == '/uploads/') {
                $favicon = '/image/favicon.ico';
            }
            $app_name = Config::get('app.website_title');
            // walk location
            $reqloc = WalkLocation::where('request_id', $request->id)->first();
            return View::make('website.track_ride')
                            ->with('title', 'Track ' . $user_name)
                            ->with('user_name', $user_name)
                            ->with('logo', $logo)
                            ->with('favicon', $favicon)
                            ->with('app_name', $app_name)
                            ->with('cur_lat', $reqloc->latitude)
                            ->with('cur_lon', $reqloc->longitude)
                            ->with('track_id', $id);
        } else {
            return Redirect::to('/');
        }
    }

    // Ajax for auto updating location for tracking
    public function get_track_loc($id) {
        $request = Requests::where('security_key', $id)->where('is_started', 1)->where('is_completed', 0)->first();
        if ($request) {
            $owner = Owner::where('id', $request->owner_id)->first();
            $user_name = $owner->first_name . " " . $owner->last_name;
            $theme = Theme::all();
            $logo = '/image/logo.png';
            $favicon = '/image/favicon.ico';
            foreach ($theme as $themes) {
                $favicon = '/uploads/' . $themes->favicon;
                $logo = '/uploads/' . $themes->logo;
            }
            if ($logo == '/uploads/') {
                $logo = '/image/logo.png';
            }
            if ($favicon == '/uploads/') {
                $favicon = '/image/favicon.ico';
            }
            $app_name = Config::get('app.website_title');
            // walk location
            $start_loc = WalkLocation::where('request_id', $request->id)->first();
            $reqloc = WalkLocation::where('request_id', $request->id)->orderBy('id', 'desc')->first();
            $title = 'Track ' . $user_name;

            return Response::json(array('success' => true,
                        'titl' => $title,
                        'logo' => $logo,
                        'favicon' => $favicon,
                        'app_name' => $app_name,
                        'cur_lat' => $reqloc->latitude,
                        'cur_lon' => $reqloc->longitude,
                        'prev_lat' => $start_loc->latitude,
                        'prev_lon' => $start_loc->longitude,
                        'track_id' => $id));
        } else {
            return Redirect::to('/');
        }
    }

}
