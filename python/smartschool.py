import urllib.request
import json
import sys

def addReading(id, value):
    response = urllib.request.urlopen("http://" + ip + "/addReading.php?id=" + str(id) + "&value=" + str(value)).read();
    return True;

def getReading(id):
    response = urllib.request.urlopen("http://" + ip + "/getReading.php?id=" + str(id)).read();
    data = json.loads(response);
    return data['value'];
