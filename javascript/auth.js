var SmSc = SmSc || {};

Object.defineProperty(SmSc, "credentials", {
    get:function() {
        return SmSc._credentials || new SmSc.Credentials("", "");
    },
    set:function(credentials) {
        SmSc._credentials = credentials || SmSc.credentials;
    }
})

SmSc.addCredentials = function(object) {
    object.username = SmSc.credentials.username;
    object.password = SmSc.credentials.password;
    
    return object;
}

SmSc.Credentials = class Credentials {
    constructor(username, password) {
        this.username = username;
        this.password = password;
    }
    
    verify(callback) {
        callback = callback || function(){};
        
        $.ajax(SmSc.url + "/login.php", {
            method:"GET",
            dataType:"JSON",
            data:{
                username:this.username,
                password:this.password
            },
            success:function(response) {
                var valid = response.valid || false;
                var err = response.err || null;
                callback(valid, err);
            },
            error:function() {
                console.log(arguments);
                callback(false, "the http request failed");
            }
        })
    }
}
