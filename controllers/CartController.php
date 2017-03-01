<?php

/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.05.2016
 * Time: 10:37
 */

namespace app\controllers;

use app\models\Product;
use app\models\Cart;
use Yii;

/* Array
  (
  [1] => Array
  (
  [qty] => QTY
  [name] => NAME
  [price] => PRICE
  [img] => IMG
  )
  [10] => Array
  (
  [qty] => QTY
  [name] => NAME
  [price] => PRICE
  [img] => IMG
  )
  )
  [qty] => QTY,
  [sum] => SUM
  ); */

class CartController extends AppController {

    public function actionAdd() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $product = Product::findOne($id);
        if (empty($product))
            return false;

//        \Yii::$app->session->remove('_CART_');
        $cart = new Cart();
        $cart->addToCart($product);

//        $data = \Yii::$app->session->get('_CART_', []);
        
        return true;
        
//        $this->layout = false;
//        return $this->render('cart-modal', compact('data'));
    }

    public function actionCart() {
         
        $data = \Yii::$app->session->get('_CART_', []);
        return $this->render('cart-modal', compact('data'));
    }

}
