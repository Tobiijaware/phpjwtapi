<?php 



class Users {
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;

    private $connection;
    private $users_table;
    private $project_table;

    public function __construct($db)
    {
        $this->connection = $db;
        $this->users_table = "users";
        $this->project_table = "projects";
    }

    public function createUser(){
        $user_query = "INSERT INTO ".$this->users_table."
        SET name = ?, email = ?, password = ?";
        $obj = $this->connection->prepare($user_query);

        $obj->bind_param("sss", $this->name, $this->email, $this->password);
        if($obj->execute()){
            return true;
        }
        return false;
    }

    public function checkEmail(){
        $query = "SELECT * from ".$this->users_table." WHERE email = ?";
        $prep = $this->connection->prepare($query);
        $prep->bind_param("s", $this->email);

        if($prep->execute()){
            $data = $prep->get_result();
            return $data->fetch_assoc();
        }
        return [];

    }

    public function checkLogin(){
        $query = "SELECT * from ".$this->users_table." WHERE email = ?";
        $prep = $this->connection->prepare($query);
        $prep->bind_param("s", $this->email);

        if($prep->execute()){
            $data = $prep->get_result();
            return $data->fetch_assoc();
        }
        return [];
    }

    public function createProject(){
        $query = "INSERT into ".$this->project_table." 
        SET user_id = ?, name = ?, description = ?, status = ?";

        $obj = $this->connection->prepare($query);
        $project_name = htmlspecialchars(strip_tags($this->project_name));
        $description = htmlspecialchars(strip_tags($this->description));
        $status = htmlspecialchars(strip_tags($this->status));

        $obj->bind_param("isss", $this->user_id, $project_name, $description, $status);
        if($obj->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function getAllProjects(){
        $query = "SELECT * from ".$this->project_table." ORDER BY id ASC";
        $obj = $this->connection->prepare($query);

        $obj->execute();
        return $obj->get_result();
        
        
    }

    public function getUserProjects(){
        $query = "SELECT * from ".$this->project_table." WHERE user_id = ?";
        $obj = $this->connection->prepare($query);
        $obj->bind_param("i", $this->user_id);

        $obj->execute();
        return $obj->get_result();
    }




}