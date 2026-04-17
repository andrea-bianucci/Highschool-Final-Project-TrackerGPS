#include <SoftwareSerial.h>
#include <string>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h> 

const char* ssid = "[name of your personal SSID if you want to connect to your hotspot]";
const char* password = "[psw of your SSID]";

const char* ssid2 = "[name of your personal SSID if you want to connect to your Wifi";
const char* password2 = "[psw of your SSID]";

HTTPClient http;  //Declare an object of class HTTPClient
WiFiClient client;
int httpCode;
String payload;
int msg;
 
SoftwareSerial ss(4, 5); // rx, tx

String lat = "";
String lng = "";
int cosa = 0;

void setup() {
  ss.begin(9600);
  Serial.begin(9600);
  Serial.print("Connecting.."); 
  Serial.print("Connesso");
}

void loop() {
  
  if (ss.available()) {
    String data = ss.readStringUntil('\n');
    Serial.print("ok");
    String latitude = "", longitude = "";
    char * datoFinale;
    if (data.startsWith("$GPGGA")) {
      Serial.println("Data: " + data);

      String values[15]; // Array per memorizzare i valori separati
        
        int valueIndex = 0;
        int startIndex = 0;
        int endIndex = data.indexOf(',');
  
        while (endIndex != -1 && valueIndex < 15) {
          values[valueIndex] = data.substring(startIndex, endIndex);
          startIndex = endIndex + 1;
          endIndex = data.indexOf(',', startIndex);
          valueIndex++;
        }
  
        latitude = values[2];
        longitude = values[4];
        
        if (latitude != "" && longitude != ""){

            //if (WiFi.status() != WL_CONNECTED){
                WiFi.begin(ssid, password);
                Serial.print("Connecting.."); 
                //int cont = 0;
                //while (WiFi.status() != WL_CONNECTED && cont<10) {
                while (WiFi.status() != WL_CONNECTED) {
                  delay(1000);
                  Serial.print(".");
                  //cont++;
                }
    
                //cont = 0;
                /*Serial.println("PRIMA FAILED");
                
                WiFi.begin(ssid2, password2);
                Serial.print("Connecting.."); 
                while (WiFi.status() != WL_CONNECTED && cont<10) {
                  delay(1000);
                  Serial.print(".");
                  cont++;
                }

                Serial.println("SECONDA FAILED");*/
            //}
    
            http.begin(client, "http://www.trackergps.altervista.org/Esp.php?id=2&latitude="+latitude+"&longitude="+longitude+"");  //Specify request destination
            msg = http.GET();
            Serial.print(msg);
            WiFi.disconnect();
        }
    }
        /*String values[15]; // Array per memorizzare i valori separati
        
        int valueIndex = 0;
        int startIndex = 0;
        int endIndex = data.indexOf(',');
  
        while (endIndex != -1 && valueIndex < 15) {
          values[valueIndex] = data.substring(startIndex, endIndex);
          startIndex = endIndex + 1;
          endIndex = data.indexOf(',', startIndex);
          valueIndex++;
        }
  
        latitude = values[2];
        longitude = values[4];*/

        
    }
}
