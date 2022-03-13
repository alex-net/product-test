<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'sqlite:@app/db.sqlite',
    'on '.\yii\db\Connection::EVENT_AFTER_OPEN=>function($e){
    	Yii::$app->db->createCommand('pragma foreign_keys=on')->execute();
    }
    //'username' => 'root',
    //'password' => '',
    //'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
