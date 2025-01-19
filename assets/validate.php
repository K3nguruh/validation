<?php

/**
 * Klasse Validate
 *
 * Diese Klasse stellt eine Sammlung von Methoden zur Validierung von Eingabedaten bereit. Sie kann verwendet werden, um Werte auf Vorhandensein,
 * Übereinstimmung mit bestimmten Mustern (z. B. reguläre Ausdrücke) sowie auf numerische oder datumsbezogene Anforderungen zu prüfen.
 * Sie eignet sich besonders für den Einsatz in Webanwendungen zur Validierung von Benutzereingaben oder anderen Eingabedaten.
 *
 * Methoden:
 * - validateRequired:  Überprüft, ob ein Wert vorhanden und nicht leer ist.
 * - validateEqual:     Überprüft, ob ein Wert gleich einem anderen Wert ist.
 * - validateMatch:     Überprüft, ob ein Wert dem regulären Ausdruck entspricht.
 * - validateText:      Überprüft, ob ein Wert keine HTML-Tags enthält.
 * - validateHtml:      Überprüft, ob ein Wert nur erlaubte HTML-Tags enthält.
 * - validateEmail:     Überprüft, ob ein Wert eine gültige E-Mail-Adresse ist.
 * - validateUrl:       Überprüft, ob ein Wert eine gültige URL ist.
 * - validateDate:      Überprüft, ob ein Wert ein gültiges Datum im angegebenen Format hat.
 * - validateMin:       Überprüft, ob ein Wert größer oder gleich einem minimalen Wert ist.
 * - validateMax:       Überprüft, ob ein Wert kleiner oder gleich einem maximalen Wert ist.
 * - validateMinMax:    Überprüft, ob ein Wert zwischen einem minimalen und einem maximalen Wert liegt, inklusive Grenzwerte.
 * - validateLess:      Überprüft, ob ein Wert kleiner als ein maximaler Wert ist.
 * - validateGreater:   Überprüft, ob ein Wert größer als ein minimaler Wert ist.
 * - validateBetween:   Überprüft, ob ein Wert zwischen einem minimalen und einem maximalen Wert liegt, exklusive Grenzwerte.
 *
 *
 * Autor:   K3nguruh <https://github.com/K3nguruh>
 * Version: 1.0.0
 * Datum:   2025-01-19 15:06
 * Lizenz:  MIT-Lizenz
 */

class Validate
{
  /**
   * Überprüft, ob ein Wert vorhanden und nicht leer ist.
   *
   * Diese Methode prüft, ob der angegebene Wert nicht leer ist. Ein leerer Wert wird als einer der folgenden angesehen:
   * eine leere Zeichenkette, null, false oder ein leeres Array.
   *
   * Beispiel:
   * validateRequired("Test"); // Gibt true zurück
   * validateRequired(""); // Gibt false zurück
   * validateRequired(null); // Gibt false zurück
   * validateRequired([]); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt true zurück, wenn der Wert nicht leer ist, andernfalls false.
   */
  public function validateRequired($value)
  {
    return !($value === "" || $value === null || $value === false || (is_array($value) && empty($value)));
  }

  /**
   * Überprüft, ob ein Wert gleich einem anderen Wert ist.
   *
   * Diese Methode prüft, ob der angegebene Wert exakt dem Vergleichswert entspricht. Der Vergleich erfolgt mit dem strikten Vergleichsoperator (===).
   *
   * Beispiel:
   * validateEqual(5, 5); // Gibt true zurück
   * validateEqual("Test", "Test"); // Gibt true zurück
   * validateEqual(5, "5"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $compare Der Wert, mit dem verglichen werden soll.
   * @return bool Gibt true zurück, wenn die Werte exakt übereinstimmen, andernfalls false.
   */
  public function validateEqual($value, $compare)
  {
    return $value === $compare;
  }

  /**
   * Überprüft, ob ein Wert dem regulären Ausdruck entspricht.
   *
   * Diese Methode prüft, ob der angegebene Wert dem angegebenen regulären Ausdruck entspricht. Der Wert wird mit dem vollständigen regulären Ausdruck verglichen.
   *
   * Beispiel:
   * validateMatch("abc123", "^[a-z0-9]+$"); // Gibt true zurück
   * validateMatch("abc-123", "^[a-z0-9]+$"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param string $regex Der reguläre Ausdruck, mit dem der Wert übereinstimmen soll.
   * @return bool Gibt true zurück, wenn der Wert dem regulären Ausdruck entspricht, andernfalls false.
   */
  public function validateMatch($value, $regex)
  {
    return preg_match("/{$regex}/", $value) === 1;
  }

