<?php

namespace App\Controllers;

use Database\PDO\Connection;

class WithdrawalsController {

    private $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance()->get_database_instance();
    }
    /**
     * Muestra una lista de este recurso
     */
    public function index() 
    {
       $stmt = $this->connection->prepare("SELECT * FROM withdrawals");
       $stmt->execute();

       $results = $stmt->fetchAll();

       foreach($results as $result)
        echo "gastaste {$result["amount"]} $ USD  en {$result["description"]} \n ";

    }

    /**
     * Muestra un formulario para crear un nuevo recurso
     */
    public function create() {}

    /**
     * Guarda un nuevo recurso en la base de datos
     */
    public function store($data) {
        

        //usamo prepare para preparar la consulta y asi evitamos SQL injection
        $stmt = $this->connection->prepare("INSERT INTO withdrawals (payment_method, type, date, amount, description) VALUES (:payment_method, :type, :date, :amount, :description)");

        $stmt->bindValue(":payment_method", $data["payment_method"]);
        $stmt->bindValue(":type",$data["type"]);
        $stmt->bindValue(":date",$data["date"]);
        $stmt->bindValue(":amount",$data["amount"]);
        $stmt->bindValue(":description",$data["description"]);

        //cuando unsamos bindparam el valor de la variable puede ser modificado despues
        //como en este caso, bindvalue no permite camibios
        // $data['description']="compre comidad para la kiara";

        //execute recibe un array de cada uno de los placeholder puestos en values en pdo
        $stmt->execute();
    }

    /**
     * Muestra un único recurso especificado
     */
    public function show($id) {

        $stmt = $this->connection->prepare("SELECT * FROM withdrawals WHERE id =:id");
        $stmt->execute([
            ":id" => $id
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        //echo "El registro N° {$result["id"]} dice que gastaste {$result["amount"]} USD en {$result["description"]} \n";

        var_dump($id);

    }

    /**
     * Muestra el formulario para editar un recurso
     */
    public function edit() {}

    /**
     * Actualiza un recurso específico en la base de datos
     */
    public function update($id, $data) {

        $this->connection->beginTransaction();

        $stmt = $this->connection->prepare("UPDATE withdrawals SET 

            payment_method => :payment_method
            type => :type
            date => :date
            amount => :amount
            description => :description
        
        WHERE id =:id");

        $stmt->execute([
            ":id" => $id,
            ":payment_method" => $data["payment_method"],
            ":type" => $data["type"],
            ":date" => $data["date"],
            ":amount" => $data["amount"],
            ":description" => $data["description"]
            
        ]);

        $sure = readline("Estas seguro de editar el registro asociado al ID N° {$id} ??? -> ");
        $sure = strtolower($sure);

        if ($sure == "si") {
           $this->connection->commit();
           echo "has editado el registro con el id {$id}";
       }else {
           $this->connection->rollBack();
           echo "No se ha editado ningun registro";
       }


    }

    /**
     * Elimina un recurso específico de la base de datos
     */
    public function destroy($id) {

        $st = $this->connection->beginTransaction();

        $stmt = $this->connection->prepare("DELETE FROM withdrawals WHERE id =:id");
        $stmt->execute([
            ":id" => $id
        ]);

        $sure = readline("Estas seguro de eliminar el registro asociado al ID N° {$id} ??? -> ");
        $sure = strtolower($sure);

        if ($sure == "si") {
            $this->connection->commit();
            echo "has eliminado el registro con el id {$id}";
        }else {
            $this->connection->rollBack();
            echo "No se ha eliminado ningun registro";
        }   
            

        
       

    }
    
}