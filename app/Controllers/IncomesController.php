<?php

// por lo general un controlador tiene 7 metodos y algunas API 5.

namespace App\Controllers;

use Database\PDO\Connection;

class IncomesController {

    private $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance()->get_database_instance();
    }

    /**
     * Muestra una lista de este recurso
     */
    public function index() {

        $stmt = $this->connection->prepare("SELECT * FROM incomes");
        $stmt->execute();

        $results = $stmt->fetchAll();

        require("../resources/views/incomes/index.php");

       //bindcolumn me permite asociar una columna y ponerla como variable es como un alias despues de la coma osea "amount" ahora esta en la variable $amount que no esta definida
       //por eso el linter de vsc marca error pero bind column ya la definio
    //    $stmt->bindColumn("amount", $amount);
    //    $stmt->bindColumn("description", $description);

    //    while($stmt->fetch()){
    //         echo "ingresó $amount $ USD  en $description \n";
    //    }

    //    $results = $stmt->fetchAll();

    //    foreach($results as $result)
    //     echo "ingresó {$result["amount"]} $ USD  en {$result["description"]} \n ";

    //se obtiene el mismo resultado de fecthAll 
    }

    /**
     * Muestra un formulario para crear un nuevo recurso
     */
    public function create() {
        require("../resources/views/incomes/create.php");
    }

    /**
     * Guarda un nuevo recurso en la base de datos
     */
    public function store($data) {

       $stmt = $this->connection->prepare("INSERT INTO incomes (payment_method, type, date, amount, description) VALUES (?,?,?,?,?);");

        //el linter de VSC saca error porque las variables estan definidas despues de la funcion, hacer caso omiso

        $stmt->bindvalue(":payment_method",$data['payment_method']);
        $stmt->bindvalue(":type",$data['type']);
        $stmt->bindvalue(":date",$data['date']);
        $stmt->bindvalue(":amount",$data['amount']);
        $stmt->bindvalue(":description",$data['description']);
        
        $stmt->execute();

    }

    /**
     * Muestra un único recurso especificado
     */
    public function show($id) {

        $stmt = $this->connection->prepare("SELECT * FROM incomes WHERE id =:id");
        $stmt->is_execute([
            ":id" => $id
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        echo "El registro N° {$result["id"]} dice que ganaste {$result["amount"]} USD por {$result["description"]} \n";
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

        $stmt = $this->connection->prepare("UPDATE incomes SET 

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

       $this->connection->beginTransaction();

       $stmt = $this->connection->prepare("DELETE FROM incomes WHERE id =:id");
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