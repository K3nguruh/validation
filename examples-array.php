<?php
require_once "assets/validate.php";
require_once "assets/validation.php";

$validation = new Validation();

$post = [
  "id" => "100",
  "name" => "",
  "datum" => "1980-06-15 00:00",
  "alter" => "15",
];

$validation
  ->setArray($post, "id")
  ->setRule("required", "Bitte eine ID eingeben.")
  ->setRule("match||^[1-9]\d{3}$", "Bitte eine gültige ID eingeben.")
  ->validate();

$validation
  ->setArray($post, "name")
  ->setAlias("name-2")
  ->setRule("required", "Bitte einen Namen eingeben.")
  ->validate();

$validation
  ->setArray($post, "datum")
  ->setRule("required", "Bitte ein Datum eingeben.")
  ->setRule("date||Y-m-d", "Bitte ein gültiges Datum eingeben.")
  ->validate();

$validation
  ->setArray($post, "alter")
  ->setRule("required", "Bitte ein Alter eingeben.")
  ->setRule("min||16", "Du musst 16 Jahre oder älter sein.")
  ->validate();

$errors = $validation->getErrors();

if ($errors) {
  echo "<pre>";
  print_r($errors);
  echo "</pre>";
} else {
  echo "Alles in Ordnung.";
}
