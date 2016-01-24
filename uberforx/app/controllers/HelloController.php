<?php

class HelloController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     *
     * @return Response
     */
    public function index() {
        echo distanceGeoPoints(22, 50.0000001, 22, 50.000001);
    }

    public function test() {
        test_ios_noti(1, "walker", "my title", "my_message");
    }

}
