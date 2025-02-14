# `validation.php` (Erweitert `validate.php`)

Diese Klasse erweitert die Funktionen der Klasse [`Validate`](https://github.com/K3nguruh/validate) und fügt zusätzliche Mechanismen zur Definition und Verwaltung von Validierungsregeln sowie den zugehörigen Fehlermeldungen hinzu.

## Hauptmerkmale:

- Verwaltung von Validierungsregeln mit flexiblen Parametern
- Dynamische Alias-Namen für Werte, um eine bessere Nachvollziehbarkeit der Validierungsfehler zu ermöglichen
- Zentrale Fehlerverwaltung für eine konsistente Fehlerbehandlung
- Unterstützung von Methodenverkettungen (Fluent Interface) für eine benutzerfreundliche Nutzung

## Systemvoraussetzungen:

- PHP 8.0 oder höher

## Methoden:

| Methode (public)           | Beschreibung                          | Parameter (Typ)                       | Standard |
| -------------------------- | ------------------------------------- | ------------------------------------- | -------- |
| `setValue($value)`         | Setzt den zu validierenden Wert.      | `$value (mixed)`                      | `null`   |
| `setArray($array, $name)`  | Setzt den Wert aus einem Array.       | `$array (array)`, `$name (string)`    | `null`   |
| `setAlias($name)`          | Setzt einen Alias-Namen für den Wert. | `$name (string)`                      | `null`   |
| `setRule($rule, $message)` | Fügt eine Validierungsregel hinzu.    | `$rule (string)`, `$message (string)` | `null`   |
| `validate($output)`        | Führt die Validierung durch.          | `$output (bool)`                      | `false`  |
| `getErrors($control)`      | Gibt die aufgetretenen Fehler zurück. | `$control (string\|null)`             | `null`   |
| `resetErrors()`            | Setzt die Fehler zurück.              | Keine                                 | --       |

## Installation:

Klone das Repository:

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

#### v1.0.1 und höher:

```php
<?php
require_once "assets/validate.php";
require_once "assets/validation.php";

$validation = new Validation();

$post = [
  "id" => "100",
];

$validation
  ->setValue($post["id"])
  ->setRule("required", "Bitte eine ID eingeben.")
  ->setRule("match||^[1-9]\d{3}$", "Bitte eine gültige ID eingeben.")
  ->validate(true);

// direkte Json Ausgabe - Keine weitere Code-Ausführung
// {"status":"error","control":"id","message":"Bitte eine gültige ID eingeben."}
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

#### v1.0.1 und höher:

```php
<?php
require_once "assets/validate.php";
require_once "assets/validation.php";

$validation = new Validation();

$post = [
  "id" => "100",
];

$validation
  ->setArray($post, "id")
  ->setRule("required", "Bitte eine ID eingeben.")
  ->setRule("match||^[1-9]\d{3}$", "Bitte eine gültige ID eingeben.")
  ->validate(true);

// direkte Json Ausgabe - Keine weitere Code-Ausführung
// {"status":"error","control":"id","message":"Bitte eine gültige ID eingeben."}
```

## Zusätzliche Informationen

- **Lizenz**: MIT
- **Issues/Bugs**: [GitHub Issues](https://github.com/K3nguruh/validation/issues)

## Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert. Siehe [LICENSE](LICENSE) für Details.
