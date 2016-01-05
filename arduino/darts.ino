#include <UIPEthernet.h>


const int count = 8;
int outputs[] = {23, 25, 27, 29, 31, 33, 35, 37};
int inputs[] = {34, 36, 38, 40, 42, 44, 46, 48};
const int debounceTime = 500;

EthernetClient client;

char keymap[count][count][4] = {
  {"13s", "1s", "18s", "4s", "10s", "15s", "6s", "2s"},
  {"13t", "1t", "18t", "4t", "10t", "15t", "6t", "2t"},
  {"16t", "14t", "11t", "8t", "19t", "3t", "7t", "17t"},
  {"16d", "14d", "11d", "8d", "19d", "3d", "7d", "17d"},
  {"16s", "14s", "11s", "8s", "19s", "3s", "7s", "17s"},
  {"13d", "1d", "18d", "4d", "10d", "15d", "6d", "2d"},
  {"20t", "9s", "25s", "9t", "20d", "9d", "20s", ""},
  {"5t", "12s", "25d", "12t", "5d", "12d", "5s", ""}
};

void setup() {
  for (int i = 0; i < count; ++i) {
    pinMode(outputs[i], OUTPUT);
  }
  Serial.begin(9600);


  uint8_t mac[6] = {0x00,0x01,0x02,0x03,0x04,0x05};
  Ethernet.begin(mac);

  Serial.print("localIP: ");
  Serial.println(Ethernet.localIP());
  Serial.print("subnetMask: ");
  Serial.println(Ethernet.subnetMask());
  Serial.print("gatewayIP: ");
  Serial.println(Ethernet.gatewayIP());
  Serial.print("dnsServerIP: ");
  Serial.println(Ethernet.dnsServerIP());
}

void handleHit(int x, int y) {
  Serial.println(x);
  Serial.println(y);
  Serial.println(keymap[x][y]);

  EthernetClient client;
  Serial.println("Client connect");

  if (client.connect(IPAddress(10,170,10,194),80))
  {
    Serial.println("Client connected");

    client.println("POST /dartboard/hit HTTP/1.1");
    client.println("Host: 10.170.10.194");
    client.println("User-Agent: Mozilla/4.0");
    client.println("Content-Length: 10");
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.println();
    client.print("field=");
    client.println(keymap[x][y]);
    client.println();
    client.flush();

    delay(50);

    client.stop();
    Serial.println("Client disconnect");

  }
  else {
    Serial.println("Client connect failed");
  }
}


void loop() {
  for (int in = 0; in < count; ++in) {
    digitalWrite(outputs[in], HIGH);
    for (int i = 0; i < count; ++i) {
      int sensorValue = digitalRead(inputs[i]);

      if (sensorValue == HIGH) {
        handleHit(in, i);
        delay(debounceTime);
        while (digitalRead(inputs[i]) == HIGH);
      }
    }

    digitalWrite(outputs[in], LOW);
  }
}