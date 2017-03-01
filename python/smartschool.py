import urllib.request
import json
import sys

ip = "localhost";

def addReading(id, value):
    response = urllib.request.urlopen("http://" + ip + "/smartschool/addReading.php?id=" + str(id) + "&value=" + str(value)).read();
    return true;

def getReading(id):
    response = urllib.request.urlopen("http://" + ip + "/smartschool/getReading.php?id=" + str(id)).read();
    data = json.loads(response);
    return data['value'];
