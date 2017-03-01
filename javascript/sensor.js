var SmSc = SmSc || {};

SmSc.Sensor = class Sensor{
    /*
    * Create a sensor instance
    * @param {number} id The id of the sensor, null if not in database
    * @param {string} location The location of the sensor
    * @param {string} type The type of the sensor
    */
    constructor(id, location, type) {
        this.id = id;
        this.location = location;
        this.type = type;
        this.readings = [];
    }

    /*
    * Add the sensor to the database and set the id of the sensor
    * @param {function} callback The callback, will be executed when done
    */
    insert(callback) {
        var sensor = this;
        callback = callback || function(){};
        $.ajax(SmSc.url + "/addSensor.php", {
            method:"GET",
            dataType:"JSON",
            data:SmSc.addCredentials({
                location:sensor.location,
                type:sensor.type
            }),
            success:function(response) {
                var nSensor = response.sensor || {};
                sensor.id = nSensor.id;

                var err = response.err || null;

                if(response.err) callback(null, err);
                else callback(sensor, err);
            },
            error:function() {
                console.log(arguments);
                callback(sensor, "the http request failed");
            }
        });
    }

    get reading() {
        return _.last(_.sortBy(this.readings, "dateTime"));
    }

    set reading(value) {
        if(typeof value == "number") {
            var reading = new SmSc.Reading(this, value, 0);
            reading.insert();
            this.readings.push(reading);
        } else {
            var reading = value;
            reading.insert();
            this.readings.push(reading);
        }
    }
}

SmSc.getSensors = function getSensors(callback) {
    $.ajax(SmSc.url + "/getSensors.php", {
        method:"GET",
        dataType:"JSON",
        data:SmSc.credentials,
        success:function(response) {
            console.log(response);
            var sensors = [];
            for(var x in response.sensors) {
                var object = response.sensors[x].sensor;
                var sensor = new SmSc.Sensor(object.id, object.location, object.type);
                for(var y in response.sensors[x].readings){
                    var object2 = response.sensors[x].readings[y];
                    var reading = new SmSc.Reading(sensor, object2.value, object2.dateTime);
                    sensor.readings.push(reading);
                }
                sensors.push(sensor);
            }
            callback(sensors);
        }
    })
}
