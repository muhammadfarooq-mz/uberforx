<?php
/*user registration*/
// define('DATE_FORMATE','Asia/Kolkata');
define('JSON_ROOT_OBJECT' , 'batting');
define('STATUS' , 'status');
define('MESSAGE' , 'message');
!defined("CATEGORY_PATH") ? define("CATEGORY_PATH", "upload/images/"):'';
define('PATH' , 'http://letsbett.com/ws/');
define('SUCCESS' , 'success');
define('FAIL' , 'fail');
define('WAITING' , 'waiting');
define('LIVE_MATCH' , 'live_match');
define('UPCOMMING_MATCH' , 'upcomming_match');
define('USER_PRO','user_profile');
define('TOP_TEN','top_ten_scorrer');

/*table names*/
define('USER_TABLE','user');
    /*USER TABLE FIELD NAMES*/
define('USER_BAL','current_balence');
define('USER_EMAIL','email');
define('IMG','image');
define('LOS','lost');
define('USER_NAM','name');
define('US_ID','user_id');
define('GCM_TOKEN','gcm_token');

define('WON','win');
define('CHIPS','chips');
define('FIRST','1000');
define('SECOND','2500');
define('THIRD','4500');
define('FOUR','7000');
define('FIVE','15000');


define('QUESTION_TABLE','que_catagory');
    /*original field names*/
define('CATAGORY_WEIGHT','catagory_weight');
define('FLAG_GEN_TEM','flg_gen_tem');
define('QUE_CATAGORY_DESCRYPTION','que_catagory_descryption');
define('QUE_CATAGORY_ID','que_catagory_id');
define('QUE_BLOCK_TIME','block_time');
define('QUE_CATAGORY_TYPE','que_catagory_type');

define('USER_BATTING_TABLE','user_bat_data');
    /*original field names*/
define('BATTING_ID_HIS','batting_id');
define('USR_ID_HIS','user_id');
define('MATCH_ID_HIS','match_id');
define('TEAM_ID_HIS','team_id');
define('TEAM_NAME_HIS','team_name');
define('CATAGORY_ID_HIS','catagory_id');
define('USER_ANS_HIS','user_answer');
define('USER_BAT_AMT_HIS','user_bat_amount');
define('MATCH_TYP_HIS','match_type');
define('WIN_LOSS_STAT_HIS','win_los_stat');
define('WIN_LOSS_AMOUNT_HIS','win_los_amount');
define('MATCH_DATE_HIS','match_date');
define('USER_TRUE_ANS_HIS','true_answer');
define('USER_DISP_ANS_HIS','user_disp_ans');
define('QUE_TEAM_NAM_HIS','que_team_nam');
/*login register*/
define("START_BALENCE", 3000);
define("FILE_NAME", "img_name");
define("FILE_DATA", "img_data");
define('USER_ID','user_id');
define('NAME','name');
define('EMAIL','email');
define('GCM_ID','gcm_id');
define('IMAGE','image');
define('NOIMAGE','noimage.jpg');
define('USER_BALANCE','current_balence');
define('WIN','win');
define('LOST','lost');
define('VERSION','version');
define('VER','1.6');

/*for new changes for ammount grater than carore*/
define('CAROR',10000000);
define('CR',"Cr ");
define('DECIMALS',2);
define('CR_BALANCE','balance');
/*for new changes for ammount grater than carore END*/
/*my new Consts*/
define('USER_NAME_MOBILE','name');
define('EMAIL_SENDER','email_sender');
define('EMAIL_RECEIVER','email_receiver');
define('ID_SENDER','id_sender');
define('ID_RECEIVER','id_receiver');
define('SHARE_AMT','share_amount');
define('USER_LIST','user_list');


/*send question list array*/
define('GENERAL','genaral_questions');
define('TEAM1','team1_questions');
define('TEAM2','team2_questions');

    /*mobile developer field names*/
define('QUESTION_WEIGHT','question_weight');
define('QUESTION','question');
define('QUESTION_ID','question_id');
define('QUESTION_CATAGORY','question_catagory');
define('QUESTION_BLOCK_TIME','block_time');
    /*user batting data getting from device*/
define('MTCH_ID_MOB','match_id');
define('TEM_ID_MOB','team_id');
define('TEAM_NM_MOB','team_name');
define('USER_ANS_MOB','user_answer');
define('USER_BAT_AMT_MOB','user_bat_amount');
define('MTCH_TYP_MOB','match_type');
define('WIN_LOS_AMT_MOB','win_los_amount');
define('WIN_LOS_STAT_MOB','win_los_stat');
define('USER_HISTORY','user_history');
define('TEAM_NAMES_FOR_HISTORY','history_team_name');
define('MATCH_DATE_FOR_HISTORY','match_date');
define('TEAM_WISE_DATA_HISTORY','history_team_question_data');


/*YAHOO*/
define('BASEURL','http://query.yahooapis.com/v1/public/yql');
define('BASE_QUERY_END','&format=json&diagnostics=true&env=store%3A%2F%2F0TxIGQMQbObzvU4Apia0V0&callback=');
define('OPTIONS','options');
define('START_RANGE','range_start');
define('END_RANGE','range_end');
define('DEVIDER_RANGE','range_devider');
define('ODDS','odds');
define('EVENS','evens');
define('MATCH','match');
define('TEST','test');
define('ODI','odi');
define('T20','t20');
define('TRUE_ANSWER_MOB','true_answer');
define('QUE_TEAM_NAME_MOB','que_team_name');
define('WEEKLY_BALANCE_IF_ZERO',100);
define('GET_MORE_CHIPS',100);
define('CRON_USER_UP_BAL_COUNT',"balance_up_of_users_no");
define('MOST_ANSWERED',"most_answered");
define('MINES_ONE',"N/A");