  /**
   * Überprüft, ob ein Wert keine HTML-Tags enthält.
   *
   * Diese Methode prüft, ob der angegebene Wert keine HTML-Tags enthält.
   *
   * Beispiel:
   * validateText("Hello World"); // Gibt true zurück
   * validateText("<p>Hello World</p>"); // Gibt false zurück
   *
   * @param string $value Der zu überprüfende Wert.
   * @return bool Gibt true zurück, wenn der Wert keine HTML-Tags enthält, andernfalls false.
   */
  public function validateText($value)
  {
    $cleanValue = strip_tags($value);
    return $cleanValue === $value;
  }

  /**
   * Überprüft, ob ein Wert nur erlaubte HTML-Tags enthält.
   *
   * Diese Methode prüft, ob der angegebene Wert nur HTML-Tags enthält, die in der Liste der erlaubten Tags aufgeführt sind.
   *
   * Beispiel:
   * validateHtml("<p>Hello <b>World</b></p>"); // Gibt true zurück
   * validateHtml("<p>Hello <script>alert('XSS')</script></p>"); // Gibt false zurück
   *
   * @param string $value Der zu überprüfende Wert.
   * @param string $allowedTags (Optional) Eine Zeichenfolge, die die erlaubten HTML-Tags enthält.
   * @return bool Gibt true zurück, wenn der Wert nur erlaubte HTML-Tags enthält, andernfalls false.
   */
  public function validateHtml($value, $allowedTags = null)
  {
    $defaultTags = "<a><b><blockquote><br><code><div><em><h1><h2><h3><h4><h5><h6><hr><i><li><ol><p><s><span><strong><u><ul>";
    $cleanValue = strip_tags($value, $allowedTags ?? $defaultTags);
    return $cleanValue === $value;
  }

  /**
   * Überprüft, ob ein Wert eine gültige E-Mail-Adresse ist.
   *
   * Diese Methode prüft, ob der angegebene Wert eine gültige E-Mail-Adresse ist.
   *
   * Beispiel:
   * validateEmail("test@example.com"); // Gibt true zurück
   * validateEmail("invalid-email"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt true zurück, wenn der Wert eine gültige E-Mail-Adresse ist, andernfalls false.
   */
  public function validateEmail($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  /**
   * Überprüft, ob ein Wert eine gültige URL ist.
   *
   * Diese Methode prüft, ob der angegebene Wert eine gültige URL ist.
   *
   * Beispiel:
   * validateUrl("https://www.example.com"); // Gibt true zurück
   * validateUrl("invalid-url"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt true zurück, wenn der Wert eine gültige URL ist, andernfalls false.
   */
  public function validateUrl($value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) !== false;
  }

  /**
   * Überprüft, ob ein Wert ein gültiges Datum im angegebenen Format hat.
   *
   * Diese Methode prüft, ob der angegebene Wert ein gültiges Datum im angegebenen Format ist.
   *
   * Beispiel:
   * validateDate("2025-01-17", "Y-m-d"); // Gibt true zurück
   * validateDate("17-01-2025", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param string $format Das Datumsformat (Standard: 'Y-m-d').
   * @return bool Gibt true zurück, wenn der Wert ein gültiges Datum ist, andernfalls false.
   */
  public function validateDate($value, $format = "Y-m-d")
  {
    $date = DateTime::createFromFormat($format, $value);
    return $date && $date->format($format) === $value;
  }

