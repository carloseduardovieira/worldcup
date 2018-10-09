<?php

class Team{
 
    private $conn;
 
    public $id;
    public $name;
    public $image;
    
    public function __construct($db){
        $this->conn = $db;
    }

    public function Create( $oTeam ) {
        if( !$oTeam->name || !$oTeam->image ) die('some value not found');
        $sQuery = "INSERT INTO teams SET name=:name, image=:image";
    
        $stmt = $this->conn->prepare( $sQuery );
        $this->name = htmlspecialchars(strip_tags($oTeam->name));
        $this->image = htmlspecialchars(strip_tags($oTeam->image));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    public function FindTeamMatches( $sName ) {

    }
    
}