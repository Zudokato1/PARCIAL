<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETES, OPTIONS');
});

// Get All Customers
$app->get('/api/todos', function(Request $request, Response $response){
    $sql = "SELECT * FROM usuarios";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get Single Customer
$app->get('/api/customer/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM usuarios WHERE id_usuario = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Add usuarios
$app->post('/api/customer/add', function(Request $request, Response $response){
    $id_user= $request->getParam('id_usuario');
    $cedula= $request->getParam('cedula');
    $first_name = $request->getParam('nombres');
    $last_name = $request->getParam('apellidos');
    $phone = $request->getParam('telefono');
    $address = $request->getParam('direccion');
    $city = $request->getParam('ciudad');


    $sql = "INSERT INTO usuarios (id_usuario,cedula,nombres,apellidos,telefono,direccion,ciudad) VALUES
    (:id_usuario,:cedula,:nombres,:apellidos,:telefono,:direccion,:ciudad)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_usuarios',   $id_user);
        $stmt->bindParam(':ciudad',        $cedula);
        $stmt->bindParam(':nombres',       $first_name);
        $stmt->bindParam(':apellido',      $last_name);
        $stmt->bindParam(':telefono',      $phone);
        $stmt->bindParam(':direccion',     $email);
        $stmt->bindParam(':ciudad',        $address);


        $stmt->execute();

        echo '{"notice": {"text": "usuario añadido"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->put('/api/customer/update/{id}', function(Request $request, Response $response){
    $id_user= $request->getParam('id_usuario');
    $cedula= $request->getParam('cedula');
    $first_name = $request->getParam('nombres');
    $last_name = $request->getParam('apellidos');
    $phone = $request->getParam('telefono');
    $address = $request->getParam('direccion');
    $city = $request->getParam('ciudad');


    $sql = "UPDATE customers SET
                id_usuario  = :id_usuario,
                cedula      = :cedula,
				nombres 	= :nombres,
				apellidos 	= :apellidos,
                telefono	= :telefono,
                direccion	= :direccion,
                ciudad 	    = :ciudad
			WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_usuarios',   $id_user);
        $stmt->bindParam(':ciudad',        $cedula);
        $stmt->bindParam(':nombres',       $first_name);
        $stmt->bindParam(':apellido',      $last_name);
        $stmt->bindParam(':telefono',      $phone);
        $stmt->bindParam(':direccion',     $email);
        $stmt->bindParam(':ciudad',        $address);

        $stmt->execute();

        echo '{"notice": {"text": "Customer Updated"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Delete usuarios
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM usuarios WHERE id_usuario = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "usuario eliminado"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
