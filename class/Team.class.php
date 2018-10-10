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
    
        $this->name = htmlspecialchars(strip_tags($oTeam->name));
        $this->image = htmlspecialchars(strip_tags($oTeam->image));
        
        $sQuery = "INSERT INTO teams (name, image) VALUES(?,?)";
        $aParams = array($this->name,$this->image);

        $stmt = $this->conn->prepare( $sQuery );
        try { 
            $this->conn->beginTransaction(); 
            $stmt->execute( $aParams );         
            $lastInsert = $this->conn->lastInsertId(); 
            $this->conn->commit(); 
            return $lastInsert;
        } catch(PDOExecption $e) { 
            $this->conn->rollback(); 
            return "Error!: " . $e->getMessage() . "</br>"; 
        }
    }

    public function FindTeamMatches( $iIdTeam ) {
        $sQuery = "
        ";
        $this->id = (int) $iIdTeam;
        $aParams = array($this->id);
        try {                 
            $stmt = $this->conn->prepare( $sQuery );                    
            $stmt->execute($aParams);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOExecption $e) { 
            return $this->conn->errorInfo();
        }  
    }
    
}