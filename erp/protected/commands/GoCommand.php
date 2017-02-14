<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GoCommand extends CConsoleCommand
{
    // Define attributes and methods!
    public function run()
    {
        $data = Divisi::model()->findAll();
       foreach($data as $row)
       {
           $model = new Testcronjob;
           $model->data = $row["nama"];
           $model->save();
       }
    }
    
    public function actionIndex() {
        echo 'asas';
    }
}