define('IS_RAV_MOB','is_rav_mob');
define('TRU','true');
define('FLS','false');

define('TEAM','team');

define('CHECK_USER_LEADER','leader');
define('IS_LEADER','true');
define('IS_NOT_LEADER','false');

define('T20_20','#wt20 #OneBigOver #cricket'); /*for twitter*/
define('OD50_50','#wt20 #OneBigOver #cricket'); /*for twitter*/
/*TOSTS*/
define('QUE','question'); /*not used now previously it was used in GCM message*/
define('WIN1','You won '); /* compare1.php in GCM message if user win a bet*/
define('LOSS1','You loss '); /* compare1.php in GCM message if user loss a bet*/
define('AMOUNT',' coins in');  /* compare1.php in GCM message*/ /* LIKE You Won 500 coins in LetsBett*/

define('USER_UPDATE_BALANCE','User balance is updated');  /*cronjob2.php it will used if we give coins by cronjob while user's coins are 0 but that cronjob is cancled and we gives coin by add and purchase*/
define('DB_ERROR','Database Connection Error'); /*if database will not connect properly or database not found then this message will be displayed*/
define('DATE_SEND_SUCCESS','Data Sent Successfully'); /*display_history.php while user history will be send at that time this message will pass*/
define('USER_PRO_SEND','User Profile successfully send'); /*get_user_profile.php while user profile successfully send at that time this message will pass*/
define('USER_PRO_NOT_SEND','User Profile not sent please try again');  /*get_user_profile.php while user profile not successfully send at that time this message will pass*/
define('QUESTION_TIME_UP','Sorry Your time is up for betting on this question'); /*insert_user_batting.php while question will be block at that time this message will displayed*/
define('NOT_SUPPORTED_TEST','Sorry The Game is not supported for test match'); /*insert_user_batting.php & on_question_select.php it's impossible but even though if test match's id will be pass from mobile at that time tis failure message will be displayed*/
define('BET_MORE_THEN_CURRENT','Bet Amount is more than current balance'); /*insert_user_batting.php while user bet amount will be higher then user's current balance*/
define('YOUR_BET_UPDATE','Your bet is updated'); /*insert_user_batting.php while user bet will updated*/
define('YOUR_BET_CONFIRM','Your bet is confirmed'); /*insert_user_batting.php while user bet at first time*/
define('BET_NOT_UPDATE_TRY_AGAIN','Sorry Your bet is not updated please try again'); /*insert_user_batting.php while user bet will not updated because of some serverside problem*/
define('BET_NOT_CONFIRM_TRY_AGAIN','Sorry Your bet is not confirmed please try again'); /*insert_user_batting.php while user bet at first time and not confirm because of some serverside problem*/
define('LEADERBORD_SEND','Top Scorer list sent successfully'); /*leader_board.php while leaderboard will successgully send to mobile this message will passed*/
define('LEADERBORD_NOT_SEND','Sorry Data not Send'); /*leader_board.php while any how leaderboard will not successgully send to mobile this message will passed*/
define('INVALID_EMAIL','Invalid Email address'); /*mor_chips.php/purchase_chips.php/user_register.php while user email will pass blank or not available same like database have at that time this message will passed*/
define('YOUR_BALANCE_UPDATED','Your balance is updated'); /*mor_chips.php/purchase_chips.php while user will updated successfully this message will passed*/
define('YOU_HAVE_SUFFICIENT_BALANCE','You have sufficient balance'); /*mor_chips.php while user will successfully seen an add and user have more than 0 balance this message will passed*/
define('OPTION_SENT_SUCCESS','Options sent successfully'); /*on_question_select.php while all options will be successfully send to mobile for particular question this message will passed*/
define('QUESTIONS_SUCCESS_SEND','Question list successfully send'); /*question_list.php while all the questions will be sent to mobile this message will be passed*/
define('UPGRADE_VERSION','Upgrade Version'); /*user_register.php while user will open app and the version is older at that time this message will be passed*/
define('USER_SUCCESS_LOGIN','User Successfully Logged in'); /*user_register.php while user successfully logged in app at that time this message will passed*/
define('USER_SUCCESS_REGISTER','User Successfully Registered'); /*user_register.php while user successfully rigistered in app at that time this message will passed*/

define('SHARE_AMOUNT_HIGHER','Share amount is higher than your balance'); /*user_register.php while user successfully rigistered in app at that time this message will passed*/
define('SHARES',' shared '); /*GCEM MESSAGE WHILE USER SHARES AMOUNT*/
define('COINS_TO_YOU',' coins with you '); /*GCEM MESSAGE WHILE USER SHARES AMOUNT*/
define("NO_USER_FOUND","No user found having name like "); /*message when user search result is empty in find_user.php*/
define("USERS_SEND","User list successfully sent"); /*when user list successfully send to device in find_user.php*/
define("SHARE_TO_YOURSELF","Sorry you cannot share coins to yourself"); /*when user share coins to himself*/
define("DECIMAL_COIN_SHARE","Sorry you cannot share coins with decimal value"); /*when user share coins with decimal point*/
?>