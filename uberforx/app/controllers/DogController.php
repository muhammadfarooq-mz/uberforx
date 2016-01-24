<?php

class DogController extends BaseController {

    public function isAdmin($token) {
        return false;
    }

    public function getOwnerData($owner_id, $token, $is_admin) {

        if ($owner_data = Owner::where('token', '=', $token)->where('id', '=', $owner_id)->first()) {
            return $owner_data;
        } elseif ($is_admin) {
            $owner_data = Owner::where('id', '=', $owner_id)->first();
            if (!$owner_data) {
                return false;
            }
            return $owner_data;
        } else {
            return false;
        }
    }

    private function get_timezone_offset($remote_tz, $origin_tz = null) {
        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }

    public function create() {
        if (Request::isMethod('post')) {
            $name = ucwords(trim(Input::get('name')));
            $age = Input::get('age');
            $breed = Input::get('type');
            $likes = Input::get('notes');
            $token = Input::get('token');
            $owner_id = Input::get('id');
            $picture = Input::file('picture');

            $validator = Validator::make(
                            array(
                        'name' => $name,
                        'age' => $age,
                        'breed' => $breed,
                        'token' => $token,
                        'owner_id' => $owner_id,
                        'picture' => $picture,
                            ), array(
                        'name' => 'required',
                        'age' => 'required|integer',
                        'breed' => 'required',
                        'token' => 'required',
                        'owner_id' => 'required|integer',
                        /* 'picture' => 'required|mimes:jpeg,bmp,png', */
                        'picture' => 'required',
                            )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {
                        // Do necessary operations
                        // check if there's already a dog
                        $dog = Dog::where('owner_id', $owner_id)->first();
                        if ($dog === null) {
                            $dog = new Dog;
                        }

                        $dog->name = $name;
                        $dog->age = $age;
                        $dog->breed = $breed;
                        $dog->likes = $likes;
                        $dog->owner_id = $owner_data->id;


                        // Upload File
                        $file_name = time();
                        $file_name .= rand();
                        $ext = Input::file('picture')->getClientOriginalExtension();
                        Input::file('picture')->move(public_path() . "/uploads", $file_name . "." . $ext);
                        $local_url = $file_name . "." . $ext;

                        // Upload to S3
                        if (Config::get('app.s3_bucket') != "") {
                            $s3 = App::make('aws')->get('s3');
                            $pic = $s3->putObject(array(
                                'Bucket' => Config::get('app.s3_bucket'),
                                'Key' => $file_name,
                                'SourceFile' => public_path() . "/uploads/" . $local_url,
                            ));

                            $s3->putObjectAcl(array(
                                'Bucket' => Config::get('app.s3_bucket'),
                                'Key' => $file_name,
                                'ACL' => 'public-read'
                            ));

                            $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                        } else {
                            $s3_url = asset_url() . '/uploads/' . $local_url;
                        }
                        if (isset($dog->image_url)) {
                            if ($dog->image_url != "") {
                                $icon = $dog->image_url;
                                unlink_image($icon);
                            }
                        }
                        $dog->image_url = $s3_url;

                        $dog->save();

                        $owner = Owner::find($owner_data->id);
                        $owner->dog_id = $dog->id;
                        $owner->save();

                        $response_array = array('success' => true);
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        } else {
            //handles get request
            $token = Input::get('token');
            $owner_id = Input::get('id');
            $validator = Validator::make(
                            array(
                        'token' => $token,
                        'owner_id' => $owner_id,
                            ), array(
                        'token' => 'required',
                        'owner_id' => 'required|integer'
                            )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {

                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {
                        $dog = Dog::find($owner_data->dog_id);
                        if ($dog) {
                            $response_array = array(
                                'success' => true,
                                'thing_id' => $dog->id,
                                'age' => $dog->age,
                                'type' => $dog->breed,
                                'notes' => $dog->likes,
                                'image_url' => $dog->image_url,
                            );
                            $response_code = 200;
                        } else {
                            $response_array = array('success' => false, 'error' => 'No Dogs Found', 'error_code' => 445);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {

                    if ($is_admin) {
                        $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Setting Owner Location

    public function update_thing() {
        if (Request::isMethod('post')) {
            $name = ucwords(trim(Input::get('name')));
            $age = Input::get('age');
            $breed = Input::get('type');
            $likes = Input::get('notes');
            $token = Input::get('token');
            $owner_id = Input::get('id');
            $picture = Input::file('picture');

            $validator = Validator::make(
                            array(
                        'token' => $token,
                        'owner_id' => $owner_id,
                        'age' => $age,
                        'picture' => $picture,
                            ), array(
                        'token' => 'required',
                        'owner_id' => 'required|integer',
                        'age' => 'integer',
                        /* 'picture' => 'mimes:jpeg,bmp,png', */
                        'picture' => '',
                            )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {

                        $dog_data = Dog::where('owner_id', $owner_id)->first();
                        if ($dog_data) {
                            $dog = Dog::find($dog_data->id);
                            if ($name) {
                                $dog->name = $name;
                            }
                            if ($age) {
                                $dog->age = $age;
                            }
                            if ($breed) {
                                $dog->breed = $breed;
                            }
                            if ($likes) {
                                $dog->likes = $likes;
                            }

                            if (Input::hasFile('picture')) {
                                // upload image
                                $file_name = time();
                                $file_name .= rand();
                                $file_name = sha1($file_name);

                                $ext = Input::file('picture')->getClientOriginalExtension();
                                Input::file('picture')->move(public_path() . "/uploads", $file_name . "." . $ext);
                                $local_url = $file_name . "." . $ext;

                                // Upload to S3
                                if (Config::get('app.s3_bucket') != "") {
                                    $s3 = App::make('aws')->get('s3');
                                    $pic = $s3->putObject(array(
                                        'Bucket' => Config::get('app.s3_bucket'),
                                        'Key' => $file_name,
                                        'SourceFile' => public_path() . "/uploads/" . $local_url,
                                    ));

                                    $s3->putObjectAcl(array(
                                        'Bucket' => Config::get('app.s3_bucket'),
                                        'Key' => $file_name,
                                        'ACL' => 'public-read'
                                    ));

                                    $s3_url = $s3->getObjectUrl(Config::get('app.s3_bucket'), $file_name);
                                } else {
                                    $s3_url = asset_url() . '/uploads/' . $local_url;
                                }

                                if (isset($dog->image_url)) {
                                    if ($dog->image_url != "") {
                                        $icon = $dog->image_url;
                                        unlink_image($icon);
                                    }
                                }

                                $dog->image_url = $s3_url;
                            }

                            $dog->save();
                            $response_array = array('success' => true);
                            $response_code = 200;
                        } else {
                            $response_array = array('success' => false, 'error' => 'No Dog Found', 'error_code' => 405);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Rate Walker

    public function set_walker_rating() {
        if (Request::isMethod('post')) {
            $comment = "";
            if (Input::has('comment')) {
                $comment = Input::get('comment');
            }
            $request_id = Input::get('request_id');
            $rating = 0;
            if (Input::has('rating')) {
                $rating = Input::get('rating');
            }
            $token = Input::get('token');
            $owner_id = Input::get('id');

            $validator = Validator::make(
                            array(
                        'request_id' => $request_id,
                        /* 'rating' => $rating, */
                        'token' => $token,
                        'owner_id' => $owner_id,
                            ), array(
                        'request_id' => 'required|integer',
                        /* 'rating' => 'required|integer', */
                        'token' => 'required',
                        'owner_id' => 'required|integer'
                            )
            );

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {
                        // Do necessary operations
                        if ($request = Requests::find($request_id)) {
                            if ($request->owner_id == $owner_data->id) {
                                if ($request->is_completed == 1) {
                                    if ($request->is_walker_rated == 0) {
                                        $walker_review = new WalkerReview;
                                        $walker_review->request_id = $request_id;
                                        $walker_review->walker_id = $request->confirmed_walker;
                                        $walker_review->rating = $rating;
                                        $walker_review->owner_id = $owner_data->id;
                                        $walker_review->comment = $comment;
                                        $walker_review->save();

                                        $request->is_walker_rated = 1;
                                        $request->save();

                                        if ($rating) {
                                            if ($walker = Walker::find($request->confirmed_walker)) {
                                                $old_rate = $walker->rate;
                                                $old_rate_count = $walker->rate_count;
                                                $new_rate_counter = ($walker->rate_count + 1);
                                                $new_rate = (($walker->rate * $walker->rate_count) + $rating) / $new_rate_counter;
                                                $walker->rate_count = $new_rate_counter;
                                                $walker->rate = $new_rate;
                                                $walker->save();
                                            }
                                        }

                                        $response_array = array('success' => true);
                                        $response_code = 200;
                                    } else {
                                        $response_array = array('success' => false, 'error' => 'Already Rated', 'error_code' => 409);
                                        $response_code = 200;
                                    }
                                } else {
                                    $response_array = array('success' => false, 'error' => 'Walk is not completed', 'error_code' => 409);
                                    $response_code = 200;
                                }
                            } else {
                                $response_array = array('success' => false, 'error' => 'Walk ID doesnot matches with Dog ID', 'error_code' => 407);
                                $response_code = 200;
                            }
                        } else {
                            $response_array = array('success' => false, 'error' => 'Walk ID Not Found', 'error_code' => 408);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Setting Owner Location

    public function set_location() {
        if (Request::isMethod('post')) {
            $latitude = Input::get('latitude');
            $longitude = Input::get('longitude');
            $token = Input::get('token');
            $owner_id = Input::get('id');

            $validator = Validator::make(
                            array(
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'token' => $token,
                        'owner_id' => $owner_id,
                            ), array(
                        'latitude' => 'required',
                        'longitude' => 'required',
                        'token' => 'required',
                        'owner_id' => 'required|integer'
                            )
            );
            /* $var = Keywords::where('id', 2)->first(); */

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);
                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $response_array = array('success' => true);
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        /* $response_array = array('success' => false, 'error' => '' . $var->keyword . 'ID not Found', 'error_code' => 410); */
                        $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . 'ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

// Get Walk Location


    public function get_walk_location() {

        $request_id = Input::get('request_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $timestamp = Input::get('ts');


        $validator = Validator::make(
                        array(
                    'request_id' => $request_id,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'request_id' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer'
                        )
        );
        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if ($request = Requests::find($request_id)) {
                        if ($request->owner_id == $owner_id) {
                            if (isset($timestamp)) {
                                $walk_locations = WalkLocation::where('request_id', '=', $request_id)->where('created_at', '>', $timestamp)->orderBy('created_at')->get();
                            } else {
                                $walk_locations = WalkLocation::where('request_id', '=', $request_id)->orderBy('created_at')->get();
                            }
                            $locations = array();

                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;


                            foreach ($walk_locations as $walk_location) {
                                $location = array();
                                $location['latitude'] = $walk_location->latitude;
                                $location['longitude'] = $walk_location->longitude;
                                $location['distance'] = convert($walk_location->distance, $unit);
                                $location['bearing'] = $walk_location->bearing;
                                $location['timestamp'] = $walk_location->created_at;
                                array_push($locations, $location);
                            }

                            $response_array = array('success' => true, 'locationdata' => $locations);
                            $response_code = 200;
                        } else {
                            /* $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with' . $var->keyword . ' ID', 'error_code' => 407); */
                            $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with' . Config::get('app.generic_keywords.User') . ' ID', 'error_code' => 407);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_providers_all() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );
        /* $var = Keywords::where('id', 2)->first(); */
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $settings = Settings::where('key', 'default_search_radius')->first();
                    $distance = $settings->value;
                    $settings = Settings::where('key', 'default_distance_unit')->first();
                    $unit = $settings->value;
                    if ($unit == 0) {
                        $multiply = 1.609344;
                    } elseif ($unit == 1) {
                        $multiply = 1;
                    }
                    $query = "SELECT "
                            . "walker.id, "
                            . "walker.latitude, "
                            . "walker.longitude, "
                            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ,8) as distance "
                            . "from walker "
                            . "where is_available = 1 and "
                            . "is_active = 1 and "
                            . "is_approved = 1 and "
                            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                            . "order by distance "
                            . "LIMIT 5";
                    $walkers = DB::select(DB::raw($query));
                    $p = 0;
                    foreach ($walkers as $key) {
                        $provider[$p]['id'] = $key->id;
                        $provider[$p]['distance'] = $key->distance;
                        $provider[$p]['latitude'] = $key->latitude;
                        $provider[$p]['longitude'] = $key->longitude;
                        $provider[$p]['bearing'] = $key->bearing;
                        $walker_services = ProviderServices::where('provider_id', $key->id)->first();
                        if ($walker_services != NULL) {
                            $walker_type = ProviderType::where('id', $walker_services->type)->first();

                            if ($walker_type != NULL) {
                                $provider[$p]['type'] = $walker_type->name;
                                $provider[$p]['base_price'] = $walker_services->base_price;
                                $provider[$p]['distance_cost'] = $walker_services->price_per_unit_distance;
                                $provider[$p]['time_cost'] = $walker_services->price_per_unit_time;
                            } else {
                                $provider[$p]['type'] = '';
                                $provider[$p]['base_price'] = '';
                                $provider[$p]['distance_cost'] = '';
                                $provider[$p]['time_cost'] = '';
                            }
                        }
                        $p++;
                    }

                    if ($walkers != NULL) {
                        $response_array = array(
                            'success' => true,
                            'walkers' => $provider,
                        );
                        $response_code = 200;
                    } else {
                        $response_array = array(
                            'success' => false,
                            'error' => 'No walker found',
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_nearby_providers() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $type = Input::get('type');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );
        /* $var = Keywords::where('id', 2)->first(); */
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {

                    // If type is not an array
                    if (!is_array($type)) {
                        // and if type wasn't passed at all
                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();
                            if ($provider_type === null) {
                                $type = array(1);
                            } else {
                                $type = array($provider_type->id);
                            }
                        } else {
                            $type = explode(',', $type);
                        }
                    }

                    foreach ($type as $key) {
                        $typ[] = $key;
                    }
                    $ty = implode(",", $typ);
                    $typequery = "SELECT distinct provider_id from walker_services where type IN($ty)";
                    $typewalkers = DB::select(DB::raw($typequery));
                    Log::info('typewalkers = ' . print_r($typewalkers, true));
                    if ($typewalkers == NULL) {
                        /* $driver = Keywords::where('id', 1)->first();
                          $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found matching the service type.', 'error_code' => 405); */
                        $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 405);
                        $response_code = 200;
                        return Response::json($response_array, $response_code);
                    }
                    foreach ($typewalkers as $key) {
                        $types[] = $key->provider_id;
                    }
                    $typestring = implode(",", $types);
                    Log::info('typestring = ' . print_r($typestring, true));

                    $settings = Settings::where('key', 'default_search_radius')->first();
                    $distance = $settings->value;
                    $settings = Settings::where('key', 'default_distance_unit')->first();
                    $unit = $settings->value;
                    if ($unit == 0) {
                        $multiply = 1.609344;
                    } elseif ($unit == 1) {
                        $multiply = 1;
                    }
                    $query = "SELECT "
                            . "walker.*, "
                            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ,8) as distance "
                            . "from walker "
                            . "where is_available = 1 and "
                            . "is_active = 1 and "
                            . "is_approved = 1 and "
                            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                            . "walker.deleted_at IS NULL and "
                            . "walker.id IN($typestring) "
                            . "order by distance";
                    $walkers = DB::select(DB::raw($query));

                    Log::info('walkers = ' . print_r($walkers, true));
                    $p = 0;
                    foreach ($walkers as $key) {
                        $provider[$p]['id'] = $key->id;
                        $provider[$p]['distance'] = $key->distance;
                        $provider[$p]['latitude'] = $key->latitude;
                        $provider[$p]['longitude'] = $key->longitude;
                        $walker_services = ProviderServices::where('provider_id', $key->id)->first();
                        if ($walker_services != NULL) {
                            $walker_type = ProviderType::where('id', $walker_services->type)->first();

                            if ($walker_type != NULL) {
                                $provider[$p]['type'] = $walker_type->name;
                                $provider[$p]['base_price'] = currency_converted($walker_services->base_price);
                                $provider[$p]['distance_cost'] = currency_converted($walker_services->price_per_unit_distance);
                                $provider[$p]['time_cost'] = currency_converted($walker_services->price_per_unit_time);
                            } else {
                                $provider[$p]['type'] = '';
                                $provider[$p]['base_price'] = '';
                                $provider[$p]['distance_cost'] = '';
                                $provider[$p]['time_cost'] = '';
                            }
                        }
                        $p++;
                    }
                    $settings = Settings::where('key', 'default_distance_unit')->first();
                    $unit = $settings->value;
                    if ($unit == 0) {
                        $unit_set = 'kms';
                    } elseif ($unit == 1) {
                        $unit_set = 'miles';
                    }

                    // Log::info('providers = '.print_r($provider, true));

                    if ($walkers != NULL) {
                        $response_array = array(
                            'success' => true,
                            'unit' => $unit_set,
                            'walkers' => $provider,
                        );
                        $response_code = 200;
                    } else {
                        $response_array = array(
                            'success' => false,
                            'unit' => $unit_set,
                            'error' => 'No walker found',
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Get Available Providers if provider_selection == 1 in settings table

    public function get_providers() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $type = Input::get('type');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $is_multiple_service = Settings::where('key', 'allow_multiple_service')->first();
                    if ($is_multiple_service->value == 0) {

                        $archk = is_array($type);
                        Log::info('type = ' . print_r($archk, true));
                        if ($archk == 1) {
                            $type = $type;
                            Log::info('type = ' . print_r($type, true));
                        } else {
                            $type = explode(',', $type);
                            Log::info('type = ' . print_r($type, true));
                        }

                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();

                            if (!$provider_type) {
                                $type = 1;
                            } else {
                                $type = $provider_type->id;
                            }
                        }

                        foreach ($type as $key) {
                            $typ[] = $key;
                        }
                        $ty = implode(",", $typ);

                        $typequery = "SELECT distinct provider_id from walker_services where type IN($ty)";
                        $typewalkers = DB::select(DB::raw($typequery));
                        Log::info('typewalkers = ' . print_r($typewalkers, true));

                        if ($typewalkers == NULL) {
                            /* $driver = Keywords::where('id', 1)->first();
                              $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found matching the service type.', 'error_code' => 405); */
                            $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 405);
                            $response_code = 200;
                            return Response::json($response_array, $response_code);
                        }

                        foreach ($typewalkers as $key) {
                            $types[] = $key->provider_id;
                        }
                        $typestring = implode(",", $types);
                        Log::info('typestring = ' . print_r($typestring, true));

                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }
                        $query = "SELECT "
                                . "walker.*, "
                                . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                . "cos( radians(latitude) ) * "
                                . "cos( radians(longitude) - radians('$longitude') ) + "
                                . "sin( radians('$latitude') ) * "
                                . "sin( radians(latitude) ) ) ,8) as distance "
                                . "from walker "
                                . "where is_available = 1 and "
                                . "is_active = 1 and "
                                . "is_approved = 1 and "
                                . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                . "cos( radians(latitude) ) * "
                                . "cos( radians(longitude) - radians('$longitude') ) + "
                                . "sin( radians('$latitude') ) * "
                                . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                                . "walker.id IN($typestring) "
                                . "order by distance "
                                . "LIMIT 5";
                        $walkers = DB::select(DB::raw($query));
                        Log::info('walkers = ' . print_r($walkers, true));
                        if ($walkers != NULL) {
                            $owner = Owner::find($owner_id);
                            $owner->latitude = $latitude;
                            $owner->longitude = $longitude;
                            $owner->save();

                            $request = new Requests;
                            $request->owner_id = $owner_id;
                            $request->request_start_time = date("Y-m-d H:i:s");
                            $request->save();
                            foreach ($type as $key) {
                                $reqserv = new RequestServices;
                                $reqserv->request_id = $request->id;
                                $reqserv->type = $key;
                                $reqserv->save();
                            }
                            $p = 0;
                            foreach ($walkers as $prov) {
                                $providers[$p]['id'] = $prov->id;
                                $providers[$p]['first_name'] = $prov->first_name;
                                $providers[$p]['last_name'] = $prov->last_name;
                                $providers[$p]['picture'] = $prov->picture;
                                $providers[$p]['phone'] = $prov->phone;
                                $providers[$p]['latitude'] = $prov->latitude;
                                $providers[$p]['longitude'] = $prov->longitude;
                                $providers[$p]['rating'] = $prov->rate;
                                $providers[$p]['car_model'] = $prov->car_model;
                                $providers[$p]['car_number'] = $prov->car_number;
                                $providers[$p]['bearing'] = $prov->bearing;
                                $provserv = ProviderServices::where('provider_id', $prov->id)->get();
                                $types = ProviderType::where('id', '=', $prov->type)->first();
                                foreach ($provserv as $ps) {
                                    if ($ps->base_price != 0) {
                                        $providers[$p]['base_price'] = $ps->base_price;
                                        $providers[$p]['price_per_unit_time'] = $ps->price_per_unit_time;
                                        $providers[$p]['price_per_unit_distance'] = $ps->price_per_unit_distance;
                                        $providers[$p]['base_distance'] = $types->base_distance;
                                    } else {
                                        /* $settings = Settings::where('key', 'base_price')->first();
                                          $base_price = $settings->value; */
                                        $providers[$p]['base_price'] = $types->base_price;
                                        $providers[$p]['price_per_unit_time'] = $types->price_per_unit_time;
                                        $providers[$p]['price_per_unit_distance'] = $types->price_per_unit_distance;
                                        $providers[$p]['base_distance'] = $types->base_distance;
                                    }
                                }
                                /* $rat = WalkerReview::where('walker_id', $prov->id)->get();
                                  $countRating = count($rat); */

                                /* if ($countRating > 0) {
                                  $sum = 0;
                                  $count = 0;
                                  foreach ($rat as $ratp) {
                                  $sum = $ratp->rating + $sum;
                                  $count = $count + 1;
                                  }
                                  $avgrat = $sum / $count;
                                  $providers[$p]['rating'] = $avgrat;
                                  } else {
                                  $providers[$p]['rating'] = 0;
                                  } */
                                $s = 0;
                                $total_price = 0;
                                foreach ($provserv as $ps) {
                                    foreach ($type as $tp) {
                                        $providers[$p]['type'] = $tp;
                                        if ($tp == $ps->type) {
                                            $total_price = $total_price + $ps->base_price;
                                        }
                                    }
                                    $s = $s + 1;
                                }
                                $providers[$p]['total_price'] = $total_price;

                                $p = $p + 1;
                            }
                            Log::info('providers = ' . print_r($providers, true));
                            $response_array = array(
                                'success' => true,
                                'request_id' => $request->id,
                                'provider' => $providers,
                            );
                            $response_code = 200;
                        }
                    } else {

                        // Do necessary operations
                        $archk = is_array($type);
                        Log::info('type = ' . print_r($archk, true));
                        if ($archk == 1) {
                            $type = (int) $type;
                            Log::info('type = ' . print_r($type, true));
                            $count = 1;
                        } else {
                            $type1 = explode(',', $type);
                            $type = array();
                            foreach ($type1 as $key) {
                                $type[] = (int) $key;
                            }
                            Log::info('type = ' . print_r($type, true));
                            $count = count($type);
                        }
                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();

                            if (!$provider_type) {
                                $type = 1;
                            } else {
                                $type = $provider_type->id;
                            }
                        }

                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }

                        $query = "SELECT "
                                . "walker.id, "
                                . "walker.first_name, "
                                . "walker.last_name, "
                                . "walker.picture, "
                                . "walker.phone, "
                                . "walker.latitude, "
                                . "walker.longitude, "
                                . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                . "cos( radians(latitude) ) * "
                                . "cos( radians(longitude) - radians('$longitude') ) + "
                                . "sin( radians('$latitude') ) * "
                                . "sin( radians(latitude) ) ) ,8) as distance "
                                . "from walker "
                                . "where is_available = 1 and "
                                . "is_active = 1 and "
                                . "is_approved = 1 and "
                                . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                . "cos( radians(latitude) ) * "
                                . "cos( radians(longitude) - radians('$longitude') ) + "
                                . "sin( radians('$latitude') ) * "
                                . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                                . "order by distance "
                                . "LIMIT 5";
                        $walker = DB::select(DB::raw($query));
                        $typewalker = array();
                        $typewalker1 = array();

                        foreach ($walker as $key) {
                            $typewalker[] = $key->id;
                        }

                        $flag = 0;
                        if ($typewalker) {
                            $walkers = ProviderServices::whereIn('provider_id', $typewalker)->whereIn('type', $type)->groupBy('provider_id')->havingRaw('count(distinct type) = ' . $count)->get();
                            foreach ($walkers as $key) {
                                $typewalker1[] = $key->provider_id;
                            }
                            if ($typewalker1) {
                                $walkers = Walker::whereIn('id', $typewalker1)->get();
                                if ($walkers)
                                    $flag = 1;
                            }
                        }

                        if ($flag == 1) {

                            $c = 0;
                            foreach ($walkers as $key) {
                                $provider[$c]['id'] = $key->id;
                                $provider[$c]['first_name'] = $key->first_name;
                                $provider[$c]['last_name'] = $key->last_name;
                                $provider[$c]['picture'] = $key->picture;
                                $provider[$c]['phone'] = $key->phone;
                                $provider[$c]['latitude'] = $key->latitude;
                                $provider[$c]['longitude'] = $key->longitude;
                                $provider[$c]['rating'] = $key->rate;
                                $provider[$c]['car_model'] = $key->car_model;
                                $provider[$c]['car_number'] = $key->car_number;
                                $provider[$c]['bearing'] = $key->bearing;
                                $provserv = ProviderServices::where('provider_id', $key->id)->get();

                                foreach ($provserv as $ps) {
                                    $provider[$c]['type'] = $ps->type;
                                    $provider[$c]['base_price'] = $ps->base_price;
                                }

                                /* $rat = WalkerReview::where('walker_id', $key->id)->get();
                                  $countRating = count($rat);

                                  if ($countRating > 0) {
                                  $sum = 0;
                                  $count = 0;
                                  foreach ($rat as $ratp) {
                                  $sum = $ratp->rating + $sum;
                                  $count = $count + 1;
                                  }
                                  $avgrat = $sum / $count;
                                  $provider[$c]['rating'] = $avgrat;
                                  } else {
                                  $provider[$c]['rating'] = 0;
                                  } */
                                $s = 0;
                                $total_price = 0;
                                foreach ($provserv as $ps) {

                                    foreach ($type as $tp) {
                                        if ($tp == $ps->type) {
                                            $total_price = $total_price + $ps->base_price;
                                        }
                                    }
                                    $s = $s + 1;
                                }
                                $provider[$c]['total_price'] = $total_price;
                                $c = $c + 1;
                            }
                            Log::info('provider = ' . print_r($provider, true));
                            $response_array = array(
                                'success' => true,
                                'provider' => $provider,
                            );
                            $response_code = 200;
                        } else {
                            $response_array = array(
                                'success' => false,
                                'error' => 'No walker found',
                            );
                            $response_code = 200;
                        }
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_providers_old() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $type = Input::get('type');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if (!$type) {
                        // choose default type
                        $provider_type = ProviderType::where('is_default', 1)->first();

                        if (!$provider_type) {
                            $type = 1;
                        } else {
                            $type = $provider_type->id;
                        }
                    }
                    $ty = $type;
                    /* foreach ($type as $key) {
                      $typ[] = $key;
                      }
                      $ty = implode(",", $typ); */

                    $typequery = "SELECT distinct provider_id from walker_services where type IN($ty)";
                    $typewalkers = DB::select(DB::raw($typequery));
                    Log::info('typewalkers = ' . print_r($typewalkers, true));
                    foreach ($typewalkers as $key) {
                        $types[] = $key->provider_id;
                    }
                    $typestring = implode(",", $types);
                    Log::info('typestring = ' . print_r($typestring, true));

                    if ($typestring == '') {
                        $response_array = array('success' => false, 'error' => 'No provider found matching the service type.', 'error_code' => 405);
                        $response_code = 200;
                        return Response::json($response_array, $response_code);
                    }

                    $settings = Settings::where('key', 'default_search_radius')->first();
                    $distance = $settings->value;
                    $settings = Settings::where('key', 'default_distance_unit')->first();
                    $unit = $settings->value;
                    if ($unit == 0) {
                        $multiply = 1.609344;
                    } elseif ($unit == 1) {
                        $multiply = 1;
                    }
                    $query = "SELECT "
                            . "walker.id, "
                            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ,8) as distance "
                            . "from walker "
                            . "where is_available = 1 and "
                            . "is_active = 1 and "
                            . "is_approved = 1 and "
                            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                            . "walker.id IN($typestring) "
                            . "order by distance "
                            . "LIMIT 5";
                    $walkers = DB::select(DB::raw($query));
                    Log::info('walkers = ' . print_r($walkers, true));
                    if ($walkers != NULL) {
                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = date("Y-m-d H:i:s");
                        $request->save();
                        foreach ($type as $key) {
                            $reqserv = new RequestServices;
                            $reqserv->request_id = $request->id;
                            $reqserv->type = $key;
                            $reqserv->save();
                        }
                        /* $reqserv = new RequestServices;
                          $reqserv->request_id = $request->id;
                          $reqserv->type = $type;
                          $reqserv->save(); */
                        $response_array = array(
                            'success' => true,
                            'request_id' => $request->id,
                            'walkers' => $walkers,
                        );
                        $response_code = 200;
                    } else {
                        $response_array = array(
                            'success' => false,
                            'error' => 'No walker found',
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Create Request if provider_selection == 2 in settings table

    public function create_request_providers() {

        $token = Input::get('token');
        $owner_id = Input::get('id');
        $provider_id = Input::get('provider_id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $typein = Input::get('type');
        if (Input::has('payment_mode')) {
            $payment_opt = Input::get('payment_mode');
        }

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'provider_id' => $provider_id,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'provider_id' => 'required',
                        )
        );
        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $is_multiple_service = Settings::where('key', 'allow_multiple_service')->first();

                    if ($is_multiple_service->value == 0) {

                        $req = Requests::find($request_id);
                        $req->current_walker = $provider_id;
                        $req->save();

                        $response_array = array(
                            'success' => true,
                            'request_id' => $req->id,
                        );
                        $response_code = 200;
                    } else {

                        $archk = is_array($typein);
                        Log::info('type = ' . print_r($archk, true));
                        if ($archk == 1) {
                            $type = $typein;
                            Log::info('type = ' . print_r($typein, true));
                        } else {
                            $type = explode(',', $typein);
                            Log::info('type = ' . print_r($type, true));
                        }
                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();

                            if (!$provider_type) {
                                $type = 1;
                            } else {
                                $type = $provider_type->id;
                            }
                        }

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = date("Y-m-d H:i:s");
                        $request->current_walker = $provider_id;
                        $request->payment_mode = $payment_opt;
                        $request->save();
                        $flag = 0;
                        $base_price = 0;

                        $typs = array();
                        $typi = array();
                        $typp = array();

                        foreach ($type as $key) {
                            $reqserv = new RequestServices;
                            $reqserv->request_id = $request->id;
                            $reqserv->type = $key;
                            $reqserv->save();

                            $typ1 = ProviderType::where('id', $key)->first();
                            $ps = ProviderServices::where('type', $key)->where('provider_id', $provider_id)->first();
                            if ($ps->base_price > 0) {
                                $typp1 = 0.00;
                                $typp1 = $ps->base_price;
                            } else {
                                $typp1 = 0.00;
                            }
                            $typs['name'] = $typ1->name;
                            $typs['price'] = $typp1;

                            array_push($typi, $typs);

                            if ($ps) {
                                $base_price = $base_price + $ps->base_price;
                            }
                        }

                        $settings = Settings::where('key', 'provider_timeout')->first();
                        $time_left = $settings->value;

                        $msg_array = array();
                        $msg_array['type'] = $typi;
                        $msg_array['unique_id'] = 1;
                        $msg_array['request_id'] = $request->id;
                        $msg_array['time_left_to_respond'] = $time_left;
                        $msg_array['request_service'] = $key;
                        $msg_array['total_base_price'] = $base_price;

                        $owner = Owner::find($owner_id);
                        $request_data = array();
                        $request_data['owner'] = array();
                        $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                        $request_data['owner']['picture'] = $owner->picture;
                        $request_data['owner']['phone'] = $owner->phone;
                        $request_data['owner']['address'] = $owner->address;
                        $request_data['owner']['latitude'] = $owner->latitude;
                        $request_data['owner']['longitude'] = $owner->longitude;
                        $request_data['owner']['payment_type'] = $payment_opt;
                        $request_data['owner']['rating'] = $owner->rate;
                        $request_data['owner']['num_rating'] = $owner->rate_count;
                        /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                          $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */

                        $msg_array['request_data'] = $request_data;

                        $title = "New Request";
                        $message = $msg_array;
                        Log::info('first_walker_id = ' . print_r($provider_id, true));
                        Log::info('New request = ' . print_r($message, true));
                        /* don't do json_encode in above line because if */
                        send_notifications($provider_id, "walker", $title, $message);

                        $response_array = array(
                            'success' => true,
                            'request_id' => $request->id,
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Cancel Request
    public function cancellation() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $request_id = Input::get('request_id');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'request_id' => $request_id,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'request_id' => 'required',
                        )
        );
        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $req = Requests::find($request_id);
                    if ($req->is_paid == 0) {
                        DB::delete("delete from request_services where request_id = '" . $request_id . "';");
                        DB::delete("delete from walk_location where request_id = '" . $request_id . "';");
                        $req->is_cancelled = 1;
                        $req->save();
                        $response_array = array(
                            'success' => true,
                            'deleted request_id' => $req->id,
                        );
                        $response_code = 200;
                    } else {
                        $deduce = 0.85;
                        $refund = $req->total * $deduce;
                        $req->is_cancelled = 1;
                        $req->refund = $refund;

                        if (Input::has('cod')) {
                            if (Input::get('cod') == 1) {
                                $request->cod = 1;
                            } else {
                                $request->cod = 0;
                            }
                        }
                        $req->save();
                        // Refund Braintree Stuff.
                        DB::delete("delete from request_services where request_id = '" . $request_id . "';");
                        DB::delete("delete from walk_location where request_id = '" . $request_id . "';");
                        $response_array = array(
                            'success' => true,
                            'refund' => $refund,
                            'deleted request_id' => $req->id,
                        );
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Create Request if provider_selection == 1 in settings table

    public function create_request() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $d_latitude = Input::get('d_latitude');
        $d_longitude = Input::get('d_longitude');
        $payment_opt = 0;
        if (Input::has('payment_mode')) {
            $payment_opt = Input::get('payment_mode');
        }
        if (Input::has('payment_opt')) {
            $payment_opt = Input::get('payment_opt');
        }

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            $unit = "";
            $driver_data = "";

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    /* SEND REFERRAL & PROMO INFO */
                    $settings = Settings::where('key', 'referral_code_activation')->first();
                    $referral_code_activation = $settings->value;
                    if ($referral_code_activation) {
                        $referral_code_activation_txt = "referral on";
                    } else {
                        $referral_code_activation_txt = "referral off";
                    }

                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $promotional_code_activation = $settings->value;
                    if ($promotional_code_activation) {
                        $promotional_code_activation_txt = "promo on";
                    } else {
                        $promotional_code_activation_txt = "promo off";
                    }
                    /* SEND REFERRAL & PROMO INFO */
                    // Do necessary operations
                    $request = DB::table('request')->where('owner_id', $owner_data->id)
                            ->where('is_completed', 0)
                            ->where('is_cancelled', 0)
                            ->where('current_walker', '!=', 0)
                            ->first();
                    if ($request) {
                        goto DontcreateReq;
                    } else {
                        /* SEND REFERRAL & PROMO INFO */
                        if ($payment_opt != 1) {
                            $card_count = Payment::where('owner_id', '=', $owner_id)->count();
                            if ($card_count <= 0) {
                                $response_array = array('success' => false, 'error' => "Please add card first for payment.", 'error_code' => 417);
                                $response_code = 200;
                                $response = Response::json($response_array, $response_code);
                                return $response;
                            }
                        }
                        /* if ($owner_data->debt > 0) {
                          $response_array = array('success' => false, 'error' => "You are already in \$$owner_data->debt debt", 'error_code' => 417);
                          $response_code = 200;
                          $response = Response::json($response_array, $response_code);
                          return $response;
                          } */
                        if (Input::has('type')) {
                            Log::info('out');
                            $type = Input::get('type');
                            if (!$type) {
                                // choose default type
                                $provider_type = ProviderType::where('is_default', 1)->first();

                                if (!$provider_type) {
                                    $type = 1;
                                } else {
                                    $type = $provider_type->id;
                                }
                            }

                            $typequery = "SELECT distinct provider_id from walker_services where type IN($type)";
                            $typewalkers = DB::select(DB::raw($typequery));

                            Log::info('typewalkers = ' . print_r($typewalkers, true));

                            if (count($typewalkers) > 0) {

                                foreach ($typewalkers as $key) {

                                    $types[] = $key->provider_id;
                                }

                                $typestring = implode(",", $types);
                                Log::info('typestring = ' . print_r($typestring, true));
                            } else {
                                /* $driver = Keywords::where('id', 1)->first();
                                  send_notifications($owner_id, "owner", 'No ' . $driver->keyword . ' Found', 'No ' . $driver->keyword . ' found matching the service type.'); */
                                send_notifications($owner_id, "owner", 'No ' . Config::get('app.generic_keywords.Provider') . ' Found', 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.');

                                /* $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found matching the service type.', 'error_code' => 416); */
                                $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 416);
                                $response_code = 200;
                                return Response::json($response_array, $response_code);
                            }

                            $settings = Settings::where('key', 'default_search_radius')->first();
                            $distance = $settings->value;
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            if ($unit == 0) {
                                $multiply = 1.609344;
                            } elseif ($unit == 1) {
                                $multiply = 1;
                            }
                            $query = "SELECT walker.*, "
                                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ,8) as distance "
                                    . "FROM walker "
                                    . "where is_available = 1 and "
                                    . "is_active = 1 and "
                                    . "is_approved = 1 and "
                                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                                    . "walker.deleted_at IS NULL and "
                                    . "walker.id IN($typestring) "
                                    . "order by distance";
                            $walkers = DB::select(DB::raw($query));
                            $walker_list = array();

                            $owner = Owner::find($owner_id);
                            $owner->latitude = $latitude;
                            $owner->longitude = $longitude;
                            $owner->save();

                            $request = new Requests;
                            $request->owner_id = $owner_id;
                            $request->payment_mode = $payment_opt;

                            if (Input::has('promo_code')) {
                                $promo_code = Input::get('promo_code');
                                $payment_mode = 0;
                                $payment_mode = $payment_opt;

                                $settings = Settings::where('key', 'promotional_code_activation')->first();
                                $prom_act = $settings->value;
                                if ($prom_act) {
                                    if ($payment_mode == 0) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_card_payment')->first();
                                        $prom_act_card = $settings->value;
                                        if ($prom_act_card) {
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                    $response_code = 200;
                                                    return Response::json($response_array, $response_code);
                                                } else {
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);
                                                        $response_code = 200;
                                                        return Response::json($response_array, $response_code);
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                $response_code = 200;
                                                return Response::json($response_array, $response_code);
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on card payment.', 'error_code' => 505);
                                            $response_code = 200;
                                            return Response::json($response_array, $response_code);
                                        }
                                    } else if (($payment_mode == 1)) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_cash_payment')->first();
                                        $prom_act_cash = $settings->value;
                                        if ($prom_act_cash) {
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                    $response_code = 200;
                                                    return Response::json($response_array, $response_code);
                                                } else {
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);
                                                        $response_code = 200;
                                                        return Response::json($response_array, $response_code);
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                $response_code = 200;
                                                return Response::json($response_array, $response_code);
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on cash payment.', 'error_code' => 505);
                                            $response_code = 200;
                                            return Response::json($response_array, $response_code);
                                        }
                                    }/* else {
                                      $response_array = array('success' => FALSE, 'error' => 'Payment mode is paypal', 'error_code' => 505);
                                      $response_code = 200;
                                      return Response::json($response_array, $response_code);
                                      } */
                                } else {
                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 505);
                                    $response_code = 200;
                                    return Response::json($response_array, $response_code);
                                }



                                /* $pcode = PromoCodes::where('coupon_code', Input::get('promo_code'))->first();

                                  if ($pcode) {
                                  // promo history
                                  $promohistory = PromoHistory::where('user_id', $owner_id)->where('promo_code', Input::get('promo_code'))->first();
                                  if (!$promohistory) {
                                  if (date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($pcode->expiry)))) {
                                  $response_array = array('success' => false, 'Promo Code already Expired', 'error_code' => 425);
                                  $response_code = 200;
                                  return Response::json($response_array, $response_code);
                                  } else {
                                  $request->promo_code = $pcode->id;
                                  if ($pcode->uses == 1) {
                                  $pcode->status = 3;
                                  }
                                  $pcode->uses = $pcode->uses - 1;
                                  $pcode->save();
                                  $phist = new PromoHistory();
                                  $phist->user_id = $owner_id;
                                  $phist->promo_code = Input::get('promo_code');
                                  $phist->amount_earned = $pcode->value;
                                  $phist->save();
                                  if ($pcode->type == 2) {
                                  // Absolute discount
                                  // Add to ledger amount
                                  $led = Ledger::where('owner_id', $owner_id)->first();
                                  if ($led) {
                                  $led->amount_earned = $led->amount_earned + $pcode->value;
                                  $led->save();
                                  } else {
                                  $ledger = new Ledger();
                                  $ledger->owner_id = $owner_id;
                                  $ledger->referral_code = "0";
                                  $ledger->total_referrals = 0;
                                  $ledger->amount_earned = $pcode->value;
                                  $ledger->amount_spent = 0;
                                  $ledger->save();
                                  }
                                  }
                                  }
                                  } else {
                                  $response_array = array('success' => false, 'Promo Code already Used', 'error_code' => 425);
                                  $response_code = 200;
                                  return Response::json($response_array, $response_code);
                                  }
                                  } else {
                                  $response_array = array('success' => false, 'Invalid Promo Code', 'error_code' => 415);
                                  $response_code = 200;
                                  return Response::json($response_array, $response_code);
                                  } */
                            }

                            $user_timezone = $owner->timezone;
                            $default_timezone = Config::get('app.timezone');
                            /* $offset = $this->get_timezone_offset($default_timezone, $user_timezone); */
                            $date_time = get_user_time($default_timezone, $user_timezone, date("Y-m-d H:i:s"));
                            $request->D_latitude = 0;
                            if (isset($d_latitude)) {
                                $request->D_latitude = Input::get('d_latitude');
                            }
                            $request->D_longitude = 0;
                            if (isset($d_longitude)) {
                                $request->D_longitude = Input::get('d_longitude');
                            }
                            /* $request->request_start_time = date("Y-m-d H:i:s"); */
                            $request->request_start_time = $date_time;
                            $request->save();

                            $reqserv = new RequestServices;
                            $reqserv->request_id = $request->id;
                            $reqserv->type = $type;
                            $reqserv->save();
                        } else {
                            Log::info('in');
                            $settings = Settings::where('key', 'default_search_radius')->first();
                            $distance = $settings->value;
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            if ($unit == 0) {
                                $multiply = 1.609344;
                            } elseif ($unit == 1) {
                                $multiply = 1;
                            }
                            $query = "SELECT walker.*, "
                                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ,8) as distance "
                                    . "FROM walker "
                                    . "where is_available = 1 and "
                                    . "is_active = 1 and "
                                    . "is_approved = 1 and "
                                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                                    . "cos( radians(latitude) ) * "
                                    . "cos( radians(longitude) - radians('$longitude') ) + "
                                    . "sin( radians('$latitude') ) * "
                                    . "sin( radians(latitude) ) ) ) ,8) <= $distance and "
                                    . "walker.deleted_at IS NULL and "
                                    . "order by distance";
                            $walkers = DB::select(DB::raw($query));
                            $walker_list = array();

                            $owner = Owner::find($owner_id);
                            $owner->latitude = $latitude;
                            $owner->longitude = $longitude;
                            $owner->save();

                            $request = new Requests;
                            $request->owner_id = $owner_id;
                            $request->payment_mode = $payment_opt;

                            if (Input::has('promo_code')) {
                                $promo_code = Input::get('promo_code');
                                $payment_mode = 0;
                                $payment_mode = $payment_opt;
                                $settings = Settings::where('key', 'promotional_code_activation')->first();
                                $prom_act = $settings->value;
                                if ($prom_act) {
                                    if ($payment_mode == 0) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_card_payment')->first();
                                        $prom_act_card = $settings->value;
                                        if ($prom_act_card) {
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                    $response_code = 200;
                                                    return Response::json($response_array, $response_code);
                                                } else {
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);
                                                        $response_code = 200;
                                                        return Response::json($response_array, $response_code);
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                $response_code = 200;
                                                return Response::json($response_array, $response_code);
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on card payment.', 'error_code' => 505);
                                            $response_code = 200;
                                            return Response::json($response_array, $response_code);
                                        }
                                    } else if (($payment_mode == 1)) {
                                        $settings = Settings::where('key', 'get_promotional_profit_on_cash_payment')->first();
                                        $prom_act_cash = $settings->value;
                                        if ($prom_act_cash) {
                                            if ($promos = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->where('state', '=', 1)->first()) {
                                                if ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime(trim($promos->expiry)))) || (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime(trim($promos->start_date))))) {
                                                    $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                    $response_code = 200;
                                                    return Response::json($response_array, $response_code);
                                                } else {
                                                    $promo_is_used = UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $promos->id)->count();
                                                    if ($promo_is_used) {
                                                        $response_array = array('success' => FALSE, 'error' => 'Promotional code already used.', 'error_code' => 512);
                                                        $response_code = 200;
                                                        return Response::json($response_array, $response_code);
                                                    } else {
                                                        $promo_update_counter = PromoCodes::find($promos->id);
                                                        $promo_update_counter->uses = $promo_update_counter->uses - 1;
                                                        $promo_update_counter->save();

                                                        $user_promo_entry = new UserPromoUse;
                                                        $user_promo_entry->code_id = $promos->id;
                                                        $user_promo_entry->user_id = $owner_id;
                                                        $user_promo_entry->save();

                                                        $owner = Owner::find($owner_id);
                                                        $owner->promo_count = $owner->promo_count + 1;
                                                        $owner->save();

                                                        $request->promo_id = $promos->id;
                                                        $request->promo_code = $promos->coupon_code;
                                                    }
                                                }
                                            } else {
                                                $response_array = array('success' => FALSE, 'error' => 'Promotional code is not available', 'error_code' => 505);
                                                $response_code = 200;
                                                return Response::json($response_array, $response_code);
                                            }
                                        } else {
                                            $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active on cash payment.', 'error_code' => 505);
                                            $response_code = 200;
                                            return Response::json($response_array, $response_code);
                                        }
                                    }/* else {
                                      $response_array = array('success' => FALSE, 'error' => 'Payment mode is paypal', 'error_code' => 505);
                                      $response_code = 200;
                                      return Response::json($response_array, $response_code);
                                      } */
                                } else {
                                    $response_array = array('success' => FALSE, 'error' => 'Promotion feature is not active.', 'error_code' => 505);
                                    $response_code = 200;
                                    return Response::json($response_array, $response_code);
                                }
                                /* $pcode = PromoCodes::where('coupon_code', Input::get('promo_code'))->first();

                                  if ($pcode) {

                                  $request->promo_code = $pcode->id;

                                  if ($pcode->uses == 1) {
                                  $pcode->status = 3;
                                  }
                                  $pcode->uses = $pcode->uses - 1;
                                  $pcode->save();
                                  } else {
                                  $response_array = array('success' => false, 'Invalid Promo Code', 'error_code' => 415);
                                  $response_code = 200;
                                  return Response::json($response_array, $response_code);
                                  } */
                            }

                            $request->request_start_time = date("Y-m-d H:i:s");
                            $request->save();

                            $reqserv = new RequestServices;
                            $reqserv->request_id = $request->id;
                            $reqserv->save();
                        }
                        $i = 0;
                        $first_walker_id = 0;
                        foreach ($walkers as $walker) {
                            $request_meta = new RequestMeta;
                            $request_meta->request_id = $request->id;
                            $request_meta->walker_id = $walker->id;
                            if ($i == 0) {
                                $first_walker_id = $walker->id;
                                $driver_data = array();
                                $driver_data['unique_id'] = 1;
                                $driver_data['id'] = "" . $first_walker_id;
                                $driver_data['first_name'] = "" . $walker->first_name;
                                $driver_data['last_name'] = "" . $walker->last_name;
                                $driver_data['phone'] = "" . $walker->phone;
                                /*  $driver_data['email'] = "" . $walker->email; */
                                $driver_data['picture'] = "" . $walker->picture;
                                $driver_data['bio'] = "" . $walker->bio;
                                /* $driver_data['address'] = "" . $walker->address;
                                  $driver_data['state'] = "" . $walker->state;
                                  $driver_data['country'] = "" . $walker->country;
                                  $driver_data['zipcode'] = "" . $walker->zipcode;
                                  $driver_data['login_by'] = "" . $walker->login_by;
                                  $driver_data['social_unique_id'] = "" . $walker->social_unique_id;
                                  $driver_data['is_active'] = "" . $walker->is_active;
                                  $driver_data['is_available'] = "" . $walker->is_available; */
                                $driver_data['latitude'] = "" . $walker->latitude;
                                $driver_data['longitude'] = "" . $walker->longitude;
                                /* $driver_data['is_approved'] = "" . $walker->is_approved; */
                                $driver_data['type'] = "" . $walker->type;
                                $driver_data['car_model'] = "" . $walker->car_model;
                                $driver_data['car_number'] = "" . $walker->car_number;
                                $driver_data['rating'] = $walker->rate;
                                $driver_data['num_rating'] = $walker->rate_count;
                                /* $driver_data['rating'] = DB::table('review_walker')->where('walker_id', '=', $first_walker_id)->avg('rating') ? : 0;
                                  $driver_data['num_rating'] = DB::table('review_walker')->where('walker_id', '=', $first_walker_id)->count(); */
                                $i++;
                            }
                            $request_meta->save();
                        }
                        $req = Requests::find($request->id);
                        $req->current_walker = $first_walker_id;
                        $req->save();

                        $settings = Settings::where('key', 'provider_timeout')->first();
                        $time_left = $settings->value;

                        // Send Notification
                        $walker = Walker::find($first_walker_id);
                        if ($walker) {
                            $msg_array = array();
                            $msg_array['unique_id'] = 1;
                            $msg_array['request_id'] = $request->id;
                            $msg_array['time_left_to_respond'] = $time_left;
                            $msg_array['payment_mode'] = $payment_opt;
                            $owner = Owner::find($owner_id);
                            $request_data = array();
                            $request_data['owner'] = array();
                            $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                            $request_data['owner']['picture'] = $owner->picture;
                            $request_data['owner']['phone'] = $owner->phone;
                            $request_data['owner']['address'] = $owner->address;
                            $request_data['owner']['latitude'] = $owner->latitude;
                            $request_data['owner']['longitude'] = $owner->longitude;
                            if ($d_latitude != NULL) {
                                $request_data['owner']['d_latitude'] = $d_latitude;
                                $request_data['owner']['d_longitude'] = $d_longitude;
                            }
                            $request_data['owner']['owner_dist_lat'] = $request->D_latitude;
                            $request_data['owner']['owner_dist_long'] = $request->D_longitude;
                            $request_data['owner']['payment_type'] = $payment_opt;
                            $request_data['owner']['rating'] = $owner->rate;
                            $request_data['owner']['num_rating'] = $owner->rate_count;
                            /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                              $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */
                            $request_data['dog'] = array();
                            if ($dog = Dog::find($owner->dog_id)) {

                                $request_data['dog']['name'] = $dog->name;
                                $request_data['dog']['age'] = $dog->age;
                                $request_data['dog']['breed'] = $dog->breed;
                                $request_data['dog']['likes'] = $dog->likes;
                                $request_data['dog']['picture'] = $dog->image_url;
                            }
                            $msg_array['request_data'] = $request_data;

                            $title = "New Request";
                            $message = $msg_array;
                            Log::info('response = ' . print_r($message, true));
                            Log::info('first_walker_id = ' . print_r($first_walker_id, true));
                            Log::info('New request = ' . print_r($message, true));
                            /* don't do json_encode in above line because if */
                            send_notifications($first_walker_id, "walker", $title, $message);
                        } else {
                            Log::info('No provider found in your area');

                            /* $driver = Keywords::where('id', 1)->first();
                              send_notifications($owner_id, "owner", 'No ' . $driver->keyword . ' Found', 'No ' . $driver->keyword . ' found for the selected service in your area currently'); */
                            send_notifications($owner_id, "owner", 'No ' . Config::get('app.generic_keywords.Provider') . ' Found', 'No ' . Config::get('app.generic_keywords.Provider') . ' found for the selected service in your area currently');

                            /* $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found for the selected service in your area currently', 'error_code' => 415); */
                            $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found for the selected service in your area currently', 'error_code' => 415);
                            $response_code = 200;
                            return Response::json($response_array, $response_code);
                        }
                        // Send SMS 
                        $settings = Settings::where('key', 'sms_request_created')->first();
                        $pattern = $settings->value;
                        $pattern = str_replace('%user%', $owner_data->first_name . " " . $owner_data->last_name, $pattern);
                        $pattern = str_replace('%id%', $request->id, $pattern);
                        $pattern = str_replace('%user_mobile%', $owner_data->phone, $pattern);
                        sms_notification(1, 'admin', $pattern);

                        // send email
                        /* $settings = Settings::where('key', 'email_new_request')->first();
                          $pattern = $settings->value;
                          $pattern = str_replace('%id%', $request->id, $pattern);
                          $pattern = str_replace('%url%', web_url() . "/admin/request/map/" . $request->id, $pattern);
                          $subject = "New Request Created";
                          email_notification(1, 'admin', $pattern, $subject); */
                        $settings = Settings::where('key', 'admin_email_address')->first();
                        $admin_email = $settings->value;
                        $follow_url = web_url() . "/user/signin";
                        $pattern = array('admin_eamil' => $admin_email, 'trip_id' => $request->id, 'follow_url' => $follow_url);
                        $subject = "Ride Booking Request";
                        email_notification(1, 'admin', $pattern, $subject, 'new_request', null);
                        if (!empty($driver_data)) {
                            $response_array = array(
                                'success' => true,
                                'unique_id' => 1,
                                'is_referral_active' => $referral_code_activation,
                                'is_referral_active_txt' => $referral_code_activation_txt,
                                'is_promo_active' => $promotional_code_activation,
                                'is_promo_active_txt' => $promotional_code_activation_txt,
                                'request_id' => $request->id,
                                'walker' => $driver_data,
                            );
                        } else {
                            $response_array = array(
                                'success' => false,
                                'unique_id' => 1,
                                'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found around you.',
                                'is_referral_active' => $referral_code_activation,
                                'is_referral_active_txt' => $referral_code_activation_txt,
                                'is_promo_active' => $promotional_code_activation,
                                'is_promo_active_txt' => $promotional_code_activation_txt,
                                'request_id' => $request->id,
                                'error_code' => 411,
                                'walker' => $driver_data,
                            );
                        }
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;

        DontcreateReq:
        Log::info('Request not created ');
    }

    //create crequest with fare

    public function create_request_fare() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $distance = Input::get('distance');
        $time = Input::get('time');
        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    if ($owner_data->debt > 0) {
                        $response_array = array('success' => false, 'error' => "You are already in \$$owner->debt debt", 'error_code' => 417);
                        $response_code = 200;
                        $response = Response::json($response_array, $response_code);
                        return $response;
                    }

                    if (Input::has('type')) {
                        $type = Input::get('type');
                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();

                            if (!$provider_type) {
                                $type = 1;
                            } else {
                                $type = $provider_type->id;
                            }
                        }
                        $typequery = "SELECT distinct provider_id from walker_services where type IN($type)";
                        $typewalkers = DB::select(DB::raw($typequery));

                        Log::info('typewalkers = ' . print_r($typewalkers, true));

                        if (count($typewalkers) > 0) {

                            foreach ($typewalkers as $key) {

                                $types[] = $key->provider_id;
                            }

                            $typestring = implode(",", $types);
                            Log::info('typestring = ' . print_r($typestring, true));
                        } else {
                            /* $var = Keywords::where('id', 1)->first();
                              $response_array = array('success' => false, 'error' => 'No ' . $var->keyword . ' found matching the service type.', 'error_code' => 405); */
                            $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 405);
                            $response_code = 200;
                            return Response::json($response_array, $response_code);
                        }

                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }
                        $query = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance and walker.id IN($typestring) order by distance";

                        $walkers = DB::select(DB::raw($query));
                        $walker_list = array();

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = date("Y-m-d H:i:s");
                        $request->save();

                        $reqserv = new RequestServices;
                        $reqserv->request_id = $request->id;
                        $reqserv->type = $type;
                        $reqserv->save();
                    } else {
                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }
                        $query = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance order by distance";
                        $walkers = DB::select(DB::raw($query));
                        $walker_list = array();

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = date("Y-m-d H:i:s");
                        $request->save();

                        $reqserv = new RequestServices;
                        $reqserv->request_id = $request->id;
                        $reqserv->save();
                    }
                    $i = 0;
                    $first_walker_id = 0;
                    foreach ($walkers as $walker) {
                        $request_meta = new RequestMeta;
                        $request_meta->request_id = $request->id;
                        $request_meta->walker_id = $walker->id;
                        if ($i == 0) {
                            $first_walker_id = $walker->id;
                            $i++;
                        }
                        $request_meta->save();
                    }
                    $req = Requests::find($request->id);
                    $req->current_walker = $first_walker_id;
                    $req->save();

                    $settings = Settings::where('key', 'provider_timeout')->first();
                    $time_left = $settings->value;

                    // Send Notification
                    $walker = Walker::find($first_walker_id);

                    if ($walker) {
                        $msg_array = array();
                        $msg_array['unique_id'] = 1;
                        $msg_array['request_id'] = $request->id;
                        $msg_array['time_left_to_respond'] = $time_left;
                        $owner = Owner::find($owner_id);
                        $request_data = array();
                        $request_data['owner'] = array();
                        $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                        $request_data['owner']['picture'] = $owner->picture;
                        $request_data['owner']['phone'] = $owner->phone;
                        $request_data['owner']['address'] = $owner->address;
                        $request_data['owner']['latitude'] = $owner->latitude;
                        $request_data['owner']['longitude'] = $owner->longitude;
                        $request_data['owner']['rating'] = $owner->rate;
                        $request_data['owner']['num_rating'] = $owner->rate_count;
                        /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                          $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */

                        $request_data['dog'] = array();
                        if ($dog = Dog::find($owner->dog_id)) {

                            $request_data['dog']['name'] = $dog->name;
                            $request_data['dog']['age'] = $dog->age;
                            $request_data['dog']['breed'] = $dog->breed;
                            $request_data['dog']['likes'] = $dog->likes;
                            $request_data['dog']['picture'] = $dog->image_url;
                        }
                        $msg_array['request_data'] = $request_data;

                        $title = "New Request";
                        $message = $msg_array;
                        Log::info('first_walker_id = ' . print_r($first_walker_id, true));
                        Log::info('New request = ' . print_r($message, true));
                        /* don't do json_encode in above line because if */
                        send_notifications($first_walker_id, "walker", $title, $message);
                    }

                    $pt = ProviderServices::where('provider_id', $first_walker_id)->get();

                    // Send SMS 
                    $settings = Settings::where('key', 'sms_request_created')->first();
                    $pattern = $settings->value;
                    $pattern = str_replace('%user%', $owner_data->first_name . " " . $owner_data->last_name, $pattern);
                    $pattern = str_replace('%id%', $request->id, $pattern);
                    $pattern = str_replace('%user_mobile%', $owner_data->phone, $pattern);
                    sms_notification(1, 'admin', $pattern);

                    // send email
                    /* $settings = Settings::where('key', 'email_new_request')->first();
                      $pattern = $settings->value;
                      $pattern = str_replace('%id%', $request->id, $pattern);
                      $pattern = str_replace('%url%', web_url() . "/admin/request/map/" . $request->id, $pattern);
                      $subject = "New Request Created";
                      email_notification(1, 'admin', $pattern, $subject); */
                    $settings = Settings::where('key', 'admin_email_address')->first();
                    $admin_email = $settings->value;
                    $follow_url = web_url() . "/user/signin";
                    $pattern = array('admin_eamil' => $admin_email, 'trip_id' => $request->id, 'follow_url' => $follow_url);
                    $subject = "Ride Booking Request";
                    email_notification(1, 'admin', $pattern, $subject, 'new_request', null);

                    $response_array = array(
                        'success' => true,
                        'request_id' => $request->id,
                    );
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    //fare calculator

    public function fare_calculator() {

        if (Request::isMethod('post')) {
            $token = Input::get('token');
            $owner_id = Input::get('id');
            $distance = Input::get('distance');
            $time = Input::get('time');

            $validator = Validator::make(
                            array(
                        'token' => $token,
                        'owner_id' => $owner_id,
                        'distance' => $distance,
                        'time' => $time,
                            ), array(
                        'token' => 'required',
                        'owner_id' => 'required|integer',
                        'distance' => 'required',
                        'time' => 'required',
                            )
            );

            /* $var = Keywords::where('id', 2)->first(); */

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
                $response_code = 200;
            } else {
                $is_admin = $this->isAdmin($token);

                if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                    // check for token validity
                    if (is_token_active($owner_data->token_expiry) || $is_admin) {
                        $request_typ = ProviderType::where('is_default', '=', 1)->first();

                        $setbase_distance = $request_typ->base_distance;
                        $base_price1 = $request_typ->base_price;
                        $price_per_unit_distance1 = $request_typ->price_per_unit_distance;
                        $price_per_unit_time1 = $request_typ->price_per_unit_time;
                        // Do necessary operations

                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;

                        /* $setbase_price = Settings::where('key', 'base_price')->first();
                          $base_price = $setbase_price->value; */
                        if ($unit == 0) {
                            $distanceKm = $distance * 0.001;
                            /* $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                              $price_per_unit_distance = $setdistance_price->value * $distanceKm;
                             */
                            if ($distanceKm <= $setbase_distance) {
                                $price_per_unit_distance = 0;
                            } else {
                                $price_per_unit_distance = $price_per_unit_distance1 * ($distanceKm - $setbase_distance);
                            }
                        } else {
                            $distanceMiles = $distance * 0.000621371;
                            /* $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                              $price_per_unit_distance = $setdistance_price->value * $distanceMiles; */
                            if ($distanceMiles <= $setbase_distance) {
                                $price_per_unit_distance = 0;
                            } else {
                                $price_per_unit_distance = $price_per_unit_distance1 * ($distanceMiles - $setbase_distance);
                            }
                        }
                        $timeMinutes = $time * 0.0166667;
                        /* $settime_price = Settings::where('key', 'price_per_unit_time')->first();
                          $price_per_unit_time = $settime_price->value * $timeMinutes; */
                        $price_per_unit_time = $price_per_unit_time1 * $timeMinutes;

                        /* $total = $base_price + $price_per_unit_distance + $price_per_unit_time; */
                        $total = $base_price1 + $price_per_unit_distance + $price_per_unit_time;

                        $total = $total;

                        /* $currency_selected = Keywords::find(5);
                          $cur_symb = $currency_selected->keyword; */
                        $cur_symb = Config::get('app.generic_keywords.Currency');
                        $response_array = array(
                            'success' => true,
                            'setbase_distance' => $setbase_distance,
                            'base_price' => currency_converted($base_price1),
                            'price_per_unit_distance' => currency_converted($price_per_unit_distance1),
                            'price_per_unit_time' => currency_converted($price_per_unit_time1),
                            'estimated_fare' => ceil(currency_converted($total)),
                            'currency' => $cur_symb,
                        );
                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                        $response_code = 200;
                    }
                } else {
                    if ($is_admin) {
                        /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                        $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                    } else {
                        $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                    }
                    $response_code = 200;
                }
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Get cancel request

    public function cancel_request() {

        $request_id = Input::get('request_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'request_id' => $request_id,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'request_id' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if ($request = Requests::find($request_id)) {

                        if ($request->owner_id == $owner_data->id) {

                            Requests::where('id', $request_id)->update(array('is_cancelled' => 1));
                            RequestMeta::where('request_id', $request_id)->update(array('is_cancelled' => 1));

                            if ($request->promo_id) {
                                $promo_update_counter = PromoCodes::find($request->promo_id);
                                $promo_update_counter->uses = $promo_update_counter->uses + 1;
                                $promo_update_counter->save();

                                UserPromoUse::where('user_id', '=', $owner_id)->where('code_id', '=', $request->promo_id)->delete();

                                $owner = Owner::find($owner_id);
                                $owner->promo_count = $owner->promo_count - 1;
                                $owner->save();

                                $request = Requests::find($request_id);
                                $request->promo_id = 0;
                                $request->promo_code = "";
                                $request->save();
                            }

                            if ($request->confirmed_walker) {
                                $walker = Walker::find($request->confirmed_walker);
                                $walker->is_available = 1;
                                $walker->save();
                            }
                            if ($request->current_walker) {

                                $msg_array = array();
                                $msg_array['request_id'] = $request_id;
                                $msg_array['unique_id'] = 2;

                                $owner = Owner::find($owner_id);
                                $request_data = array();
                                $request_data['owner'] = array();
                                $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                                $request_data['owner']['picture'] = $owner->picture;
                                $request_data['owner']['phone'] = $owner->phone;
                                $request_data['owner']['address'] = $owner->address;
                                $request_data['owner']['latitude'] = $owner->latitude;
                                $request_data['owner']['longitude'] = $owner->longitude;
                                $request_data['owner']['rating'] = $owner->rate;
                                $request_data['owner']['num_rating'] = $owner->rate_count;
                                /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                                  $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */

                                $request_data['dog'] = array();
                                if ($dog = Dog::find($owner->dog_id)) {
                                    $request_data['dog']['name'] = $dog->name;
                                    $request_data['dog']['age'] = $dog->age;
                                    $request_data['dog']['breed'] = $dog->breed;
                                    $request_data['dog']['likes'] = $dog->likes;
                                    $request_data['dog']['picture'] = $dog->image_url;
                                }
                                $msg_array['request_data'] = $request_data;

                                $title = "Request Cancelled";
                                $message = $msg_array;
                                send_notifications($request->current_walker, "walker", $title, $message);
                            }
                            $response_array = array(
                                'success' => true,
                            );

                            $response_code = 200;
                        } else {
                            /* $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . $var->keyword . ' ID', 'error_code' => 407); */
                            $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . Config::get('app.generic_keywords.User') . ' ID', 'error_code' => 407);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Get Request Status

    public function get_request() {

        $request_id = Input::get('request_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'request_id' => $request_id,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'request_id' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            $walker_data = "";

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    /* SEND REFERRAL & PROMO INFO */
                    $settings = Settings::where('key', 'referral_code_activation')->first();
                    $referral_code_activation = $settings->value;
                    if ($referral_code_activation) {
                        $referral_code_activation_txt = "referral on";
                    } else {
                        $referral_code_activation_txt = "referral off";
                    }

                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $promotional_code_activation = $settings->value;
                    if ($promotional_code_activation) {
                        $promotional_code_activation_txt = "promo on";
                    } else {
                        $promotional_code_activation_txt = "promo off";
                    }
                    /* SEND REFERRAL & PROMO INFO */
                    // Do necessary operations
                    if ($request = Requests::find($request_id)) {

                        if ($request->owner_id == $owner_data->id) {
                            if ($request->current_walker != 0) {

                                if ($request->confirmed_walker != 0) {
                                    $walker = Walker::where('id', $request->confirmed_walker)->first();
                                    $walker_data = array();
                                    $walker_data['unique_id'] = 1;
                                    $walker_data['id'] = $walker->id;
                                    $walker_data['first_name'] = $walker->first_name;
                                    $walker_data['last_name'] = $walker->last_name;
                                    $walker_data['phone'] = $walker->phone;
                                    $walker_data['bio'] = $walker->bio;
                                    $walker_data['picture'] = $walker->picture;
                                    $walker_data['latitude'] = $walker->latitude;
                                    $walker_data['longitude'] = $walker->longitude;
                                    if ($request->D_latitude != NULL) {
                                        $walker_data['d_latitude'] = $request->D_latitude;
                                        $walker_data['d_longitude'] = $request->D_longitude;
                                    }
                                    $walker_data['type'] = $walker->type;
                                    $walker_data['rating'] = $walker->rate;
                                    $walker_data['num_rating'] = $walker->rate_count;
                                    $walker_data['car_model'] = $walker->car_model;
                                    $walker_data['car_number'] = $walker->car_number;
                                    $walker_data['bearing'] = $walker->bearing;
                                    /* $walker_data['rating'] = DB::table('review_walker')->where('walker_id', '=', $walker->id)->avg('rating') ? : 0;
                                      $walker_data['num_rating'] = DB::table('review_walker')->where('walker_id', '=', $walker->id)->count(); */

                                    $settings = Settings::where('key', 'default_distance_unit')->first();
                                    $unit = $settings->value;
                                    if ($unit == 0) {
                                        $unit_set = 'kms';
                                    } elseif ($unit == 1) {
                                        $unit_set = 'miles';
                                    }
                                    $requestserv = RequestServices::where('request_id', $request->id)->first();
                                    $bill = array();
                                    $request_typ = ProviderType::where('id', '=', $requestserv->type)->first();
                                    /* $currency_selected = Keywords::find(5);
                                      $cur_symb = $currency_selected->keyword; */
                                    $cur_symb = Config::get('app.generic_keywords.Currency');

                                    if ($request->is_completed == 1) {
                                        $bill['unit'] = $unit_set;
                                        $bill['payment_mode'] = $request->payment_mode;
                                        $bill['distance'] = (string) $request->distance;
                                        $bill['time'] = $request->time;

                                        if ($requestserv->base_price != 0) {
                                            $bill['base_distance'] = $request_typ->base_distance;
                                            $bill['base_price'] = currency_converted($requestserv->base_price);
                                            $bill['distance_cost'] = currency_converted($requestserv->distance_cost);
                                            $bill['time_cost'] = currency_converted($requestserv->time_cost);
                                        } else {
                                            /* $setbase_price = Settings::where('key', 'base_price')->first();
                                              $bill['base_price'] = currency_converted($setbase_price->value);
                                              $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                                              $bill['distance_cost'] = currency_converted($setdistance_price->value);
                                              $settime_price = Settings::where('key', 'price_per_unit_time')->first();
                                              $bill['time_cost'] = currency_converted($settime_price->value); */
                                            $bill['base_distance'] = $request_typ->base_distance;
                                            $bill['base_price'] = currency_converted($request_typ->base_price);
                                            $bill['distance_cost'] = currency_converted($request_typ->price_per_unit_distance);
                                            $bill['time_cost'] = currency_converted($request_typ->price_per_unit_time);
                                        }
                                        if ($request->payment_mode == 2) {
                                            $bill['walker']['email'] = $walker->email;
                                            $bill['walker']['amount'] = currency_converted($request->transfer_amount);
                                            $admins = Admin::first();
                                            $bill['admin']['email'] = $admins->username;
                                            $bill['admin']['amount'] = currency_converted($request->total - $request->transfer_amount);
                                        }
                                        $bill['currency'] = $cur_symb;
                                        /* $bill['total'] = currency_converted($request->total); */
                                        $bill['main_total'] = currency_converted($request->total);
                                        $bill['total'] = currency_converted($request->total - $request->ledger_payment - $request->promo_payment);
                                        $bill['referral_bonus'] = currency_converted($request->ledger_payment);
                                        $bill['promo_bonus'] = currency_converted($request->promo_payment);
                                        $bill['payment_type'] = $request->payment_mode;
                                        $bill['is_paid'] = $request->is_paid;
                                        $discount = 0;
                                        if ($request->promo_code != "") {
                                            if ($request->promo_code != "") {
                                                $promo_code = PromoCodes::where('id', $request->promo_code)->first();
                                                if ($promo_code) {
                                                    $promo_value = $promo_code->value;
                                                    $promo_type = $promo_code->type;
                                                    if ($promo_type == 1) {
                                                        // Percent Discount
                                                        $discount = $request->total * $promo_value / 100;
                                                    } elseif ($promo_type == 2) {
                                                        // Absolute Discount
                                                        $discount = $promo_value;
                                                    }
                                                }
                                            }
                                        }
                                        $bill['promo_discount'] = currency_converted($discount);
                                        $bill['actual_total'] = currency_converted($request->total + $request->ledger_payment + $discount);
                                    }
                                    $cards = "";
                                    /* $cards['none'] = ""; */
                                    $dif_card = 0;
                                    $cardlist = Payment::where('owner_id', $owner_id)->where('is_default', 1)->first();
                                    /* $cardlist = Payment::where('id', $owner_data->default_card_id)->first(); */

                                    if (count($cardlist) >= 1) {
                                        $cards = array();
                                        $default = $cardlist->is_default;
                                        if ($default == 1) {
                                            $dif_card = $cardlist->id;
                                            $cards['is_default_text'] = "default";
                                        } else {
                                            $cards['is_default_text'] = "not_default";
                                        }
                                        $cards['card_id'] = $cardlist->id;
                                        $cards['owner_id'] = $cardlist->owner_id;
                                        $cards['customer_id'] = $cardlist->customer_id;
                                        $cards['last_four'] = $cardlist->last_four;
                                        $cards['card_token'] = $cardlist->card_token;
                                        $cards['card_type'] = $cardlist->card_type;
                                        $cards['is_default'] = $default;
                                    }

                                    $code_data = Ledger::where('owner_id', '=', $owner_data->id)->first();
                                    $owner = array();
                                    $owner['owner_lat'] = $owner_data->latitude;
                                    $owner['owner_long'] = $owner_data->longitude;
                                    $owner['owner_dist_lat'] = $request->D_latitude;
                                    $owner['owner_dist_long'] = $request->D_longitude;
                                    $owner['payment_type'] = $request->payment_mode;
                                    $owner['default_card'] = $dif_card;
                                    $owner['dest_latitude'] = $request->D_latitude;
                                    $owner['dest_longitude'] = $request->D_longitude;
                                    $owner['referral_code'] = $code_data->referral_code;
                                    $owner['is_referee'] = $owner_data->is_referee;
                                    $owner['promo_count'] = $owner_data->promo_count;



                                    $charge = array();

                                    $settings = Settings::where('key', 'default_distance_unit')->first();
                                    $unit = $settings->value;
                                    if ($unit == 0) {
                                        $unit_set = 'kms';
                                    } elseif ($unit == 1) {
                                        $unit_set = 'miles';
                                    }
                                    $charge['unit'] = $unit_set;


                                    if ($requestserv->base_price != 0) {
                                        $charge['base_distance'] = $request_typ->base_distance;
                                        $charge['base_price'] = currency_converted($requestserv->base_price);
                                        $charge['distance_price'] = currency_converted($requestserv->distance_cost);
                                        $charge['price_per_unit_time'] = currency_converted($requestserv->time_cost);
                                    } else {
                                        /* $setbase_price = Settings::where('key', 'base_price')->first();
                                          $charge['base_price'] = currency_converted($setbase_price->value);
                                          $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                                          $charge['distance_price'] = currency_converted($setdistance_price->value);
                                          $settime_price = Settings::where('key', 'price_per_unit_time')->first();
                                          $charge['price_per_unit_time'] = currency_converted($settime_price->value); */
                                        $charge['base_distance'] = $request_typ->base_distance;
                                        $charge['base_price'] = currency_converted($request_typ->base_price);
                                        $charge['distance_price'] = currency_converted($request_typ->price_per_unit_distance);
                                        $charge['price_per_unit_time'] = currency_converted($request_typ->price_per_unit_time);
                                    }
                                    $charge['total'] = currency_converted($request->total);
                                    $charge['is_paid'] = $request->is_paid;

                                    $loc1 = WalkLocation::where('request_id', $request->id)->first();
                                    $loc2 = WalkLocation::where('request_id', $request->id)->orderBy('id', 'desc')->first();
                                    if ($loc1) {
                                        $time1 = strtotime($loc2->created_at);
                                        $time2 = strtotime($loc1->created_at);
                                        $difference = intval(($time1 - $time2) / 60);
                                    } else {
                                        $difference = 0;
                                    }
                                    $difference = $request->time;


                                    $rserv = RequestServices::where('request_id', $request_id)->get();
                                    $typs = array();
                                    $typi = array();
                                    $typp = array();
                                    $total_price = 0;
                                    foreach ($rserv as $typ) {
                                        $typ1 = ProviderType::where('id', $typ->type)->first();
                                        $typ_price = ProviderServices::where('provider_id', $request->confirmed_walker)->where('type', $typ->type)->first();

                                        if ($typ_price->base_price > 0) {
                                            $typp1 = 0.00;
                                            $typp1 = $typ_price->base_price;
                                        } elseif ($typ_price->price_per_unit_distance > 0) {
                                            $typp1 = 0.00;
                                            foreach ($rserv as $key) {
                                                $typp1 = $typp1 + $key->distance_cost;
                                            }
                                        } else {
                                            $typp1 = 0.00;
                                        }
                                        $typs['name'] = $typ1->name;
                                        $typs['price'] = currency_converted($typp1);
                                        $total_price = $total_price + $typp1;
                                        array_push($typi, $typs);
                                    }
                                    $bill['type'] = $typi;

                                    $response_array = array(
                                        'success' => true,
                                        'unique_id' => 1,
                                        'status' => $request->status,
                                        'is_referral_active' => $referral_code_activation,
                                        'is_referral_active_txt' => $referral_code_activation_txt,
                                        'is_promo_active' => $promotional_code_activation,
                                        'is_promo_active_txt' => $promotional_code_activation_txt,
                                        'confirmed_walker' => $request->confirmed_walker,
                                        'is_walker_started' => $request->is_walker_started,
                                        'is_walker_arrived' => $request->is_walker_arrived,
                                        'is_walk_started' => $request->is_started,
                                        'is_completed' => $request->is_completed,
                                        'is_walker_rated' => $request->is_walker_rated,
                                        'is_cancelled' => $request->is_cancelled,
                                        'dest_latitude' => $request->D_latitude,
                                        'dest_longitude' => $request->D_longitude,
                                        'promo_id' => $request->promo_id,
                                        'promo_code' => $request->promo_code,
                                        'walker' => $walker_data,
                                        'time' => $difference,
                                        'bill' => $bill,
                                        'owner' => $owner,
                                        'card_details' => $cards,
                                        'charge_details' => $charge,
                                    );

                                    $user_timezone = $walker->timezone;
                                    $default_timezone = Config::get('app.timezone');

                                    $accepted_time = get_user_time($default_timezone, $user_timezone, $request->request_start_time);

                                    $time = DB::table('walk_location')
                                            ->where('request_id', $request_id)
                                            ->min('created_at');

                                    $end_time = get_user_time($default_timezone, $user_timezone, $time);

                                    $response_array['accepted_time'] = $accepted_time;
                                    if ($request->is_started == 1) {
                                        $response_array['start_time'] = DB::table('walk_location')
                                                ->where('request_id', $request_id)
                                                ->min('created_at');

                                        $settings = Settings::where('key', 'default_distance_unit')->first();
                                        $unit = $settings->value;

                                        $response_array['distance'] = DB::table('walk_location')
                                                ->where('request_id', $request_id)
                                                ->max('distance');

                                        $response_array['distance'] = (string) convert($response_array['distance'], $unit);
                                        if ($unit == 0) {
                                            $unit_set = 'kms';
                                        } elseif ($unit == 1) {
                                            $unit_set = 'miles';
                                        }
                                        $response_array['unit'] = $unit_set;
                                    }
                                    if ($request->is_completed == 1) {
                                        $response_array['end_time'] = $end_time;
                                    }
                                } else {
                                    if ($request->current_walker != 0) {
                                        $walker = Walker::find($request->current_walker);
                                        $walker_data = array();
                                        $walker_data['unique_id'] = 1;
                                        $walker_data['id'] = $walker->id;
                                        $walker_data['first_name'] = $walker->first_name;
                                        $walker_data['last_name'] = $walker->last_name;
                                        $walker_data['phone'] = $walker->phone;
                                        $walker_data['bio'] = $walker->bio;
                                        $walker_data['picture'] = $walker->picture;
                                        $walker_data['latitude'] = $walker->latitude;
                                        $walker_data['longitude'] = $walker->longitude;
                                        $walker_data['type'] = $walker->type;
                                        $walker_data['car_model'] = $walker->car_model;
                                        $walker_data['car_number'] = $walker->car_number;
                                        $walker_data['bearing'] = $walker->bearing;
                                        // $walker_data['payment_type'] = $request->payment_mode;
                                        $walker_data['rating'] = $walker->rate;
                                        $walker_data['num_rating'] = $walker->rate_count;
                                    }
                                    $cards = "";
                                    /* $cards['none'] = ""; */
                                    $dif_card = 0;
                                    $cardlist = Payment::where('owner_id', $owner_id)->where('is_default', 1)->first();
                                    /* $cardlist = Payment::where('id', $owner_data->default_card_id)->first(); */

                                    if (count($cardlist) >= 1) {
                                        $cards = array();
                                        $default = $cardlist->is_default;
                                        if ($default == 1) {
                                            $dif_card = $cardlist->id;
                                            $cards['is_default_text'] = "default";
                                        } else {
                                            $cards['is_default_text'] = "not_default";
                                        }
                                        $cards['card_id'] = $cardlist->id;
                                        $cards['owner_id'] = $cardlist->owner_id;
                                        $cards['customer_id'] = $cardlist->customer_id;
                                        $cards['last_four'] = $cardlist->last_four;
                                        $cards['card_token'] = $cardlist->card_token;
                                        $cards['card_type'] = $cardlist->card_type;
                                        $cards['is_default'] = $default;
                                    }
                                    $code_data = Ledger::where('owner_id', '=', $owner_data->id)->first();
                                    $owner = array();
                                    $owner['owner_lat'] = $owner_data->latitude;
                                    $owner['owner_long'] = $owner_data->longitude;
                                    $owner['owner_dist_lat'] = $request->D_latitude;
                                    $owner['owner_dist_long'] = $request->D_longitude;
                                    $owner['payment_type'] = $request->payment_mode;
                                    $owner['default_card'] = $dif_card;
                                    $owner['dest_latitude'] = $request->D_latitude;
                                    $owner['dest_longitude'] = $request->D_longitude;
                                    $owner['referral_code'] = $code_data->referral_code;
                                    $owner['is_referee'] = $owner_data->is_referee;
                                    $owner['promo_count'] = $owner_data->promo_count;
                                    /* $driver = Keywords::where('id', 1)->first(); */
                                    $requestserv = RequestServices::where('request_id', $request->id)->first();
                                    $charge = array();
                                    $request_typ = ProviderType::where('id', '=', $requestserv->type)->first();
                                    $settings = Settings::where('key', 'default_distance_unit')->first();
                                    $unit = $settings->value;
                                    if ($unit == 0) {
                                        $unit_set = 'kms';
                                    } elseif ($unit == 1) {
                                        $unit_set = 'miles';
                                    }
                                    $charge['unit'] = $unit_set;
                                    if ($requestserv->base_price != 0) {
                                        $charge['base_distance'] = $request_typ->base_distance;
                                        $charge['base_price'] = currency_converted($requestserv->base_price);
                                        $charge['distance_price'] = currency_converted($requestserv->distance_cost);
                                        $charge['price_per_unit_time'] = currency_converted($requestserv->time_cost);
                                    } else {
                                        /* $setbase_price = Settings::where('key', 'base_price')->first();
                                          $charge['base_price'] = currency_converted($setbase_price->value);
                                          $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                                          $charge['distance_price'] = currency_converted($setdistance_price->value);
                                          $settime_price = Settings::where('key', 'price_per_unit_time')->first();
                                          $charge['price_per_unit_time'] = currency_converted($settime_price->value); */
                                        $charge['base_distance'] = $request_typ->base_distance;
                                        $charge['base_price'] = currency_converted($request_typ->base_price);
                                        $charge['distance_price'] = currency_converted($request_typ->price_per_unit_distance);
                                        $charge['price_per_unit_time'] = currency_converted($request_typ->price_per_unit_time);
                                    }
                                    $charge['total'] = currency_converted($request->total);
                                    $charge['is_paid'] = $request->is_paid;
                                    $response_array = array(
                                        'success' => true,
                                        'unique_id' => 1,
                                        'status' => $request->status,
                                        'is_referral_active' => $referral_code_activation,
                                        'is_referral_active_txt' => $referral_code_activation_txt,
                                        'is_promo_active' => $promotional_code_activation,
                                        'is_promo_active_txt' => $promotional_code_activation_txt,
                                        'confirmed_walker' => 0,
                                        'is_walker_started' => $request->is_walker_started,
                                        'is_walker_arrived' => $request->is_walker_arrived,
                                        'is_walk_started' => $request->is_started,
                                        'is_completed' => $request->is_completed,
                                        'is_walker_rated' => $request->is_walker_rated,
                                        'is_cancelled' => $request->is_cancelled,
                                        'dest_latitude' => $request->D_latitude,
                                        'dest_longitude' => $request->D_longitude,
                                        'promo_id' => $request->promo_id,
                                        'promo_code' => $request->promo_code,
                                        'walker' => $walker_data,
                                        'bill' => "",
                                        'owner' => $owner,
                                        'card_details' => $cards,
                                        'charge_details' => $charge,
                                        'confirmed_walker' => 0,
                                        'error_code' => 484,
                                        /* 'error' => 'Searching for ' . $driver->keyword . 's.', */
                                        'error' => 'Searching for ' . Config::get('app.generic_keywords.Provider') . 's.',
                                    );
                                }
                            } else {
                                /* $driver = Keywords::where('id', 1)->first(); */
                                if ($request->current_walker != 0) {
                                    $walker = Walker::find($request->current_walker);
                                    $walker_data = array();
                                    $walker_data['unique_id'] = 1;
                                    $walker_data['id'] = $walker->id;
                                    $walker_data['first_name'] = $walker->first_name;
                                    $walker_data['last_name'] = $walker->last_name;
                                    $walker_data['phone'] = $walker->phone;
                                    $walker_data['bio'] = $walker->bio;
                                    $walker_data['picture'] = $walker->picture;
                                    $walker_data['latitude'] = $walker->latitude;
                                    $walker_data['longitude'] = $walker->longitude;
                                    $walker_data['type'] = $walker->type;
                                    $walker_data['car_model'] = $walker->car_model;
                                    $walker_data['car_number'] = $walker->car_number;
                                    $walker_data['bearing'] = $walker->bearing;
                                    // $walker_data['payment_type'] = $request->payment_mode;
                                    $walker_data['rating'] = $walker->rate;
                                    $walker_data['num_rating'] = $walker->rate_count;
                                }
                                $cards = "";
                                /* $cards['none'] = ""; */
                                $dif_card = 0;
                                $cardlist = Payment::where('owner_id', $owner_id)->where('is_default', 1)->first();
                                /* $cardlist = Payment::where('id', $owner_data->default_card_id)->first(); */

                                if (count($cardlist) >= 1) {
                                    $cards = array();
                                    $default = $cardlist->is_default;
                                    if ($default == 1) {
                                        $dif_card = $cardlist->id;
                                        $cards['is_default_text'] = "default";
                                    } else {
                                        $cards['is_default_text'] = "not_default";
                                    }
                                    $cards['card_id'] = $cardlist->id;
                                    $cards['owner_id'] = $cardlist->owner_id;
                                    $cards['customer_id'] = $cardlist->customer_id;
                                    $cards['last_four'] = $cardlist->last_four;
                                    $cards['card_token'] = $cardlist->card_token;
                                    $cards['card_type'] = $cardlist->card_type;
                                    $cards['is_default'] = $default;
                                }
                                $code_data = Ledger::where('owner_id', '=', $owner_data->id)->first();
                                $owner = array();
                                $owner['owner_lat'] = $owner_data->latitude;
                                $owner['owner_long'] = $owner_data->longitude;
                                $owner['owner_dist_lat'] = $request->D_latitude;
                                $owner['owner_dist_long'] = $request->D_longitude;
                                $owner['payment_type'] = $request->payment_mode;
                                $owner['default_card'] = $dif_card;
                                $owner['dest_latitude'] = $request->D_latitude;
                                $owner['dest_longitude'] = $request->D_longitude;
                                $owner['referral_code'] = $code_data->referral_code;
                                $owner['is_referee'] = $owner_data->is_referee;
                                $owner['promo_count'] = $owner_data->promo_count;
                                /* $driver = Keywords::where('id', 1)->first(); */
                                $requestserv = RequestServices::where('request_id', $request->id)->first();
                                $charge = array();
                                $request_typ = ProviderType::where('id', '=', $requestserv->type)->first();
                                $settings = Settings::where('key', 'default_distance_unit')->first();
                                $unit = $settings->value;
                                if ($unit == 0) {
                                    $unit_set = 'kms';
                                } elseif ($unit == 1) {
                                    $unit_set = 'miles';
                                }
                                $charge['unit'] = $unit_set;
                                if ($requestserv->base_price != 0) {
                                    $charge['base_distance'] = $request_typ->base_distance;
                                    $charge['base_price'] = currency_converted($requestserv->base_price);
                                    $charge['distance_price'] = currency_converted($requestserv->distance_cost);
                                    $charge['price_per_unit_time'] = currency_converted($requestserv->time_cost);
                                } else {
                                    /* $setbase_price = Settings::where('key', 'base_price')->first();
                                      $charge['base_price'] = currency_converted($setbase_price->value);
                                      $setdistance_price = Settings::where('key', 'price_per_unit_distance')->first();
                                      $charge['distance_price'] = currency_converted($setdistance_price->value);
                                      $settime_price = Settings::where('key', 'price_per_unit_time')->first();
                                      $charge['price_per_unit_time'] = currency_converted($settime_price->value); */
                                    $charge['base_distance'] = $request_typ->base_distance;
                                    $charge['base_price'] = currency_converted($request_typ->base_price);
                                    $charge['distance_price'] = currency_converted($request_typ->price_per_unit_distance);
                                    $charge['price_per_unit_time'] = currency_converted($request_typ->price_per_unit_time);
                                }
                                $charge['total'] = currency_converted($request->total);
                                $charge['is_paid'] = $request->is_paid;
                                $response_array = array(
                                    'success' => false,
                                    'unique_id' => 1,
                                    'status' => $request->status,
                                    'is_referral_active' => $referral_code_activation,
                                    'is_referral_active_txt' => $referral_code_activation_txt,
                                    'is_promo_active' => $promotional_code_activation,
                                    'is_promo_active_txt' => $promotional_code_activation_txt,
                                    'confirmed_walker' => 0,
                                    'is_walker_started' => $request->is_walker_started,
                                    'is_walker_arrived' => $request->is_walker_arrived,
                                    'is_walk_started' => $request->is_started,
                                    'is_completed' => $request->is_completed,
                                    'is_walker_rated' => $request->is_walker_rated,
                                    'is_cancelled' => $request->is_cancelled,
                                    'dest_latitude' => $request->D_latitude,
                                    'dest_longitude' => $request->D_longitude,
                                    'promo_id' => $request->promo_id,
                                    'promo_code' => $request->promo_code,
                                    'walker' => $walker_data,
                                    'bill' => "",
                                    'owner' => $owner,
                                    'card_details' => $cards,
                                    'charge_details' => $charge,
                                    'current_walker' => 0,
                                    'error_code' => 483,
                                    /* 'error' => 'No ' . $driver->keyword . 's are available currently. Please try after sometime.', */
                                    'error' => 'No ' . Config::get('app.generic_keywords.Provider') . 's are available currently. Please try after sometime.',
                                );
                            }
                            $response_code = 200;
                        } else {
                            /* $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . $var->keyword . ' ID', 'error_code' => 407); */
                            $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . Config::get('app.generic_keywords.User') . ' ID', 'error_code' => 407);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // Get Request Status


    public function get_request_location() {

        $request_id = Input::get('request_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'request_id' => $request_id,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'request_id' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if ($request = Requests::find($request_id)) {

                        if ($request->owner_id == $owner_data->id) {

                            if ($request->confirmed_walker != 0) {
                                if ($request->is_started == 0) {
                                    $walker = Walker::find($request->confirmed_walker);
                                    $distance = 0;
                                } else {
                                    $walker = WalkLocation::where('request_id', $request->id)->orderBy('created_at', 'desc')->first();
                                    $distance = WalkLocation::where('request_id', $request->id)->max('distance');
                                }

                                $settings = Settings::where('key', 'default_distance_unit')->first();
                                $unit = $settings->value;
                                if ($unit == 0) {
                                    $unit_set = 'kms';
                                } elseif ($unit == 1) {
                                    $unit_set = 'miles';
                                }
                                $distance = convert($distance, $unit);

                                $loc1 = WalkLocation::where('request_id', $request->id)->first();
                                $loc2 = WalkLocation::where('request_id', $request->id)->orderBy('id', 'desc')->first();
                                if ($loc1) {
                                    $time1 = strtotime($loc2->created_at);
                                    $time2 = strtotime($loc1->created_at);
                                    $difference = intval(($time1 - $time2) / 60);
                                } else {
                                    $difference = 0;
                                }
                                $difference = $request->time;

                                $response_array = array(
                                    'success' => true,
                                    'latitude' => $walker->latitude,
                                    'longitude' => $walker->longitude,
                                    'bearing' => $walker->bearing,
                                    'distance' => (string) $distance,
                                    'time' => $difference,
                                    'unit' => $unit_set
                                );
                            } else {
                                $response_array = array(
                                    'success' => false,
                                    'error' => 'Walker not Confirmed yet',
                                    'error_code' => 421,
                                );
                            }
                            $response_code = 200;
                        } else {
                            /* $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . $var->keyword . ' ID', 'error_code' => 407); */
                            $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with ' . Config::get('app.generic_keywords.User') . ' ID', 'error_code' => 407);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    // check status and Send Request to walker
    // if request not timed out do nothing
    // else send new request
    // if user accepted change stat of request

    public function schedule_request() {
        /* Cronjob counter */
        /* echo asset_url() . "/cron_count.txt"; */
        $css_msg = file(asset_url() . "/cron_count.txt");
        if ($css_msg[0] > '100') {
            $css_msg[0] = 0;
        } else {
            $css_msg[0] ++;
        }
        /* echo $css_msg[0]; */
        $t = file_put_contents(public_path() . '/cron_count.txt', $css_msg[0]);
        $css_msg[0];
        /* Cronjob counter END */

        $time = date("Y-m-d H:i:s");
        $timezone_app = Config::get('app.timezone');
        date_default_timezone_set($timezone_app);
        $timezone_sys = date_default_timezone_get();

        $query = "SELECT request.*,TIMESTAMPDIFF(SECOND,request_start_time, '$time') as diff from request where status = 0 and is_cancelled = 0";
        $results = DB::select(DB::raw($query));

        /* SEND REFERRAL & PROMO INFO */
        $settings = Settings::where('key', 'referral_code_activation')->first();
        $referral_code_activation = $settings->value;
        if ($referral_code_activation) {
            $referral_code_activation_txt = "referral on";
        } else {
            $referral_code_activation_txt = "referral off";
        }

        $settings = Settings::where('key', 'promotional_code_activation')->first();
        $promotional_code_activation = $settings->value;
        if ($promotional_code_activation) {
            $promotional_code_activation_txt = "promo on";
        } else {
            $promotional_code_activation_txt = "promo off";
        }
        /* SEND REFERRAL & PROMO INFO */
        $driver_data = "";

        foreach ($results as $result) {
            $settings = Settings::where('key', 'provider_timeout')->first();
            $timeout = $settings->value;
            if ($result->diff >= $timeout) {
                // Archiving Old Walker
                RequestMeta::where('request_id', '=', $result->id)->where('walker_id', '=', $result->current_walker)->update(array('status' => 2));
                $request = Requests::where('id', $result->id)->first();
                $request_meta = RequestMeta::where('request_id', '=', $result->id)->where('status', '=', 0)->orderBy('created_at')->first();
                // update request
                if (isset($request_meta->walker_id)) {
                    // assign new walker
                    Requests::where('id', '=', $result->id)->update(array('current_walker' => $request_meta->walker_id, 'request_start_time' => date("Y-m-d H:i:s")));

                    // Send Notification

                    $walker = Walker::find($request_meta->walker_id);
                    $settings = Settings::where('key', 'provider_timeout')->first();
                    $time_left = $settings->value;

                    $owner = Owner::find($result->owner_id);

                    $msg_array = array();
                    $msg_array['unique_id'] = 1;
                    $msg_array['request_id'] = $request->id;
                    $msg_array['time_left_to_respond'] = $time_left;

                    $msg_array['payment_mode'] = $request->payment_mode;
                    $msg_array['client_profile'] = array();
                    $msg_array['client_profile']['name'] = $owner->first_name . " " . $owner->last_name;
                    $msg_array['client_profile']['picture'] = $owner->picture;
                    $msg_array['client_profile']['bio'] = $owner->bio;
                    $msg_array['client_profile']['address'] = $owner->address;
                    $msg_array['client_profile']['phone'] = $owner->phone;

                    $owner = Owner::find($result->owner_id);
                    $request_data = array();
                    $request_data['owner'] = array();
                    $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                    $request_data['owner']['picture'] = $owner->picture;
                    $request_data['owner']['phone'] = $owner->phone;
                    $request_data['owner']['address'] = $owner->address;
                    $request_data['owner']['latitude'] = $owner->latitude;
                    $request_data['owner']['longitude'] = $owner->longitude;
                    if ($request->d_latitude != NULL) {
                        $request_data['owner']['d_latitude'] = $request->D_latitude;
                        $request_data['owner']['d_longitude'] = $request->D_longitude;
                    }
                    $request_data['owner']['rating'] = $owner->rate;
                    $request_data['owner']['num_rating'] = $owner->rate_count;
                    /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                      $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */
                    $msg_array['request_data'] = $request_data;

                    $title = "New Request";

                    $message = $msg_array;
                    Log::info('New Request = ' . print_r($message, true));
                    send_notifications($request_meta->walker_id, "walker", $title, $message);
                    $driver_data = array();
                    $driver_data['unique_id'] = 1;
                    $driver_data['id'] = "" . $walker->id;
                    $driver_data['first_name'] = "" . $walker->first_name;
                    $driver_data['last_name'] = "" . $walker->last_name;
                    $driver_data['phone'] = "" . $walker->phone;
                    /*  $driver_data['email'] = "" . $walker->email; */
                    $driver_data['picture'] = "" . $walker->picture;
                    $driver_data['bio'] = "" . $walker->bio;
                    /* $driver_data['address'] = "" . $walker->address;
                      $driver_data['state'] = "" . $walker->state;
                      $driver_data['country'] = "" . $walker->country;
                      $driver_data['zipcode'] = "" . $walker->zipcode;
                      $driver_data['login_by'] = "" . $walker->login_by;
                      $driver_data['social_unique_id'] = "" . $walker->social_unique_id;
                      $driver_data['is_active'] = "" . $walker->is_active;
                      $driver_data['is_available'] = "" . $walker->is_available; */
                    $driver_data['latitude'] = "" . $walker->latitude;
                    $driver_data['longitude'] = "" . $walker->longitude;
                    /* $driver_data['is_approved'] = "" . $walker->is_approved; */
                    $driver_data['type'] = "" . $walker->type;
                    $driver_data['car_model'] = "" . $walker->car_model;
                    $driver_data['car_number'] = "" . $walker->car_number;
                    $driver_data['rating'] = $walker->rate;
                    $driver_data['num_rating'] = $walker->rate_count;
                    /* $driver_data['rating'] = DB::table('review_walker')->where('walker_id', '=', $walker->id)->avg('rating') ? : 0;
                      $driver_data['num_rating'] = DB::table('review_walker')->where('walker_id', '=', $walker->id)->count(); */
                    $client_push_data = array(
                        'success' => true,
                        'unique_id' => 1,
                        'is_referral_active' => $referral_code_activation,
                        'is_referral_active_txt' => $referral_code_activation_txt,
                        'is_promo_active' => $promotional_code_activation,
                        'is_promo_active_txt' => $promotional_code_activation_txt,
                        'request_id' => $result->id,
                        'walker' => $driver_data,
                    );
                    $message1 = $client_push_data;
                    $owner_data = Owner::find($result->owner_id);
                    $title1 = "New " . Config::get('app.generic_keywords.Provider') . " assigned";
                    send_notifications($owner_data->id, "owner", $title1, $message1);
                } else {
                    $owner = Owner::find($result->owner_id);
                    /* CLIENT PUSH FOR GETTING DRIVER DETAILS */
                    $client_push_data = array(
                        'success' => false,
                        'unique_id' => 1,
                        'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found around you.',
                        'is_referral_active' => $referral_code_activation,
                        'is_referral_active_txt' => $referral_code_activation_txt,
                        'is_promo_active' => $promotional_code_activation,
                        'is_promo_active_txt' => $promotional_code_activation_txt,
                        'request_id' => $result->id,
                        'error_code' => 411,
                        'walker' => $driver_data,
                    );
                    $message1 = $client_push_data;
                    $owner_data = Owner::find($result->owner_id);
                    $title1 = "No " . Config::get('app.generic_keywords.Provider') . " Found.";
                    /* if ($owner_data->is_deleted == 0) { */
                    send_notifications($owner_data->id, "owner", $title1, $message1);
                    /* } */
                    /* CLIENT PUSH FOR GETTING DRIVER DETAILS END */
                    // request ended
                    if ($result->promo_id) {
                        $promo_update_counter = PromoCodes::find($result->promo_id);
                        $promo_update_counter->uses = $promo_update_counter->uses + 1;
                        $promo_update_counter->save();

                        UserPromoUse::where('user_id', '=', $result->owner_id)->where('code_id', '=', $result->promo_id)->delete();

                        $owner = Owner::find($result->owner_id);
                        $owner->promo_count = $owner->promo_count - 1;
                        $owner->save();

                        $request = Requests::find($result->id);
                        $request->promo_id = 0;
                        $request->promo_code = "";
                        $request->save();
                    }
                    Requests::where('id', '=', $result->id)->update(array('current_walker' => 0, 'status' => 1));



                    /* $driver = Keywords::where('id', 1)->first(); */
                    $owne = Owner::where('id', $result->owner_id)->first();
                    /* $driver_keyword = $driver->keyword; */
                    $driver_keyword = Config::get('app.generic_keywords.Provider');
                    $owner_data_id = $owne->id;
                    send_notifications($owner_data_id, "owner", 'No ' . $driver_keyword . ' Found', 'No ' . $driver_keyword . ' are available right now in your area. Kindly try after sometime.');

                    $owner = Owner::find($result->owner_id);

                    $settings = Settings::where('key', 'sms_request_unanswered')->first();
                    $pattern = $settings->value;
                    $pattern = str_replace('%id%', $result->id, $pattern);
                    $pattern = str_replace('%user%', $owner->first_name, $pattern);
                    $pattern = str_replace('%user_mobile%', $owner->phone, $pattern);
                    sms_notification(1, 'admin', $pattern);

                    // send email
                    /* $settings = Settings::where('key', 'email_request_unanswered')->first();
                      $pattern = $settings->value;
                      $pattern = str_replace('%id%', $result->id, $pattern);
                      $pattern = str_replace('%url%', web_url() . "/admin/request/map/" . $result->id, $pattern);
                      $subject = "New Request Unanswered";
                      email_notification(1, 'admin', $pattern, $subject); */
                    $settings = Settings::where('key', 'admin_email_address')->first();
                    $admin_email = $settings->value;
                    $follow_url = web_url() . "/user/signin";
                    $pattern = array('admin_eamil' => $admin_email);
                    $subject = "New Request Unansweres";
                    email_notification(1, 'admin', $pattern, $subject, 'request_not_answered', null);
                }
            }
        }
    }

    // Request in Progress

    public function request_in_progress() {


        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer'
                        )
        );

        /* $var = Keywords::where('id', 2)->first(); */

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    $request = Requests::where('status', '=', 1)->where('is_completed', '=', 0)->where('is_cancelled', '=', 0)->where('owner_id', '=', $owner_id)->where('current_walker', '!=', 0)->orderBy('created_at', 'desc')->first();
                    if ($request) {
                        $request_id = $request->id;
                    } else {
                        $request_id = -1;
                    }
                    $response_array = array(
                        'request_id' => $request_id,
                        'success' => true,
                    );
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function create_request_later() {
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $date_time = Input::get('datetime');

        // dd(date('Y-m-d h:i:s', strtotime("$date_time + 2 hours")));


        $validator = Validator::make(
                        array(
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'datetime' => $date_time,
                        ), array(
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'datetime' => 'required',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations


                    if ($owner_data->debt > 0) {

                        $response_array = array('success' => false, 'error' => "You are already in \$$owner->debt debt", 'error_code' => 417);
                        $response_code = 200;
                        $response = Response::json($response_array, $response_code);
                        return $response;
                    }

                    if (Input::has('type')) {
                        $type = Input::get('type');
                        if (!$type) {
                            // choose default type
                            $provider_type = ProviderType::where('is_default', 1)->first();

                            if (!$provider_type) {
                                $type = 1;
                            } else {
                                $type = $provider_type->id;
                            }
                        }


                        $typequery = "SELECT distinct provider_id from walker_services where type IN($type)";
                        $typewalkers = DB::select(DB::raw($typequery));
                        Log::info('typewalkers = ' . print_r($typewalkers, true));
                        foreach ($typewalkers as $key) {
                            $types[] = $key->provider_id;
                        }
                        $typestring = implode(",", $types);
                        Log::info('typestring = ' . print_r($typestring, true));

                        if ($typestring == '') {
                            /* $driver = Keywords::where('id', 1)->first();
                              $response_array = array('success' => false, 'error' => 'No ' . $driver->keyword . ' found matching the service type.', 'error_code' => 405); */
                            $response_array = array('success' => false, 'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found matching the service type.', 'error_code' => 405);
                            $response_code = 200;
                            return Response::json($response_array, $response_code);
                        }
                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }
                        $query1 = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance and walker.id IN($typestring);";

                        $ssstrings = DB::select(DB::raw($query1));
                        foreach ($ssstrings as $ssstrin) {
                            $ssstri[] = $ssstrin->id;
                        }
                        $ssstring = implode(",", $ssstri);

                        $datewant = new DateTime($date_time);
                        $datetime = $datewant->format('Y-m-d H:i:s');

                        $dategiven = $datewant->sub(new DateInterval('P0Y0M0DT1H59M59S'))->format('Y-m-d H:i:s');
                        $end_time = $datewant->add(new DateInterval('P0Y0M0DT1H59M59S'))->format('Y-m-d H:i:s');


                        /* $setting = Settings::where('key', 'allow_calendar')->first();
                          if ($setting->value == 1)
                          $pvquery = "SELECT distinct provider_id from provider_availability where start <= '" . $datetime . "' and end >= '" . $datetime . "' and provider_id IN($ssstring) and provider_id NOT IN(SELECT confirmed_walker FROM request where request_start_time>='" . $dategiven . "' and request_start_time<='" . $end_time . "');";
                          else */
                        $pvquery = "SELECT id from walker where id IN($ssstring) and id NOT IN(SELECT confirmed_walker FROM request where request_start_time>='" . $dategiven . "' and request_start_time<='" . $end_time . "');";
                        $pvques = DB::select(DB::raw($pvquery));
                        //  dd($pvques);
                        $ssstr = array();
                        foreach ($pvques as $ssstn) {
                            $ssstr[] = $ssstn->provider_id;
                        }
                        $pvque = implode(",", $ssstr);
                        $walkers = array();
                        if ($pvque) {
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            if ($unit == 0) {
                                $multiply = 1.609344;
                            } elseif ($unit == 1) {
                                $multiply = 1;
                            }
                            $query = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance and walker.id IN($typestring) and id IN($pvque) order by distance;";

                            $walkers = DB::select(DB::raw($query));
                        }
                        $walker_list = array();

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = $datetime;
                        $request->later = 1;
                        if (Input::has('cod')) {
                            if (Input::get('cod') == 1) {
                                $request->cod = 1;
                            } else {
                                $request->cod = 0;
                            }
                        }
                        $request->save();

                        $reqserv = new RequestServices;
                        $reqserv->request_id = $request->id;
                        $reqserv->type = $type;
                        $reqserv->save();
                    } else {
                        $settings = Settings::where('key', 'default_search_radius')->first();
                        $distance = $settings->value;
                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $multiply = 1.609344;
                        } elseif ($unit == 1) {
                            $multiply = 1;
                        }
                        $query1 = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance;";

                        $ssstrings = DB::select(DB::raw($query1));
                        foreach ($ssstrings as $ssstrin) {
                            $ssstri[] = $ssstrin->id;
                        }
                        $ssstring = implode(",", $ssstri);

                        $datewant = new DateTime($date_time);
                        $datetime = $datewant->format('Y-m-d H:i:s');

                        $dategiven = $datewant->sub(new DateInterval('P0Y0M0DT1H59M59S'))->format('Y-m-d H:i:s');
                        $end_time = $datewant->add(new DateInterval('P0Y0M0DT1H59M59S'))->format('Y-m-d H:i:s');

                        /* $setting = Settings::where('key', 'allow_calendar')->first();
                          if ($setting->value == 1)
                          $pvquery = "SELECT distinct provider_id from provider_availability where start <= '" . $datetime . "' and end >= '" . $datetime . "' and provider_id IN($ssstring) and provider_id NOT IN(SELECT confirmed_walker FROM request where request_start_time>='" . $dategiven . "' and request_start_time<='" . $end_time . "');";
                          else */
                        $pvquery = "SELECT id from walker where id IN($ssstring) and id NOT IN(SELECT confirmed_walker FROM request where request_start_time>='" . $dategiven . "' and request_start_time<='" . $end_time . "');";

                        $pvques = DB::select(DB::raw($pvquery));

                        $ssstr = array();
                        foreach ($pvques as $ssstn) {
                            $ssstr[] = $ssstn->provider_id;
                        }
                        $pvque = implode(",", $ssstr);
                        $walkers = array();
                        if ($pvque) {
                            $settings = Settings::where('key', 'default_distance_unit')->first();
                            $unit = $settings->value;
                            if ($unit == 0) {
                                $multiply = 1.609344;
                            } elseif ($unit == 1) {
                                $multiply = 1;
                            }
                            $query = "SELECT walker.id, ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ,8) as distance from walker where is_available = 1 and is_active = 1 and is_approved = 1 and ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) ,8) <= $distance and id IN($pvque) order by distance;";

                            $walkers = DB::select(DB::raw($query));
                        }
                        $walker_list = array();

                        $owner = Owner::find($owner_id);
                        $owner->latitude = $latitude;
                        $owner->longitude = $longitude;
                        $owner->save();

                        $request = new Requests;
                        $request->owner_id = $owner_id;
                        $request->request_start_time = $datetime;
                        $request->save();

                        $reqserv = new RequestServices;
                        $reqserv->request_id = $request->id;
                        $reqserv->save();
                    }
                    $i = 0;
                    $first_walker_id = 0;
                    if ($walkers) {
                        foreach ($walkers as $walker) {
                            $request_meta = new RequestMeta;
                            $request_meta->request_id = $request->id;
                            $request_meta->walker_id = $walker->id;
                            if ($i == 0) {
                                $first_walker_id = $walker->id;
                                $i++;
                            }
                            $request_meta->save();
                        }

                        $req = Requests::find($request->id);
                        $req->current_walker = $first_walker_id;
                        $req->save();
                    }
                    $settings = Settings::where('key', 'provider_timeout')->first();
                    $time_left = $settings->value;

                    // Send Notification
                    $walker = Walker::find($first_walker_id);
                    if ($walker) {
                        $msg_array = array();
                        $msg_array['unique_id'] = 3;
                        $msg_array['request_id'] = $request->id;
                        $msg_array['time_left_to_respond'] = $time_left;
                        $owner = Owner::find($owner_id);
                        $request_data = array();
                        $request_data['owner'] = array();
                        $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                        $request_data['owner']['picture'] = $owner->picture;
                        $request_data['owner']['phone'] = $owner->phone;
                        $request_data['owner']['address'] = $owner->address;
                        $request_data['owner']['latitude'] = $owner->latitude;
                        $request_data['owner']['longitude'] = $owner->longitude;
                        $request_data['owner']['rating'] = $owner->rate;
                        $request_data['owner']['num_rating'] = $owner->rate_count;
                        /* $request_data['owner']['rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->avg('rating') ? : 0;
                          $request_data['owner']['num_rating'] = DB::table('review_dog')->where('owner_id', '=', $owner->id)->count(); */
                        $date_want = new DateTime($date_time);
                        $datetime1 = $date_want->format('Y-m-d H:i:s');
                        $request_data['datetime'] = $datetime1;
                        $request_data['dog'] = array();
                        if ($dog = Dog::find($owner->dog_id)) {

                            $request_data['dog']['name'] = $dog->name;
                            $request_data['dog']['age'] = $dog->age;
                            $request_data['dog']['breed'] = $dog->breed;
                            $request_data['dog']['likes'] = $dog->likes;
                            $request_data['dog']['picture'] = $dog->image_url;
                        }
                        $msg_array['request_data'] = $request_data;

                        $title = "New Request";
                        $message = $msg_array;
                        Log::info('first_walker_id = ' . print_r($first_walker_id, true));
                        Log::info('New request = ' . print_r($message, true));
                        /* don't do json_encode in above line because if */
                        send_notifications($first_walker_id, "walker", $title, $message);
                    }
                    // Send SMS 
                    $settings = Settings::where('key', 'sms_request_created')->first();
                    $pattern = $settings->value;
                    $pattern = str_replace('%user%', $owner_data->first_name . " " . $owner_data->last_name, $pattern);
                    $pattern = str_replace('%id%', $request->id, $pattern);
                    $pattern = str_replace('%user_mobile%', $owner_data->phone, $pattern);
                    sms_notification(1, 'admin', $pattern);

                    // send email
                    /* $settings = Settings::where('key', 'email_new_request')->first();
                      $pattern = $settings->value;
                      $pattern = str_replace('%id%', $request->id, $pattern);
                      $pattern = str_replace('%url%', web_url() . "/admin/request/map/" . $request->id, $pattern);
                      $subject = "New Request Created";
                      email_notification(1, 'admin', $pattern, $subject); */
                    $settings = Settings::where('key', 'admin_email_address')->first();
                    $admin_email = $settings->value;
                    $follow_url = web_url() . "/user/signin";
                    $pattern = array('admin_eamil' => $admin_email, 'trip_id' => $request->id, 'follow_url' => $follow_url);
                    $subject = "Ride Booking Request";
                    email_notification(1, 'admin', $pattern, $subject, 'new_request', null);

                    $response_array = array(
                        'success' => true,
                        'request_id' => $request->id,
                    );
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }

        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function eta() {

        $secret = Input::get('secret');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'secret' => $secret,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'secret' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations

                    $request = Requests::where('security_key', $secret)->first();
                    if ($request) {

                        if ($request->is_started == 0) {
                            $walker = Walker::find($request->confirmed_walker);
                            $distance = 0;
                        } else {
                            $walker = WalkLocation::where('request_id', $request->id)->orderBy('created_at', 'desc')->first();
                            $distance = WalkLocation::where('request_id', $request->id)->max('distance');
                        }

                        $settings = Settings::where('key', 'default_distance_unit')->first();
                        $unit = $settings->value;
                        if ($unit == 0) {
                            $unit_set = 'kms';
                        } elseif ($unit == 1) {
                            $unit_set = 'miles';
                        }
                        $distance = convert($distance, $unit);


                        $response_array = array(
                            'success' => true,
                            'latitude' => $walker->latitude,
                            'longitude' => $walker->longitude,
                            'destination_latitude' => $request->D_latitude,
                            'destination longitude' => $request->D_longitude,
                            'distance' => (string) $distance,
                            'unit' => $unit_set
                        );

                        $response_code = 200;
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    /* $var = Keywords::where('id', 2)->first();
                      $response_array = array('success' => false, 'error' => '' . $var->keyword . ' ID not Found', 'error_code' => 410); */
                    $response_array = array('success' => false, 'error' => '' . Config::get('app.generic_keywords.User') . ' ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function check_promo_code() {
        $promo_code = Input::get('promo_code');
        $token = Input::get('token');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'promo_code' => $promo_code,
                    'token' => $token,
                    'owner_id' => $owner_id,
                        ), array(
                    'promo_code' => 'required',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);

            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    $settings = Settings::where('key', 'promotional_code_activation')->first();
                    $prom_act = $settings->value;
                    if ($prom_act) {
                        // check promo code
                        $check_code = PromoCodes::where('coupon_code', $promo_code)->where('uses', '>', 0)->first();
                        if ($check_code != NULL) {
                            if ($check_code->state == 1 && date('Y-m-d H:i:s', strtotime($check_code->expiry)) > date('Y-m-d H:i:s') && date('Y-m-d H:i:s', strtotime($check_code->start_date)) <= date('Y-m-d H:i:s')) {
                                if ($check_code->type == 1) {
                                    $discount = $check_code->value . " %";
                                } elseif ($check_code->type == 2) {
                                    $discount = "$ " . $check_code->value;
                                }
                                $response_array = array('success' => true, 'discount' => $discount);
                            } else {
                                $response_array = array('success' => false, 'error' => 'Invalid Promo Code', 'error_code' => 418);
                            }
                        } else {
                            $response_array = array('success' => false, 'error' => 'Invalid Promo Code', 'error_code' => 419);
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Promotion feature is not active.', 'error_code' => 419);
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                }
            } else {
                $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
            }
            $response_code = 200;
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function payment_select() {
        /*
         * 0=payment with credit card
         * 1=payment with Cash
         */
        $payment_opt = Input::get('payment_opt');
        $owner_id = Input::get('id');

        $validator = Validator::make(
                        array(
                    'payment_select' => $payment_opt,
                    'owner_id' => $owner_id,
                        ), array(
                    'payment_select' => 'required',
                    'owner_id' => 'required|integer'
                        )
        );
        //echo "test";

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $request = Requests::where('owner_id', '=', $owner_id)->where('status', '=', 0)->orderBy('created_at', 'desc')->first();
            if ($request) {
                if (isset($request->id)) {
                    /* $request = Requests::find($request->id);
                      $request->payment_mode = $payment_opt;
                      $request->save(); */
                    Requests::where('id', $request->id)->update(array('payment_mode' => $payment_opt));

                    /* Owner::where('id', $owner_id)->update(array('payment_select' => $payment_opt)); */
                    $response_array = array('success' => true, 'error' => 'update successfully', 'error_code' => 407);
                    $response_code = 200;
                } else {
                    $response_array = array('success' => false, 'error' => 'Payment mode not updated', 'error_code' => 507);
                    $response_code = 200;
                }
            } else {
                $response_array = array('success' => false, 'error' => 'Payment mode not updated', 'error_code' => 507);
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function get_provider_list() {
        $latitude = Input::get('usr_lat');
        $longitude = Input::get('user_long');


        $validator = Validator::make(
                        array(
                    'usr_lat' => $latitude,
                    'user_long' => $longitude,
                        ), array(
                    'usr_lat' => 'required',
                    'user_long' => 'required',
                        )
        );

        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {

            $settings = Settings::where('key', 'default_search_radius')->first();
            $distance = $settings->value;
            $settings = Settings::where('key', 'default_distance_unit')->first();
            $unit = $settings->value;
            if ($unit == 0) {
                $multiply = 1.609344;
            } elseif ($unit == 1) {
                $multiply = 1;
            }
            $query = "SELECT *, "
                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from walker "
                    . "where is_available = 1 and "
                    . "is_active = 1 and "
                    . "is_approved = 1 and "
                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance "
                    . "LIMIT 5";
            $walkers_list = DB::select(DB::raw($query));

            $walker_data = array();
            if ($walkers_list) {

                foreach ($walkers_list as $walkers) {
                    $walker_list = array();
                    $walker_list['id'] = $walkers->id;
                    $walker_list['first_name'] = $walkers->first_name;
                    $walker_list['last_name'] = $walkers->id;
                    $walker_list['phone'] = $walkers->phone;
                    $walker_list['email'] = $walkers->email;
                    $walker_list['bio'] = $walkers->bio;
                    $walker_list['address'] = $walkers->address;
                    $walker_list['state'] = $walkers->state;
                    $walker_list['country'] = $walkers->country;
                    $walker_list['zipcode'] = $walkers->zipcode;
                    $walker_list['latitude'] = $walkers->latitude;
                    $walker_list['longitude'] = $walkers->longitude;
                    $walker_list['type'] = $walkers->type;
                    $walker_list['car_model'] = $walkers->car_model;
                    $walker_list['car_number'] = $walkers->car_number;
                    $walker_list['bearing'] = $walkers->bearing;
                    array_push($walker_data, $walker_list);
                }

                if (!empty($walker_data)) {
                    $response_array = array(
                        'success' => true,
                        'walker_list' => $walker_data,
                    );
                } else {
                    $response_array = array(
                        'success' => false,
                        'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found around you.',
                        'error_code' => 411,
                        'walker_list' => $walker_data,
                    );
                }
                $response_code = 200;
            } else {
                $response_array = array(
                    'success' => false,
                    'error' => 'No ' . Config::get('app.generic_keywords.Provider') . ' found around you.',
                    'error_code' => 411,
                    'walker_list' => $walker_data,
                );
                $response_code = 201;
            }
        }
        $response = Response::json($response_array, $response_code);
        return $response;
    }

    public function user_set_destination() {
        $request_id = Input::get('request_id');
        $token = Input::get('token');
        $owner_id = Input::get('id');
        $dest_lat = Input::get('dest_lat');
        $dest_long = Input::get('dest_long');

        $validator = Validator::make(
                        array(
                    'request_id' => $request_id,
                    'token' => $token,
                    'owner_id' => $owner_id,
                    'dest_lat' => $dest_lat,
                    'dest_long' => $dest_long,
                        ), array(
                    'request_id' => 'required|integer',
                    'token' => 'required',
                    'owner_id' => 'required|integer',
                    'dest_lat' => 'required',
                    'dest_long' => 'required',
                        )
        );
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages);
            $response_code = 200;
        } else {
            $is_admin = $this->isAdmin($token);
            if ($owner_data = $this->getOwnerData($owner_id, $token, $is_admin)) {
                // check for token validity
                if (is_token_active($owner_data->token_expiry) || $is_admin) {
                    // Do necessary operations
                    if ($request = Requests::find($request_id)) {

                        if ($request->owner_id == $owner_data->id) {
                            Requests::where('id', $request_id)->update(array('D_latitude' => $dest_lat, 'D_longitude' => $dest_long));
                            if ($request->current_walker) {
                                $msg_array = array();
                                $msg_array['request_id'] = $request_id;
                                $msg_array['unique_id'] = 4;

                                $last_destination = Requests::find($request_id);
                                $owner = Owner::find($owner_id);
                                $request_data = array();
                                $request_data['owner'] = array();
                                $request_data['owner']['name'] = $owner->first_name . " " . $owner->last_name;
                                $request_data['owner']['picture'] = $owner->picture;
                                $request_data['owner']['phone'] = $owner->phone;
                                $request_data['owner']['address'] = $owner->address;
                                $request_data['owner']['latitude'] = $owner->latitude;
                                $request_data['owner']['longitude'] = $owner->longitude;
                                $request_data['owner']['dest_latitude'] = $last_destination->D_latitude;
                                $request_data['owner']['dest_longitude'] = $last_destination->D_longitude;
                                $request_data['owner']['rating'] = $owner->rate;
                                $request_data['owner']['num_rating'] = $owner->rate_count;

                                $request_data['dog'] = array();
                                if ($dog = Dog::find($owner->dog_id)) {
                                    $request_data['dog']['name'] = $dog->name;
                                    $request_data['dog']['age'] = $dog->age;
                                    $request_data['dog']['breed'] = $dog->breed;
                                    $request_data['dog']['likes'] = $dog->likes;
                                    $request_data['dog']['picture'] = $dog->image_url;
                                }
                                $msg_array['request_data'] = $request_data;

                                $title = "Set Destination";
                                $message = $msg_array;
                                if ($request->confirmed_walker == $request->current_walker) {
                                    send_notifications($request->confirmed_walker, "walker", $title, $message);
                                }
                            }
                            $response_array = array(
                                'success' => true,
                                'error' => "Destination Set Successfully"
                            );
                            $response_code = 200;
                        } else {
                            $response_array = array('success' => false, 'error' => 'Request ID doesnot matches with Owner ID', 'error_code' => 407);
                            $response_code = 200;
                        }
                    } else {
                        $response_array = array('success' => false, 'error' => 'Request ID Not Found', 'error_code' => 408);
                        $response_code = 200;
                    }
                } else {
                    $response_array = array('success' => false, 'error' => 'Token Expired', 'error_code' => 405);
                    $response_code = 200;
                }
            } else {
                if ($is_admin) {
                    $response_array = array('success' => false, 'error' => 'Owner ID not Found', 'error_code' => 410);
                } else {
                    $response_array = array('success' => false, 'error' => 'Not a valid token', 'error_code' => 406);
                }
                $response_code = 200;
            }
        }


        $response = Response::json($response_array, $response_code);
        return $response;
    }

}
