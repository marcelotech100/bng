<?php

namespace bng\Controllers;

class Main 
{
   public function index()
   {
      echo "Estou dentro do controlador Main - index<br>";
      echo "ok";
      teste();
   }

   public function teste()
   {
      die("Aqui no teste");
   }
}