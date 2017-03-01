<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.05.2016
 * Time: 10:40
 */

namespace app\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    public function addToCart($product, $qty = 1){
        
        $cart = \Yii::$app->session->get('_CART_', []);
        
        if(isset($cart['cart'][$product->id])){
            $cart['cart'][$product->id]['qty'] += $qty;
        }else{
            $cart['cart'][$product->id] = [
                'qty' => $qty,
                'name' => $product->name,
                'price' => $product->price,
                'img' => $product->img
            ];
        }
        $cart['cart.qty'] = isset($cart['cart.qty']) ? $cart['cart.qty'] + $qty : $qty;
        $cart['cart.sum'] = isset($cart['cart.sum']) ? $cart['cart.sum'] + $qty * $product->price : $qty * $product->price;
        
                $cart = \Yii::$app->session->set('_CART_', $cart);

    }

} 