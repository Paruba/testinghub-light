var mysql = require('mysql');
class DatabaseTasks{
    
    DatabeseConn(){
        var con = mysql.createConnection({
            host: "localhost",
            user: "root",
            password: "",
            database: "testing_database"
        });
        return con;
      }
      TryConn(){
        var MyCon = this.DatabeseConn();
        MyCon.connect(function(err) {
          if (err) throw err;
          console.log("Connected!");
        });
      }
}
DataOper = new DatabaseTasks();
DataOper.TryConn();
