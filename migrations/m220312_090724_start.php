<?php

use yii\db\Migration;

/**
 * Class m220312_090724_start
 */
class m220312_090724_start extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('cats', [
            'id' => $this->primaryKey(),//->comment('Ключик категории'),
            'name' => $this->string(20)->notNull(),//->comment('Наименованиие категории'),
            'color' => $this->string(6)->notNull()->defaultValue('000000'),//->comment('Цвет категории'),
        ]);
        $this->createIndex('catsnameind', 'cats', ['name'], true);
        $this->createTable('product', [
            'id' => $this->primaryKey(),//->comment('Ключик товара'),
            'name' => $this->string(100)->notNull(),//->comment('Имя товара'),
            'price' => $this->integer()->notNull(),///ценник 
            'status' => $this->boolean()->notNull(),//->defaultValue(false)->commnet('Активность товара'),
            'descr' => $this->text(),//->comment('Описание товара'),
        ]);

        $this->createIndex('prodstatind', 'product', ['status']);

        $this->execute('create table prodcat (pid integer not null, cid integer  not null, foreign key (pid)  references product(id) on delete cascade on update cascade , foreign key (cid) references cats(id) on delete cascade on update cascade )');
        $this->createIndex('prodcatpind', 'prodcat', ['pid']);
        // таблица с картинками ..
        $this->execute('create table imgs (pid integer not null, fn text not null, active int not null default 0, foreign key (pid)  references product(id) on delete cascade on update cascade )');
        foreach(['pid', 'active', 'fn'] as $k) {
            $this->createIndex('prodimgiind' . $k, 'imgs', [$k]);
        }
    }

    public function down()
    {
        foreach(['imgs', 'cats', 'prodcat', 'product'] as $t) {
            $this->dropTable($t);
        }
    }

}
