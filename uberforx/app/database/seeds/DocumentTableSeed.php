<?php
class DocumentTableSeed extends Seeder {

    public function run()
    {
        Document::create(array('id' => 1,'name' => 'Default'));
       
    }

}