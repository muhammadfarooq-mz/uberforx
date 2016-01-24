<?php
class KeywordsTableSeed extends Seeder {

    public function run()
    {
        Keywords::create(array('id' => 1,'Keyword' => 'Provider','alias' => 'Provider' ));
        Keywords::create(array('id' => 2,'Keyword' => 'User','alias' => 'User' ));
        Keywords::create(array('id' => 3,'Keyword' => 'Taxi','alias' => 'Taxi' ));
        Keywords::create(array('id' => 4,'Keyword' => 'Trip','alias' => 'Trip' ));
        Keywords::create(array('id' => 5,'Keyword' => '$','alias' => 'Currency' ));
        Keywords::create(array('id' => 6,'Keyword' => 'total_trip','alias' => '1' ));
        Keywords::create(array('id' => 7,'Keyword' => 'cancelled_trip','alias' => '2' ));
        Keywords::create(array('id' => 8,'Keyword' => 'total_payment','alias' => '3' ));
        Keywords::create(array('id' => 9,'Keyword' => 'completed_trip','alias' => '4' ));
        Keywords::create(array('id' => 10,'Keyword' => 'card_payment','alias' => '5' ));
        Keywords::create(array('id' => 11,'Keyword' => 'credit_payment','alias' => '6' ));

    }

}