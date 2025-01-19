<?php

/**
 * Klasse Validation
 *
 * Diese Klasse erweitert die Funktionen der Klasse Validate um zusätzliche Mechanismen zur
 * Definition und Verwaltung von Validierungsregeln und den zugehörigen Fehlermeldungen.
 *
 * Hauptmerkmale:
 * - Verwaltung von Validierungsregeln mit flexiblen Parametern
 * - Dynamische Alias-Namen für Werte, um eine bessere Nachvollziehbarkeit von Validierungsfehlern zu ermöglichen
 * - Integration einer zentralen Fehlerverwaltung
 * - Unterstützung von Methodenverkettungen (Fluent Interface) für eine benutzerfreundliche Nutzung
 *
 * Methoden:
 * - setValue:    Setzt den Wert, der validiert werden soll.
 * - setArray:    Setzt den Wert aus einem Array basierend auf dem angegebenen Namen.
 * - setAlias:    Setzt einen Alias für den aktuellen Wert.
 * - setRule:     Fügt eine Validierungsregel hinzu.
 * - validate:    Validiert den festgelegten Wert anhand der definierten Regeln.
 * - getErrors:   Gibt alle Fehlermeldungen zurück, die während der Validierung aufgetreten sind.
 * - resetErrors: Setzt die Fehler zurück.
 *
 *
 * Autor:   K3nguruh <https://github.com/K3nguruh>
 * Version: 1.0.0
 * Datum:   2025-01-19 17:32
 * Lizenz:  MIT-Lizenz
 */

class Validation extends Validate
{
  private const DEFAULT_SEPARATOR = "||"; // Trennzeichen für mehrere Argumente in Validierungsregeln

  private $value; // Der zu validierende Wert
  private $alias; // Alias für den Wert, z. B. der Name des Feldes
  private $rules; // Liste der Validierungsregeln
  private $errors = []; // Fehlermeldungen während der Validierung
  private $count = 0; // Zähler für die Generierung von Alias-Namen

  /**
   * Setzt den Wert, der validiert werden soll.
   *
   * Diese Methode akzeptiert einen Wert und entfernt führende sowie abschließende Leerzeichen.
   * Der Wert wird für die Validierung festgelegt, und ein Alias wird automatisch generiert,
   * um eine eindeutige Identifikation während des Validierungsprozesses zu gewährleisten.
   *
   * @param mixed $value Der Wert, der validiert werden soll.
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setValue($value)
  {
    $this->value = trim($value);
    $this->alias = $this->count++;
    $this->rules = [];

    return $this;
  }

  /**
   * Setzt den Wert aus einem Array basierend auf dem angegebenen Namen.
   *
   * Diese Methode extrahiert den Wert aus dem übergebenen Array und verwendet den angegebenen Namen
   * als Alias für den Wert. Der Wert wird dann für die Validierung festgelegt.
   *
   * @param array $array Das Array, aus dem der Wert entnommen wird.
   * @param string $name Der Name des Werts im Array.
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setArray($array, $name)
  {
    $this->value = trim($array[$name]);
    $this->alias = $name;
    $this->rules = [];

    return $this;
  }

  /**
   * Setzt einen Alias für den aktuellen Wert.
   *
   * Diese Methode ermöglicht es, einen benutzerdefinierten Alias für den aktuellen Wert festzulegen,
   * um eine klarere Identifikation während des Validierungsprozesses zu ermöglichen.
   *
   * @param string $name Der Alias des aktuellen Werts.
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setAlias($name)
  {
    $this->alias = $name;

    return $this;
  }

  /**
   * Fügt eine Validierungsregel hinzu.
   *
   * Diese Methode fügt eine Validierungsregel hinzu, die angibt, wie der Wert validiert werden soll,
   * sowie die entsprechende Fehlermeldung im Falle eines Fehlers.
   *
   * @param string $rule Die Validierungsregel.
   * @param string $message Die Fehlermeldung im Falle eines Fehlers.
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setRule($rule, $message)
  {
    $this->rules[] = ["rule" => $rule, "message" => $message];

    return $this;
  }

  /**
   * Validiert den festgelegten Wert anhand der definierten Regeln.
   *
   * Diese Methode prüft den Wert anhand aller festgelegten Regeln. Im Falle eines Fehlers wird die entsprechende
   * Fehlermeldung gespeichert und die Validierung wird abgebrochen.
   *
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   * @throws InvalidArgumentException Wenn eine ungültige Validierungsregel gefunden wird.
   */
  public function validate()
  {
    foreach ($this->rules as $rule) {
      $args = explode(self::DEFAULT_SEPARATOR, $rule["rule"]);
      $methodName = "validate" . ucfirst(array_shift($args));

      if (!method_exists($this, $methodName)) {
        throw new InvalidArgumentException("Ungültige Validierungsregel: {$methodName}");
      }

      if (!$this->$methodName($this->value, ...$args)) {
        $this->errors[$this->alias] = $rule["message"];
        break;
      }
    }

    return $this;
  }

  /**
   * Gibt alle Fehlermeldungen zurück, die während der Validierung aufgetreten sind.
   *
   * Diese Methode gibt die Fehlermeldungen zurück, die bei der Validierung aufgetreten sind.
   * Optional kann ein spezifisches Feld angegeben werden, um nur die Fehler für dieses Feld zu erhalten.
   *
   * @param string|null $field Der Name des Feldes, für das die Fehler zurückgegeben werden sollen.
   * @return array Die Fehlermeldungen.
   */
  public function getErrors($field = null)
  {
    if ($field !== null) {
      return $this->errors[$field] ?? [];
    }

    return $this->errors;
  }

  /**
   * Setzt die Fehler zurück.
   *
   * Diese Methode leert das Fehler-Array, sodass nachfolgende Validierungen ohne vorherige Fehler ausgeführt werden können.
   *
   * @return $this Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function resetErrors()
  {
    $this->errors = [];
    return $this;
  }
}
