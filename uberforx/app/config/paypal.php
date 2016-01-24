<?php
return array(
    // set your paypal credential
    'client_id' => 'AQnTJ1tabl4Wzx6J5OJKolyiG3vZU_TqyWfDnSL_lxC4W2TF8-RdL4HnNZROgjw97rP8bYZ3BnzZbF_A',
    'secret' => 'EM31VvRwBroiYFIJPnWs-pmWjTB_Dq4YNAuUn7c2yLVhgoS-baSO2kO-Y6aY-qBeJl80fFD5mLjrhozr',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);