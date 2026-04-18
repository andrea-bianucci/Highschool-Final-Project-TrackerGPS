#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "TUO_SSID";
const char* password = "TUA_PASSWORD";

SoftwareSerial ss(4, 5); // RX = D2, TX = D1
WiFiClient client;
HTTPClient http;

void setup() {
  Serial.begin(9600);
  ss.begin(9600);
  
  WiFi.begin(ssid, password);
  Serial.print("Connessione WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connesso!");
}

void loop() {
  if (ss.available()) {
    String data = ss.readStringUntil('\n');
    
    if (data.startsWith("$GPGGA")) {
      // Parsing semplice
      String values[10];
      int startIndex = 0;
      for (int i = 0; i < 10; i++) {
        int endIndex = data.indexOf(',', startIndex);
        values[i] = data.substring(startIndex, endIndex);
        startIndex = endIndex + 1;
      }

      String latitude = values[2];  // DDMM.MMMM
      String longitude = values[4]; // DDDMM.MMMM
      
      if (latitude.length() > 0 && WiFi.status() == WL_CONNECTED) {
        String url = "http://www.trackergps.altervista.org/Esp.php?id=2&latitude=" + latitude + "&longitude=" + longitude;
        
        http.begin(client, url);
        int httpCode = http.GET();
        
        if (httpCode > 0) {
          Serial.printf("[HTTP] Codice: %d\n", httpCode);
        } else {
          Serial.printf("[HTTP] Errore: %s\n", http.errorToString(httpCode).c_str());
        }
        http.end();
      }
    }
  }
}
