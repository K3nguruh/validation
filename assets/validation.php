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
 * Methoden (public):
 * - setValue:    Setzt den Wert, der validiert werden soll.
 * - setArray:    Setzt den Wert aus einem Array basierend auf dem angegebenen Namen.
 * - setAlias:    Setzt einen Alias für den aktuellen Wert.
 * - setRule:     Fügt eine Validierungsregel hinzu.
 * - validate:    Validiert den festgelegten Wert anhand der definierten Regeln.
 * - getErrors:   Gibt alle Fehlermeldungen zurück, die während der Validierung aufgetreten sind.
 * - resetErrors: Setzt die Fehler zurück.
 *
 * Autor:   K3nguruh <https://github.com/K3nguruh>
 * Version: 1.0.1
 * Datum:   2025-02-14
 * Lizenz:  MIT-Lizenz
 */

class Validation extends Validate
{
  private const DEFAULT_SEPARATOR = "||"; // Trennzeichen für mehrere Argumente in Validierungsregeln

  private $value; // Der zu validierende Wert
  private $alias; // Alias für den Wert, z. B. der Name des Feldes
  private $rules = []; // Liste der Validierungsregeln
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
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setValue($value): self
  {
    $this->value = trim((string) $value);
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
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setArray(array $array, string $name): self
  {
    $this->value = trim((string) $array[$name]);
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
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setAlias(string $name): self
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
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function setRule(string $rule, string $message): self
  {
    $this->rules[] = ["rule" => $rule, "message" => $message];

    return $this;
  }

  /**
   * Validiert den festgelegten Wert anhand der definierten Regeln.
   *
   * Diese Methode prüft den Wert anhand aller festgelegten Regeln. Im Falle eines Fehlers wird die entsprechende
   * Fehlermeldung gespeichert und die Validierung wird abgebrochen. Optional kann die Fehlermeldung als JSON
   * ausgegeben werden.
   *
   * @param bool $output Falls true, wird die Fehlermeldung als JSON ausgegeben und das Skript beendet.
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   * @throws InvalidArgumentException Wenn eine ungültige Validierungsregel gefunden wird.
   */
  public function validate(bool $output = false): self
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

    if ($output && $this->errors) {
      $this->showJsonError();
    }

    return $this;
  }

  /**
   * Gibt alle Fehlermeldungen zurück, die während der Validierung aufgetreten sind.
   *
   * Diese Methode gibt die Fehlermeldungen zurück, die bei der Validierung aufgetreten sind.
   * Optional kann ein spezifisches Feld angegeben werden, um nur die Fehler für dieses Feld zu erhalten.
   *
   * @param string|null $control Der Name des Feldes, für das die Fehler zurückgegeben werden sollen.
   * @return array Die Fehlermeldungen.
   */
  public function getErrors(?string $control = null): array
  {
    if ($control !== null) {
      return $this->errors[$control] ?? [];
    }

    return $this->errors;
  }

  /**
   * Setzt die Fehler zurück.
   *
   * Diese Methode leert das Fehler-Array, sodass nachfolgende Validierungen ohne vorherige Fehler ausgeführt werden können.
   *
   * @return self Das Validation-Objekt für eine einfache Methodenverkettung.
   */
  public function resetErrors(): self
  {
    $this->errors = [];
    return $this;
  }

  /**
   * Gibt die Fehlermeldung als JSON aus.
   *
   * Diese Methode gibt eine JSON-Antwort mit dem Status "error", dem betroffenen Feld (control)
   * und der zugehörigen Fehlermeldung (message) aus. Anschließend wird die Ausführung des Skripts beendet.
   *
   * @return void
   */
  private function showJsonError(): void
  {
    $control = key($this->errors);
    $message = $this->errors[$control];

    header("Content-Type: application/json");
    exit(json_encode(["status" => "error", "control" => $control, "message" => $message]));
  }
}
