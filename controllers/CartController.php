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
//        \Yii::$app->session->remove('_CART_');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $product = Product::findOne($id);
        if (empty($product))
            return false;
        
        $cart = new Cart();
        $cart->addToCart($product);
        $this->layout = false;

        return $this->render('cart-modal', [
                    'data' => $cart->getItems(),
                    'totalSum' => $cart->getTotalPrice(),
                    'totalCount' => $cart->getTotalCount()
        ]);
    }

    public function actionClear() {
        $cart = new Cart();
        $cart->getClear();
        $this->layout = false;
        return $this->render('cart-modal', ['data' => $cart->getItems()]);
    }

    public function actionDelItem() {
        $id = Yii::$app->request->post('id');
        
        $cart = new Cart();
        $cart->recalc($id);
        $this->layout = false;
        return $this->render('cart-modal', [
                    'data' => $cart->getItems(),
                    'totalSum' => $cart->getTotalPrice(),
                    'totalCount' => $cart->getTotalCount()
        ]);
    }

    public function actionShow() {
        $cart = new Cart();
        $data = \Yii::$app->session->get('_CART_', []);
        if(!empty($data['cart'])){
            $total = $cart->calcSum($data['cart']);
            $this->layout = false;
            return $this->render('cart-modal', [
                        'data' => $cart->getItems(),
                        'totalSum' => $cart->getTotalPrice(),
                        'totalCount' => $cart->getTotalCount()
            ]);
        }else{
            $this->layout = false;
            return $this->render('cart-modal', ['data' => $cart->getItems()]);
        }
        
    }

//    public function actionCart() {
//         
//        $data = \Yii::$app->session->get('_CART_', []);
//        return $this->render('cart-modal', compact('data'));
//    }
}
