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
      selectData(){
        var DatCon = this.DatabeseConn();
        DatCon.connect(function(err) {
          if (err) throw err;
          DatCon.query("SELECT * FROM table_users", function (err, result, fields) {
            if (err) throw err;
            console.log(result);
          });
        });
      }
      joinData(){
        var DatCon = this.DatabeseConn();
        DatCon.connect(function(err) {
          if (err) throw err;
          DatCon.query("SELECT table_users.username, rented_car.car_name FROM table_users LEFT JOIN rented_car ON table_users.id = rented_car.rented_by", function (err, result, fields) {
            if (err) throw err;
            console.log(result);
          });
        });
      }
}
DataOper = new DatabaseTasks();
DataOper.joinData();
