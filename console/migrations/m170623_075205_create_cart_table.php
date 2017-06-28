<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170623_075205_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=> $this->integer(11)->comment('商品id'),
            'nm'=>$this->integer(11)->comment('商品数量'),
            'nid'=>$this->integer(11)->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
