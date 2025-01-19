# `validation.php` (Erweitert `validate.php`)

Diese Klasse erweitert die Funktionen der Klasse [`Validate`](https://github.com/K3nguruh/validate) und fügt zusätzliche Mechanismen zur Definition und Verwaltung von Validierungsregeln sowie den zugehörigen Fehlermeldungen hinzu.

## Hauptmerkmale:

- Verwaltung von Validierungsregeln mit flexiblen Parametern
- Dynamische Alias-Namen für Werte, um eine bessere Nachvollziehbarkeit der Validierungsfehler zu ermöglichen
- Zentrale Fehlerverwaltung für eine konsistente Fehlerbehandlung
- Unterstützung von Methodenverkettungen (Fluent Interface) für eine benutzerfreundliche Nutzung

## Methoden:

### `setValue($value)`

Setzt den zu validierenden Wert.

### `setArray($array, $name)`

Setzt den Wert aus einem Array basierend auf dem angegebenen Namen.

### `setAlias($name)`

Setzt einen Alias-Namen für den Wert, der für bessere Nachvollziehbarkeit in den Fehlermeldungen verwendet wird.

### `setRule($rule, $message)`

Fügt eine Validierungsregel hinzu.

### `validate()`

Führt die Validierung des gesetzten Wertes durch.

### `getErrors($field)`

Gibt die aufgetretenen Validierungsfehler zurück.

### `resetErrors()`

Setzt die Fehler zurück.

## Systemvoraussetzungen:

- PHP 7.4 oder höher
- Composer (optional)

## Installation:

1. Klone das Repository:
   ```sh
   git clone https://github.com/K3nguruh/validation.git
   ```

## Verwendung:

### Beispiel mit direkten Werten:

```php
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
  ->setValue($post["id"])
  ->setRule("required", "Bitte eine ID eingeben.")
  ->setRule("match||^[1-9]\d{3}$", "Bitte eine gültige ID eingeben.")
  ->validate();

$validation
  ->setValue($post["name"])
  ->setAlias("name-2")
  ->setRule("required", "Bitte einen Namen eingeben.")
  ->validate();

$validation
  ->setValue($post["datum"])
  ->setRule("required", "Bitte ein Datum eingeben.")
  ->setRule("date||Y-m-d", "Bitte ein gültiges Datum eingeben.")
  ->validate();

$validation
  ->setValue($post["alter"])
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
```

### Beispiel mit Array-Werten:

```php
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
```
