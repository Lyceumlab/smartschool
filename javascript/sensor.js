var SmSc = SmSc || {};

SmSc.Reading = class Reading {
    constructor(sensor, value, dateTime) {
        this.sensor = sensor;
        this.value = value || 0;
        this.dateTime = dateTime || new Date().getTime();
    }
    
    insert(callback) {
        var reading = this;
        
        callback = callback || function(){};
        $.ajax(SmSc.url + "/addReading.php", {
            method:"GET",
            dataType:"JSON",
            data:{
                id:this.sensor.id,
                value:this.value
            },
            success:function(response) {
                var nReading = response.reading;
                reading.dateTime = nReading ? nReading.datetime : reading.dateTime;
                
                var err = response.err || null;
                
                callback(reading, err);
            },
            error:function() {
                console.log(arguments);
                callback(reading, "the http request failed");
            }
        })
    }
}
