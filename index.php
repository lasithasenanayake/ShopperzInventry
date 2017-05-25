<?php
    require_once ("mysqlconnector.php");
    require_once ("carbite.php");
    define("SERVER", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "sossgrid");
    define("DBNAME", "shopperz");

    function insetToMySql($req,$res){
        
        $connector = new MySqlConnector(SERVER, USERNAME,PASSWORD, DBNAME);
        $status = $connector->Connect();

        if ($status === TRUE){
            $connector->Query("CREATE TABLE IF NOT EXISTS TEST (ID INT PRIMARY KEY , NAME TEXT)");
            $connector->Query("INSERT INTO TEST VALUES(1, 'test')");

            $connector->Disconnect();
            $res->Set("Operation Successful");
        } else {
            $res->Set("Error occured while connecting : $status");
        }
    }

    function GetProducts($req,$res){
        
        $connector = new MySqlConnector(SERVER, USERNAME,PASSWORD, DBNAME);
        $status = $connector->Connect();
        $myArray = array();
        if ($status === TRUE){
           // $connector->Query("CREATE TABLE IF NOT EXISTS TEST (ID INT PRIMARY KEY , NAME TEXT)");
            $result=$connector->Query("select a.product_id,a.model,a.quantity as stockqty,x.quantity as optqty,x.option_value_id,x.option_id,m.name as optionname 
            from oc_product a, oc_product_to_store b, oc_product_option_value x,oc_option_value_description m 
            where a.product_id=b.product_id and a.product_id=x.product_id and x.option_value_id=m.option_value_id and store_id=8");
            while ($obj = $result->fetch_object()) {
                array_push($myArray, $obj);
            }

            $connector->Disconnect();
            $res->Set($myArray);
        } else {
            $res->Set("Error occured while connecting : $status");
        }
    }


    Carbite::GET("/test", "insetToMySql");
    Carbite::GET("/products", "GetProducts");
    Carbite::Start();
?>
