<?php

namespace App\Services;

use App\App;
use App\Models\User as UserModel;

class User
{

    protected $isGuest = true;

    public $model;


    public function isGuest()
    {
        return $this->isGuest;
    }

    public function start()
    {

        $session = App::get()->session->get('user', null);

        if (!$session || empty($session['id'])) {
            return;
        }


        $user = UserModel::findById($session['id']);
        if (!$user) {
            return;
        }

        if (UserModel::generateSession($session['session']) !== $user->session) {
            return;
        }

        $this->isGuest = false;
        $this->model = $user;
    }

    public function auth($username, $password)
    {
        $user = UserModel::findByUsername($username);
        if (!$user) {
            return false;
        }

        if (UserModel::passwordHash($password) !== $user->password) {
            return false;
        }

        $session = sha1(time() . $user->id);

        $user->updateSession(UserModel::generateSession($session));



        App::get()->session->set('user', [
            'id' => $user->id,
            'session' => $session,
        ]);

        return true;
    }
}