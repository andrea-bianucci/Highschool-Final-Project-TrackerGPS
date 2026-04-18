#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "TUO_SSID";
const char* password = "TUA_PASSWORD";

SoftwareSerial ss(4, 5); // RX=D2, TX=D1
WiFiClient client;
HTTPClient http;

unsigned long lastTime = 0;
unsigned long timerDelay = 10000; // Invia i dati ogni 10 secondi

void setup() {
  Serial.begin(9600);
  ss.begin(9600);
  
  WiFi.mode(WIFI_STA); // Imposta come client
  WiFi.begin(ssid, password);
  
  Serial.print("Connessione in corso...");
  // Nota: non mettiamo un loop infinito qui se vuoi che il codice parta subito, 
  // ma è meglio aspettare il primo aggancio.
}

void loop() {
  // 1. Leggi SEMPRE il GPS per svuotare il buffer seriale
  if (ss.available()) {
    String data = ss.readStringUntil('\n');
    
    if (data.startsWith("$GPGGA")) {
      // Parsing veloce della stringa
      int comma2 = data.indexOf(',', 7);
      int comma3 = data.indexOf(',', comma2 + 1);
      int comma4 = data.indexOf(',', comma3 + 1);
      int comma5 = data.indexOf(',', comma4 + 1);

      String latitude = data.substring(comma2 + 1, comma3);
      String longitude = data.substring(comma4 + 1, comma5);

      // 2. Invia i dati solo se il timer è scaduto E il WiFi è connesso
      if ((millis() - lastTime) > timerDelay) {
        if (WiFi.status() == WL_CONNECTED && latitude.length() > 5) {
          
          String url = "http://www.trackergps.altervista.org/Esp.php?id=2&latitude=" + latitude + "&longitude=" + longitude;
          
          http.begin(client, url);
          int httpCode = http.GET(); // Effettua la chiamata
          
          if (httpCode > 0) {
            Serial.println("Dati inviati! Risposta: " + String(httpCode));
          } else {
            Serial.println("Errore HTTP: " + http.errorToString(httpCode));
          }
          http.end();
          
          lastTime = millis(); // Reset del timer
        } else if (WiFi.status() != WL_CONNECTED) {
          Serial.println("WiFi disconnesso, riconnessione automatica...");
          // Non serve fare nulla, l'ESP8266 gestisce la riconnessione da solo se WiFi.begin è stato chiamato
        }
      }
    }
  }
}
