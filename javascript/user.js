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
