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

      $data['user'] = $_SESSION['user'];

      $this->view('layouts/html_header');
      $this->view('navbar', $data);
      $this->view('homepage', $data);
      $this->view('footer');
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

      // check if there was an invalid login
      if (!empty($_SESSION['server_error'])) {
         $data['server_error'] = $_SESSION['server_error'];
         unset($_SESSION['server_error']);
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
      if (!$result['status']) {

         //logger
         logger("$username - login inválido", 'error');

         // invalid login
         $_SESSION['server_error'] = 'Login inválido';
         $this->login_frm();
         return;
      }

      // logger
      logger("$username - login com sucesso");

      // load user information to the session
      $results = $model->get_user_data($username);

      // add user to session
      $_SESSION['user'] = $results['data'];

      // update the last login
      $results = $model->set_user_last_login($_SESSION['user']->id);

      // go to main page
      $this->index();
   }

   public function logout()
   {
      //disable direct access to logout
      if (!check_session()) {
         $this->index();
         return;
      }

      //logger
      logger($_SESSION['user']->name . ' - fez logout');

      // clear user from session
      unset($_SESSION['user']);

      // go to index (login form)
      $this->index();
   }

   public function change_password_frm()
   {
      if (!check_session()) {
         $this->index();
         return;
      }

      $data['user'] = $_SESSION['user'];

      // checks for validation errors
      if (!empty($_SESSION['validation_errors'])) {
         $data['validation_errors'] = $_SESSION['validation_errors'];
         unset($_SESSION['validation_errors']);
      }

      // checks for server errors
      if (!empty($_SESSION['server_errors'])) {
         $data['server_errors'] = $_SESSION['server_errors'];
         unset($_SESSION['server_errors']);
      }

      $this->view('layouts/html_header');
      $this->view('navbar', $data);
      $this->view('profile_change_password_frm', $data);
      $this->view('footer');
      $this->view('layouts/html_footer');
   }

   public function change_password_submit()
   {
      if (!check_session()) {
         $this->index();
         return;
      }

      // checks if there was a post request
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
         $this->index();
         return;
      }

      // validation errors
      $validation_errors = [];

      // checks if the input fields are filled
      if (empty($_POST['text_current_password'])) {
         $validation_errors[] = "Password atual é de preenchimento obrigatório.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }


      if (empty($_POST['text_new_password'])) {
         $validation_errors[] = "A nova password é de preenchimento obrigatório.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      if (empty($_POST['text_repeat_new_password'])) {
         $validation_errors[] = "A repetição da nova password é de preenchimento obrigatório.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      // gets the input values
      $current_password     =    $_POST['text_current_password'];
      $new_password         =    $_POST['text_new_password'];
      $repeat_new_password  =    $_POST['text_repeat_new_password'];

      // checks if all passwords have more than 6 and less than 12 characters.
      if (strlen($current_password < 6) || strlen($current_password) > 12) {
         $validation_errors[] = "A password atual deve ter entre 6 e 12 caracteres.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      if (strlen($new_password < 6) || strlen($new_password) > 12) {
         $validation_errors[] = "A nova password deve ter entre 6 e 12 caracteres.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      if (strlen($repeat_new_password < 6) || strlen($repeat_new_password) > 12) {
         $validation_errors[] = "A repetição da nova password deve ter entre 6 e 12 caracteres.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      // checks if all passwords have, at least one upper, one lower and one digit.

      // use positive look ahead
      if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/", $current_password)) {
         $validation_errors[] = "A password atual deve ter, pelo menos, uma maiúscula, uma minúscula e um dígito.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/", $new_password)) {
         $validation_errors[] = "A nova password deve ter, pelo menos, uma maiúscula, uma minúscula e um dígito.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/", $repeat_new_password)) {
         $validation_errors[] = "A repetição da nova password deve ter, pelo menos, uma maiúscula, uma minúscula e um dígito.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      // checks if the new password and repeat new password are equal values
      if ($new_password != $repeat_new_password) {
         $validation_errors[] = "A nova password e a sua repetição não são iguais.";
         $_SESSION['validation_errors'] = $validation_errors;
         $this->change_password_frm();
         return;
      }

      // checks if the current password is equal to the database
      $model = new Agents();
      $results = $model->check_current_password($current_password);

      if (!$results['status']) {

         // current password does not match the one existing in the database
         $server_errors[] = "A password atual não está correta.";
         $_SESSION['server_errors'] = $server_errors;
         $this->change_password_frm();
         return;
      }

      // form data is ok. Updates the password in the database
      $model->update_agent_password($new_password);

      // logger
      $username = $_SESSION['user']->name;
      logger("$username - password alterada com sucesso no perfil do utilizador.");

      // shows view with success information
      $data['user'] = $_SESSION['user'];
      $this->view('layouts/html_header');
      $this->view('navbar', $data);
      $this->view('profile_change_password_success');
      $this->view('footer');
      $this->view('layouts/html_footer');
   }

   public function define_password($purl = '')
   {
      die($purl);
   }
}

/*
  Correção de username !!!
  username         password
admin@bng.com   => Aa123456
agente1@bng.com  => Aa123456
agente2@bng.com  => Aa123456
*/