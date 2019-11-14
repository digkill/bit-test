<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 2:22
 */

namespace App\Controllers;

use App\App;
use App\Services\Controller;
use Exception;


class HomeController extends Controller
{

    public function index()
    {

        if (App::get()->user->isGuest()) {
            App::get()->routes->toUrl('home/login');
        }


        $user = App::get()->user->model;
        $listTransactions = $user->listTransactions();
        $userBalance = $user->getBalance();

        $this->render('index', [
            'user' => $user,
            'listTransactions' => $listTransactions,
            'userBalance' => $userBalance,
        ]);
    }


    public function login()
    {

        if (!App::get()->user->isGuest()) {
            App::get()->routes->toUrl('/');
        }

        $action = isset($_POST['action']);
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $error = '';
        if ($action) {
            App::get()->session->start();
            $auth = App::get()->user->auth($username, $password);
            if ($auth) {

                App::get()->routes->toUrl();
            } else {
                App::get()->session->destroy();
                $error = 'Не правильный логин или пароль';
            }
            App::get()->session->writeClose();
        }

        $this->render('login', [
            'error' => $error,
            'username' => $username,
        ]);
    }


    public function logout()
    {
        App::get()->session->start();
        App::get()->session->destroy();
        App::get()->routes->toUrl('/');
    }


    public function pay()
    {
        if (App::get()->user->isGuest()) {
            App::get()->routes->toUrl('home/login');
        }

        $error = '';
        $pay = false;
        $action = isset($_POST['action']);
        $value = isset($_POST['money']) ? round($_POST['money'], 2) : 0;
        $user = App::get()->user->model;

        if ($action) {
            if ($user->getBalance() >= $value) {
                $pay = $user->createPay($value);
                if (!$pay) {
                    $error = 'Произошла ошибка во время транзакции';
                }
            } else {
                $error = 'Не достаточно средств на балансе';
            }
        } else {
            App::get()->routes->toUrl();
        }

        $this->render('pay', [
            'user' => $user,
            'value' => $value,
            'error' => $error,
            'pay' => $pay,
        ]);
    }


    public function confirm()
    {
        if (App::get()->user->isGuest()) {
            App::get()->routes->toUrl('home/login');
        }

        $error = '';
        $action = isset($_POST['action']);
        $payHash = isset($_POST['pay-hash']) ? $_POST['pay-hash'] : null;
        $user = App::get()->user->model;

        if ($action) {
            $pay = $user->pay($payHash);

            if (!$pay) {
                throw new Exception('Произошла ошибка во время транзакции');
            } else {
                App::get()->routes->toUrl('home/success');
            }
        } else {
            App::get()->routes->toUrl();
        }
    }

    public function success()
    {
        $this->render('success');
    }
}