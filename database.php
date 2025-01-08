<?php


class Database
{
    private $con;

    function __construct()
    {

        $this->con = $this->connect();
    }

    private function connect()
        {

            $string = "mysql:host=localhost;dbname=gardiendb";
            try{

                $connection = new PDO($string, DBUSER, DBPASS);
                return $connection;

            }catch(PDOException $e)
            {

                echo $e->getMessage();
                die;
            }

            return false;
            

        }
// write to database
        public function write ($query,$data_array = [])
        {

            try
            {
                $con = $this->connect();
                $statement = $con->prepare($query);

                foreach ($data_array as $key => $value){

                    $statement->bindValue(':' .$key, $value);


            }
            

           $check = $statement->execute();
        }catch(PDOException $e)
        {

            echo $e->getMessage();
            
        }
           
           if($check)
           {
            return true;
           }
           return false;
        }

        // read From database
        public function read($query,$data_array = [])
        {

            try
            {
                $con = $this->connect();
                $statement = $con->prepare($query);

                foreach ($data_array as $key => $value){

                    $statement->bindValue(':' .$key, $value);


            }
            

           $check = $statement->execute();
        }catch(PDOException $e)
        {

            echo $e->getMessage();
            
        }
           
           if($check)
           {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result) > 0)
            {
                return $result;
            }
            return false;
           }
           return false;
        }

        public function get_user($userid)
        {

            try
            {
                $con = $this->connect();
                $arr['userid'] = $userid;
                $query = "select * from users where userid = :userid limit 1";
                $statement = $con->prepare($query);

                foreach ($userid as $key => $value){

                    $statement->bindValue(':' .$key, $value);


            }
            

           $check = $statement->execute($arr);
        }catch(PDOException $e)
        {

            echo $e->getMessage();
            
        }
           
           if($check)
           {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result) > 0)
            {
                return $result[0];
            }
            return false;
           }
           return false;
        }


       

       

        //read from database
        
        public function generate_id($max)
        {
            $rand = "";
            $rand_count = rand (4,$max);
            for ($i=0; $i < $rand_count ; $i++) { 

                # code...
                $r = rand(0,9);
                $rand .= $r;
            }

            return $rand;
        }
    
}

