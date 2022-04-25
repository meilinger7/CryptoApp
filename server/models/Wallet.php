<?php

require_once 'DatabaseObject.php';

class Wallet implements DatabaseObject, JsonSerializable {
    public $name;
    public $currency;
    public $id;
    private $amount;
    private $price;
    private $erros = [];

    public function validate()
    {
        return $this->validateCurrency();
    }


     /**
     * Creates a new object in the database
     * @return integer ID of the newly created object (lastInsertId)
     */
    public function create()
    {
        $db = Database::connect();
        $sql = "INSERT INTO wallet (name, currency, amount, price) values(?, ?, ? , ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->name, $this->currency));
        $lastId = $db->lastInsertId();
        Database::disconnect();
        return $lastId;
    }

    /**
     * Saves the object to the database
     */
    public function update()
    {
        $db = Database::connect();
        $sql = "UPDATE wallet set name = ?, currency = ?, amount = ?, price = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->name, $this->currency, $this->amount, $this->price, $this->id));
        Database::disconnect();
    }

    /**
     * Get an object from database
     * @param integer $id
     * @return object single object or null
     */
    public static function get($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM wallet WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $item = $stmt->fetchObject('Wallet');  // ORM
        Database::disconnect();
        return $item !== false ? $item : null;
    }

    /**
     * Get an array of objects from database
     * @return array array of objects or empty array
     */
    public static function getAll()
    {
        $db = Database::connect();
        $sql = 'SELECT * FROM wallet';
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // fetch all datasets (rows), convert to array of Purchase-objects (ORM)
        $items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Wallet');

        Database::disconnect();

        return $items;
    }

    /**
     * Deletes the object from the database
     * @param integer $id
     */
    public static function delete($id)
    {
        $db = Database::connect();
        $sql = "DELETE FROM wallet WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        Database::disconnect();
    }

     /**
     * define attributes which are part of the json output
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            "id" => intval($this->id),
            "name" => $this->name,
            "currency" => $this->currency,
            "amount" => $this->amount,
            "price" => $this->price
        ];
    }

    private function validateCurrency() {
        if (strlen($this->currency) == 0) {
            $this->errors['currency'] = "Waehrung ungueltig";
            return false;
        } else if (strlen($this->currency) > 32) {
            $this->errors['currency'] = "Waehrung zu lang (max. 32 Zeichen)";
            return false;
        } else {
            unset($this->errors['currency']);
            return true;
        }
    }

    

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of currency
     */ 
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @return  self
     */ 
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}