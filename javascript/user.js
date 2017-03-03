var SmSc = SmSc || {};

SmSc.User = class User {
    constructor(username, role) {
        this.username = username;
        this._role = role;
    }
    
    set role(value) {
        this._role = value;
        
        $.ajax(SmSc.url + "/updateUser.php", {
            method:"GET",
            dataType:"JSON",
            data:SmSc.addCredentials({
                target:this.username,
                role:this.role
            })
        })
    }
    
    get role() {
        return this._role;
    }
}

SmSc.getUsers = function getUsers(callback) {
    callback = callback || function() {};
    $.ajax(SmSc.url + "/getUsers.php", {
        method:"GET",
        dataType:"JSON",
        data:SmSc.credentials,
        success:function(response) {
            var users = [];
            for(var i in response.users) {
                users[i] = new SmSc.User(response.users[i].username, response.users[i].role);
            }
            callback(users, response.err);
        }
    })
}
