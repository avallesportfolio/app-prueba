<?php
/*include 'db_connection.php';

header('Content-Type: application/json'); // Asegurarse de que la respuesta es JSON

$sql = "SELECT properties.id, properties.name, properties.address, properties.city, properties.price_per_night, property_images.image 
        FROM properties
        LEFT JOIN property_images ON properties.id = property_images.property_id";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    $conn->close();
    exit();
}

$properties = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convierte el BLOB a base64
        if ($row['image']) {
            $row['image'] = base64_encode($row['image']);
        }
        $properties[] = $row;
    }
}

$conn->close();

echo json_encode($properties);
*/
include 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

// Consulta SQL para obtener propiedades y sus imágenes
$sql = "SELECT properties.id, properties.name, properties.address, properties.city, properties.price_per_night, property_images.image 
        FROM properties
        LEFT JOIN property_images ON properties.id = property_images.property_id";

$result = $conn->query($sql); // Ejecuta la consulta

if (!$result) {
    echo json_encode(["error" => $conn->error]); // Devuelve un error si la consulta falla
    $conn->close();
    exit();
}

$properties = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $propertyId = $row['id'];
        if (!isset($properties[$propertyId])) {
            $properties[$propertyId] = [
                "id" => $propertyId,
                "name" => $row['name'],
                "address" => $row['address'],
                "city" => $row['city'],
                "price_per_night" => $row['price_per_night'],
                "images" => [] // Inicializa el array de imágenes
            ];
        }
        // Convierte el BLOB a base64 y agrega a las imágenes
        if ($row['image']) {
            $properties[$propertyId]['images'][] = base64_encode($row['image']);
        }
    }
}

$conn->close();

// Reindexa el array para que sea numérico y devuelve los datos en formato JSON
$properties = array_values($properties);
echo json_encode($properties);

?>

