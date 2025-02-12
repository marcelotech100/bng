<?php

namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Main extends BaseController
{
   public function index()
   {

      $model = new Agents();
      $results = $model->get_results();
      printData($results);

      $data['nome'] = 'João';
      $data['apelido'] = 'Ribeiro';

      $this->view('layouts/html_header');
      $this->view('home', $data);
      $this->view('layouts/html_footer');
   }
}
