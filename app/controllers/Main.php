<?php

namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Main extends BaseController
{
   public function index()
   {
      // check if there is no active user in session
      if (!check_session()) {
         $this->login_frm();
         return;
      }

      $this->view('layouts/html_header');
      $this->view('<h3 class="text-white text-center">Olá Mundo!</h3>');
      $this->view('layouts/html_footer');
   }

   // ====================================
   // LOGIN
   // ====================================
   public function login_frm()
   {
      // check if there is already an user in the session
      if (check_session()) {
         $this->index();
         return;
      }

      // check if there are errors (after login_submit)
      $data = [];
      if (!empty($_SESSION['validation_errors'])) {
         $data['validation_errors'] = $_SESSION['validation_errors'];
         unset($_SESSION['validation_errors']);
      }

      // display login form
      $this->view('layouts/html_header');
      $this->view('login_frm', $data);
      $this->view('layouts/html_footer');
   }

   public function login_submit()
   {
      // check if there is already an active session
      if (check_session()) {
         $this->index();
         return;
      }

      // check if there was a post request
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
         $this->index();
         return;
      }

      // get form data
      $username = $_POST['text_username'];
      $password = $_POST['text_password'];

      // form validation
      $validation_errors = [];
      if (empty($username) || empty($password)) {
         $validation_errors[] = "Username e password são obrigatórios.";
      }

      // check if username is valid email 
      if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
         $validation_errors[] = "O username tem que ser um email válido.";
      }

      // check if username is between 5 and 50 chars
      if (strlen($username) < 5 || strlen($username) > 50) {
         $validation_errors[] = "O username deve ter entre 5 e 50 caracteres.";
      }

      // check if password is valid
      if (strlen($password) < 6 || strlen($password) > 12) {
         $validation_errors[] = "A password deve ter entre 6 e 12 caracteres.";
      }

      // check if there are validation errors
      if (!empty($validation_errors)) {
         $_SESSION['validation_errors'] = $validation_errors;
         $this->login_frm();
         return;
      }

      $model = new Agents();
      $result = $model->check_login($username, $password);
      if ($result['status']) {
         echo 'OK!';
      } else {
         echo 'NOK!';
      }
   }
}

/*
  username         password
admin@bng.com   => Aa123456
agent1@bng.com  => Aa123456
agent2@bng.com  => Aa123456
*/