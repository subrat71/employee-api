<?php
header('Access-Control-Allow-Origin: *'); 
require_once("Rest.inc.php");
require_once("DB_Connect.php");

class API extends REST{
    public $data = "";
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "employee_db";
    private $db = NULL;
    //Constructer initialize
    public function __construct(){
        parent::__construct();
        $this->dbConnect();
    }


    private function dbConnect(){
        $this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
        if($this->db)
            mysqli_select_db($this->db, self::DB) or die('ERRROR:'.mysqli_error());
    }
    private function closeConnection() {
        if($this->db)
            mysqli_close($this->db);
    }
    private function executeGenericDQLQuery($query){
        try{
            if(!$this->db) $this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
            mysqli_set_charset($this->db, 'utf8');

            $result = mysqli_query($this->db,$query);
            $rows = array();
            if(mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)){
                    array_push($rows,$row);
                }
                return $rows;
            }  else return array();
        } catch(Exception $e){
            $response = array();
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            $this->response($this->json($response), 200);
        }
    }
                
    private function executeGenericDMLQuery($query){
        try{
            $result = mysqli_query($this->db,$query);
            if(mysqli_errno($this->db) != 0){
                throw new Exception("Error   :".mysqli_errno($this->db)."   :  ".mysqli_error($this->db));
            }else{
                return $result;
            }
        }
        catch(Exception $e){
            $response = array();
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            //echo json_encode($response);
            $this->response($this->json($response), 200);
        }
    }
    private function executeGenericInsertQuery($query){
        try{
            $result = mysqli_query($this->db,$query);
            if(mysqli_errno($this->db) != 0){
                throw new Exception("Error   :".mysqli_errno($this->db)."   :  ".mysqli_error($this->db));
            }
            return mysqli_insert_id($this->db);
        }
        catch(Exception $e){
            $response = array();
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            //echo json_encode($response);
            $this->response($this->json($response), 200);
        }
    }
    private function clearArray($arr){
        unset($arr);
        $arr = array();
        return $arr;
    }
    public function processApi(){        
        $func='';
        if(isset($_REQUEST['service']))
            $func = strtolower(trim(str_replace("/", "", $_REQUEST['service'])));
        else if(isset($_REQUEST['reqmethod']))
            $func = strtolower(trim(str_replace("/", "", $_REQUEST['reqmethod'])));
            
        if($func){  
            //if(function_exists($func))
            if (method_exists($this, $func)) {
                $this->$func();
            } else{
                $this->log('invalid service:'.$func, true, 'log_invalid.txt');
                $this->response('invalid service', 406);
            }
        }
    }
    //Employee login
    public function addEmployee() {
        echo "working";
        if($this->get_request_method() != "POST") {
            $this->response('!POST',406);
        }
        
        // $employee = json_decode($this->_request['data']);
        
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        echo $email .' ** '. $fullname .' ** '. $password .' ** '. $address;
        /*try{
            $sql = "INSERT INTO ".TABLE_PREFIX."mask_data (mask_id,mask_name,productid, side, mask_json_data,bounds_json_data,custom_size_data, mask_width,mask_height,mask_price,scale_ratio,is_cropMark,is_safeZone,cropValue,safeValue,scaleRatio_unit,cust_min_height,cust_min_width,cust_max_height,cust_max_width,cust_bound_price) VALUES".substr($values,1);
            $status = $this->executeGenericDMLQuery($sql);
             if($status) {
                $printtypeStatus = $this->setPrintareaType();
                $msg= array("printtypeStatus" => $printtypeStatus);
                $msg= array("status" => "success");
             } else {
                 $msg=array("status"=>"failed");
             }
             $this->response($this->json($employee), 200);
        }catch(Exception $e){
            $result = array('Caught exception:'=>$e->getMessage());
            $this->response($this->json($result), 200);
        }*/
    }

    //Get data
    public function getEmployee() {
        echo ">>>>>>>>>>>>>>";
    }
}
$api = new API;
$api->processApi();
?>