  /**
   * Überprüft, ob ein Wert größer oder gleich einem minimalen Wert ist.
   *
   * Diese Methode prüft, ob der angegebene Wert den angegebenen minimalen Wert nicht unterschreitet. Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateMin(10, 5); // Gibt true zurück
   * validateMin(5, 10); // Gibt false zurück
   * validateMin("2025-01-17", "2025-01-01", "Y-m-d"); // Gibt true zurück
   * validateMin("2024-12-31", "2025-01-01", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert größer oder gleich dem minimalen Wert ist, andernfalls false.
   */
  public function validateMin($value, $minValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $minValue = DateTime::createFromFormat($format, $minValue);

      if (!$thisValue || !$minValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue >= $minValue;
  }

  /**
   * Überprüft, ob ein Wert kleiner oder gleich einem maximalen Wert ist.
   *
   * Diese Methode prüft, ob der angegebene Wert den angegebenen maximalen Wert nicht überschreitet. Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateMax(5, 10); // Gibt true zurück
   * validateMax(15, 10); // Gibt false zurück
   * validateMax("2024-12-31", "2025-01-01", "Y-m-d"); // Gibt true zurück
   * validateMax("2025-01-02", "2025-01-01", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $maxValue Der maximale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert kleiner oder gleich dem maximalen Wert ist, andernfalls false.
   */
  public function validateMax($value, $maxValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $maxValue = DateTime::createFromFormat($format, $maxValue);

      if (!$thisValue || !$maxValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue <= $maxValue;
  }

  /**
   * Überprüft, ob ein Wert zwischen einem minimalen und einem maximalen Wert liegt, inklusive Grenzwerte.
   *
   * Diese Methode prüft, ob der angegebene Wert nicht kleiner als der minimale Wert und nicht größer als der maximale Wert ist.
   * Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateMinMax(7, 5, 10); // Gibt true zurück
   * validateMinMax(4, 5, 10); // Gibt false zurück
   * validateMinMax("2025-01-02", "2025-01-01", "2025-01-03", "Y-m-d"); // Gibt true zurück
   * validateMinMax("2024-12-31", "2025-01-01", "2025-01-03", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert.
   * @param mixed $maxValue Der maximale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert zwischen dem minimalen und maximalen Wert liegt, andernfalls false.
   */
  public function validateMinMax($value, $minValue, $maxValue, $format = null)
  {
    return $this->validateMin($value, $minValue, $format) && $this->validateMax($value, $maxValue, $format);
  }

  /**
   * Überprüft, ob ein Wert kleiner als ein maximaler Wert ist.
   *
   * Diese Methode prüft, ob der angegebene Wert den angegebenen maximalen Wert unterschreitet. Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateLess(5, 10); // Gibt true zurück
   * validateLess(15, 10); // Gibt false zurück
   * validateLess("2024-12-31", "2025-01-01", "Y-m-d"); // Gibt true zurück
   * validateLess("2025-01-02", "2025-01-01", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $maxValue Der maximale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert kleiner als der maximale Wert ist, andernfalls false.
   */
  public function validateLess($value, $maxValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $maxValue = DateTime::createFromFormat($format, $maxValue);

      if (!$thisValue || !$maxValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue < $maxValue;
  }

  /**
   * Überprüft, ob ein Wert größer als ein minimaler Wert ist.
   *
   * Diese Methode prüft, ob der angegebene Wert den angegebenen minimalen Wert übersteigt. Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateGreater(10, 5); // Gibt true zurück
   * validateGreater(5, 10); // Gibt false zurück
   * validateGreater("2025-01-02", "2025-01-01", "Y-m-d"); // Gibt true zurück
   * validateGreater("2024-12-31", "2025-01-01", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert größer als der minimale Wert ist, andernfalls false.
   */
  public function validateGreater($value, $minValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $minValue = DateTime::createFromFormat($format, $minValue);

      if (!$thisValue || !$minValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue > $minValue;
  }

  /**
   * Überprüft, ob ein Wert zwischen einem minimalen und einem maximalen Wert liegt, exklusive Grenzwerte.
   *
   * Diese Methode prüft, ob der angegebene Wert größer als der minimale Wert und kleiner als der maximale Wert ist.
   * Optional kann ein Datumsformat angegeben werden.
   * Handelt es sich bei dem Wert um einen String, wird die Länge des Strings verglichen.
   *
   * Beispiel:
   * validateBetween(7, 5, 10); // Gibt true zurück
   * validateBetween(5, 5, 10); // Gibt false zurück
   * validateBetween(10, 5, 10); // Gibt false zurück
   * validateBetween("2025-01-02", "2025-01-01", "2025-01-03", "Y-m-d"); // Gibt true zurück
   * validateBetween("2025-01-01", "2025-01-01", "2025-01-03", "Y-m-d"); // Gibt false zurück
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert.
   * @param mixed $maxValue Der maximale Wert.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt true zurück, wenn der Wert zwischen dem minimalen und maximalen Wert liegt, andernfalls false.
   */
  public function validateBetween($value, $minValue, $maxValue, $format = null)
  {
    return $this->validateGreater($value, $minValue, $format) && $this->validateLess($value, $maxValue, $format);
  }
}
