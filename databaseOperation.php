<?php
class ParubaDatabaseOperation
{
    function getNumberOfIDs($pTableName,$pIDName){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $sql = "SELECT MAX(" . $pIDName . ") FROM ".$pTableName;
        $result = $conn->query($sql);
        $MaxId = $result->fetch_assoc();
        //print_r($MaxId);
        $MaxIdElement = $MaxId['MAX(' . $pIDName. ')'];
        $conn->close();
        return $MaxIdElement;
    }
    function selectItems($pTable,array $ValueName, $Ordered = false, $OrderedBy = NULL){
        $ParubaData = new ParubaDatabaseOperation;
        // Create connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $conn->set_charset('UTF8');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $ValuesNameString = $ParubaData->doStringArray($ValueName);
        if ($Ordered){
            $sql = "SELECT " . $ValuesNameString . " FROM " . $pTable . ' order by ' . $OrderedBy . ' DESC';
            $result = $conn->query($sql);
        } else {
            $sql = "SELECT " . $ValuesNameString . " FROM " . $pTable;
            $result = $conn->query($sql);
        }
        
        //$row = $result->fetch_assoc();

        $record = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                array_push($record,$row);
            }
        }

        $conn->close();
        return $record;
    }
    function doStringArray(array $ValueName){
        $ValuesNameString = '';
        for ($i=0; $i < count($ValueName) ; $i++) { 
            if($i == 0){
                $ValuesNameString = $ValueName[$i];
            } else {
                $ValuesNameString = $ValuesNameString . ',' . $ValueName[$i];
            }
        }
        return $ValuesNameString;
    }
    function selectDataWithCondition($pTable,array $ValueName,array $ConditionValues){
        // Create connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $conn->set_charset('UTF8');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conditionString = '';
        
        if (count($ValueName) == count($ConditionValues)) {
            for ($i=0; $i < count($ValueName); $i++) { 
                if($i == 0) {
                    $conditionString = $ValueName[$i] . '=' . "'". $ConditionValues[$i]. "'";
                } else {
                    $conditionString = $conditionString . " AND " . $ValueName[$i] . '=' . "'" . $ConditionValues[$i]. "'";
                }
            }
            $sql = "SELECT * FROM " . $pTable . " WHERE " . $conditionString;
            echo $sql;
            $result = $conn->query($sql);
            $record = array();
            $conn->close();
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    array_push($record,$row);
                }
            }
            
            return $record;
        }
    }
    function InsertToDB($pTable,array $pAdditionalCollName, array $pAdditionalValues,$InsertRecord = true, $TestInsert=false){
        // Create connection
        $ParubaData = new ParubaDatabaseOperation;
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $conn->set_charset('UTF8');
        $CountedAddColl = count($pAdditionalCollName);
        $CountedAddValues = count($pAdditionalValues);
        if ($CountedAddColl == $CountedAddValues){
            $AdditionalCollNameString = '';
            $AdditionalValuesString = '';
            for ($i=0; $i < $CountedAddValues; $i++) { 
                if($i == 0){
                    $AdditionalCollNameString = $pAdditionalCollName[$i];
                    $AdditionalValuesString = "'" . $pAdditionalValues[$i] . "'";
                } else {
                    $AdditionalCollNameString = $AdditionalCollNameString . ' , ' . $pAdditionalCollName[$i];
                    $AdditionalValuesString = $AdditionalValuesString . ' , ' ."'" .$pAdditionalValues[$i] ."'";
                }
            }
                $sql = "INSERT INTO " . $pTable . " ( " . $AdditionalCollNameString . " ) VALUES ( ". $AdditionalValuesString .  " )";
                if ($InsertRecord) {
                    $conn->query($sql);
                } else {
                    if ($TestInsert){
                        $conn->query($sql);
                        echo $sql;
                    } else {
                        echo $sql;
                    }
                }
                
        } else {
            echo "Arrays havent same sizes";
        }
        $conn->close();
    }
    function checkID($pTable, array $pIdName, array $pIdValue){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $ConditionString = '';
        if (count($pIdName) == count($pIdValue)){
            for ($i=0; $i < count($pIdName); $i++) { 
                if($i == 0){
                    $ConditionString = $pIdName[$i] . '=' . $pIdValue[$i];
                } else {
                    $ConditionString = $ConditionString . ' AND ' . $pIdName[$i] . '=' . $pIdValue[$i];
                }
            }
            
        }
        $sql = "SELECT * FROM " . $pTable . " WHERE " . $ConditionString ;
        $result = $conn->query($sql);
        $arrRess = $result->fetch_assoc();
        $conn->close();
        return $arrRess;
    }
    function UpdateRecord($pTable,array $pColName,array $pValues,array $pIdName,array $pIdValue,$InsertRecord = true,$TestInsert = false){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $conn->set_charset('UTF8');
        if(count($pColName) == count($pValues)){
            $StringToSql = '';
            for ($i=0; $i < count($pColName) ; $i++) { 
                if ($i == 0){
                    $StringToSql = $pColName[$i] . '=' . "'" . $pValues[$i] . "'";
                } else {
                    $StringToSql = $StringToSql . ', ' . $pColName[$i] . '=' . "'" . $pValues[$i] . "'";
                }
            }
            if (count($pIdName) == count($pIdValue)){
                $StringCondition = '';
                for ($j=0; $j < count($pIdName); $j++) { 
                    if ($j == 0){
                        $StringCondition = $pIdName[$j] . "='" . $pIdValue[$j] . "'";
                    } else {
                        $StringCondition = $StringCondition . " AND " . $pIdName[$j] . "='" . $pIdValue[$j] . "'";
                    }
                }
                $sql = "UPDATE " . $pTable . " SET " . $StringToSql . " WHERE " . $StringCondition;
                if ($InsertRecord) {
                    $conn->query($sql);
                } else {
                    if ($TestInsert){
                        $conn->query($sql);
                        echo $sql;
                    } else {
                        echo $sql;
                    }
                }
            }
            
        }
        $conn->close();
    }
    function removeRow($pTable,array $pKeysName, array $pKeysValue){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing_database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $ConditionString = '';
        if (count($pKeysName) == count($pKeysValue)){
            for ($i=0; $i < count($pKeysName) ; $i++) { 
                if($i == 0){
                    $ConditionString = $pKeysName[$i] . '=' . $pKeysValue[$i];
                } else {
                    $ConditionString = $ConditionString . ' AND ' . $pKeysName[$i] . '=' . $pKeysValue[$i];
                }
            }
        }
        $sql = "DELETE FROM " . $pTable . " WHERE " . $ConditionString;
        $conn->query($sql);
        $conn->close();
    }
}
$Data = new ParubaDatabaseOperation;
//function InsertToDB($pTable,array $pAdditionalCollName, array $pAdditionalValues,$InsertRecord = true, $TestInsert=false){
//$Data->InsertToDB('rented_car',['car_name','rented_by','price'],['Mercedes','2','8000']);
//$Data->InsertToDB('rented_car',['car_name','rented_by','price'],['FIAT','1','200']);
//$Data->InsertToDB('rented_car',['car_name','rented_by','price'],['KIA','3','400']);
//$Data->InsertToDB('rented_car',['car_name','rented_by','price'],['BMW','2','7000']);
?>