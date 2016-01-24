<?php
class TypeTableSeed extends Seeder {

    public function run()
    {
        ProviderType::create(array('id' => 1,'name' => 'Default','is_default' => 1 ));
       
    }

}