const int count = 8;
int outputs[] = {39, 41, 43, 45, 47, 49, 51, 53};
int inputs[] = {22, 24, 26, 28, 30, 32, 34, 36};
const int debounceTime = 500;


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
}

void handleHit(int x, int y) {
  Serial.println(keymap[x][y]); 
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
