<?php

namespace App\Models;


use App\App;
use App\Services\Model;
use Exception;

class User extends Model
{
    const SECRET_PHRASE = 'MF$&#*fnygh';

    const STATUS_NEW = 1;
    const STATUS_PROCESS = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_FAILED = 4;


    public static $tableName = 'user';
    public static $transactionTableName = 'transaction';


    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `username` = ?';
        $stmt = App::get()->database->connection->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? static::createModel($result->fetch_array()) : null;
    }

    public static function passwordHash($password)
    {
        return sha1($password . self::SECRET_PHRASE);
    }

    public static function generateSession($sessionHash)
    {
        return sha1(sha1($_SERVER['SERVER_ADDR']) . $sessionHash);
    }

    public function updateSession($hash)
    {
        if (!$this->isLoaded()) {
            return false;
        }

        $sql = 'UPDATE ' . static::$tableName . ' SET `session` = ? WHERE `id` = ?';


        $stmt = App::get()->database->connection->prepare($sql);
        $stmt->bind_param('si', $hash, $this->id);

        return $stmt->execute();
    }


    public function getBalance()
    {
        return $this->balance;
    }


    public function listTransactions()
    {
        $status = self::STATUS_NEW;
        $sql = 'SELECT * FROM ' . static::$transactionTableName . ' WHERE `user_id` = ? AND `status` != ? ORDER BY `id` DESC';

        if (!$sql) {
            return null;
        }


        $stmt = App::get()->database->connection->prepare($sql);
        $stmt->bind_param('ii', $this->id, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function createPay($value)
    {
        $hash = sha1(time() + $this->getBalance());
        $stmt = App::get()->database->connection->prepare('INSERT INTO `' . static::$transactionTableName . '` (`user_id`, `value`, `hash`) VALUES (?, ?, ?)');
        $stmt->bind_param('ids', $this->id, $value, $hash);
        $result = $stmt->execute();

        return $result ? $hash : false;
    }


    public function pay($hash)
    {
        try {
            if (!$this->isLoaded()) {
                throw new Exception('Model not loaded');
            }

            $db = App::get()->database->connection;
            $db->begin_transaction();


            $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `id` = ? FOR UPDATE';
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();


            $status = self::STATUS_NEW;
            $sql = 'SELECT * FROM ' . static::$transactionTableName . ' WHERE `hash` = ? AND `status` = ? FOR UPDATE';
            $stmt = App::get()->database->connection->prepare($sql);
            $stmt->bind_param('si', $hash, $status);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result->num_rows) {
                $db->rollback();
                return false;
            }

            $pay = $result->fetch_assoc();

            $this->setPayStatus($pay['id'], self::STATUS_PROCESS);

            $value = (float)$pay['value'];
            $balance = (float)$user['balance'];

            if ($balance + $value < 0) {
                $db->rollback();
                $this->setPayStatus($pay['id'], self::STATUS_FAILED);
                return false;
            }

            $result = $this->setPayStatus($pay['id'], self::STATUS_SUCCESS);

            $balance -= $value;

            $sql = 'UPDATE ' . static::$tableName . ' SET `balance` = ? WHERE `id` = ?';
            $stmt = App::get()->database->connection->prepare($sql);
            $stmt->bind_param('di', $balance, $this->id);
            $result = $stmt->execute() && $result;

            if (!$result) {
                $db->rollback();

                $this->setPayStatus($pay['id'], self::STATUS_FAILED);

                return false;
            }

            $db->commit();

            return true;

        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function setPayStatus($id, $status)
    {

        $sql = 'UPDATE ' . static::$transactionTableName . ' SET `status` = ? WHERE `id` = ?';
        $stmt = App::get()->database->connection->prepare($sql);
        $stmt->bind_param('ii', $status, $id);
        return $stmt->execute();
    }
}