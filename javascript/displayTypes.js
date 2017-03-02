var SmSc = SmSc || {};

SmSc.displayTypes = SmSc.displayTypes || {};

SmSc.displayTypes.thermometer = function(sensor) {
    return sensor.reading.value / 10 + "&deg;C";
}

SmSc.displayTypes.lock = function(sensor) {
    return sensor.reading.value > 0 ? "	&#128274;" : "	&#128275;";
}
