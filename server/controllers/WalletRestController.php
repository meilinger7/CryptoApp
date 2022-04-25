<?php

require_once('RESTController.php');
require_once('models/Wallet.php');

class WalletRestController extends RESTController
{
    public function handleRequest()
    {
        switch ($this->method) {
            case 'GET':
                $this->handleGETRequest();
                break;
            case 'POST':
                $this->handlePOSTRequest();
                break;
            case 'PUT':
                $this->handlePUTRequest();
                break;
            case 'DELETE':
                $this->handleDELETERequest();
                break;
            default:
                $this->response('Method Not Allowed', 405);
                break;
        }
    }

    private function handleGETRequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {
            $model = Wallet::get($this->args[0]); 
            $this->response($model);
        } else if ($this->verb == null && $this->args[1] == "measurement") {
            $model = Purchase::getAll($this->args[0]);             // all messwerte from Station 
            $this->response($model);
        } else if ($this->verb == null && empty($this->args)) {
            $model = Wallet::getAll();      
            $this->response($model);
        }
         else {
            $this->response("Bad request", 400);
        }
    }

    /**
     * create purchase: POST api.php?r=wallet
     */
    private function handlePOSTRequest()
    {
        $model = new Purchase();
        $model->setDate($this->getDataOrNull('date'));
        $model->setAmount($this->getDataOrNull('amount'));
        $model->setPrice($this->getDataOrNull('price'));
        $model->setCurrency($this->getDataOrNull('currency'));

        if ($model->save()) {
            $this->response("OK", 201);
        } else {
            $this->response($model->getErrors(), 400);
        }
    }

    /**
     * update purchase: PUT api.php?r=wallet/25 -> args[0] = 25
     */
    private function handlePUTRequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {
            $model = Purchase::get($this->args[0]);
            if ($model == null) {
                $this->response("Not found", 404);
            } else {
                $model->setDate($this->getDataOrNull('date'));
                $model->setCurrency($this->getDataOrNull('currency'));
                $model->setAmount($this->getDataOrNull('amount'));
                $model->setPrice($this->getDataOrNull('price'));


                if ($model->save()) {
                    $this->response("OK");
                } else {
                    $this->response($model->getErrors(), 400);
                }
            }

        } else {
            $this->response("Not Found", 404);
        }
    }

    /**
     * delete purchase: DELETE api.php?r=wallet/25 -> args[0] = 25
     */
    private function handleDELETERequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {
            Purchase::delete($this->args[0]);
            $this->response("OK", 200);
        } else {
            $this->response("Not Found", 404);
        }
    }

}
