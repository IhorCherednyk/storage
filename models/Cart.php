<?php

/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.05.2016
 * Time: 10:40
 */

namespace app\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord {

    public function addToCart($product, $qty = 1) {

        $cart = \Yii::$app->session->get('_CART_', []);

        if (isset($cart['cart'][$product->id])) {
            $cart['cart'][$product->id]['qty'] += $qty;
        } else {
            $cart['cart'][$product->id] = [
                'qty' => $qty,
                'name' => $product->name,
                'price' => $product->price,
                'img' => $product->img
            ];
        }
        
        $cart = \Yii::$app->session->set('_CART_', $cart);
    }
    
    public function getTotalPrice() {
        $total = 0;
        $data = \Yii::$app->session->get('_CART_', []);
        foreach ($data['cart'] as $id => $item) {
            $total += $item['price'];
        }
        
        return $total;
    }
    public function getClear(){
        \Yii::$app->session->remove('_CART_');
        return true;
    }
    public function getItems(){
        return \Yii::$app->session->get('_CART_', []);
    }
    
    public function getTotalCount() {
        $total = 0;
        $data = \Yii::$app->session->get('_CART_', []);
        foreach ($data['cart'] as $item) {
            $total += $item['qty'];
        }
        
        return $total;
    }
    
    public function recalc($id) {
        $data = \Yii::$app->session->get('_CART_', []);
        
        if (!isset($data['cart'][$id]))
            return false;
        unset($data['cart'][$id]); 
        $data = \Yii::$app->session->set('_CART_', $data);
   
    }

}
