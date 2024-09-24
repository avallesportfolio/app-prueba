<?php
include 'db_connection.php';

header('Content-Type: application/json');

// Consulta para obtener 5 propiedades mejor valoradas en orden aleatorio
$sql_top_rated = "SELECT properties.id, properties.name, properties.address, properties.city, properties.price_per_night, property_images.image 
                  FROM properties
                  LEFT JOIN property_images ON properties.id = property_images.property_id
                  ORDER BY RAND()
                  LIMIT 5";

$result_top_rated = $conn->query($sql_top_rated);

$top_rated_properties = array();

if ($result_top_rated->num_rows > 0) {
    while ($row = $result_top_rated->fetch_assoc()) {
        // Convierte el BLOB a base64
        if ($row['image']) {
            $row['image'] = base64_encode($row['image']);
        }
        $top_rated_properties[] = $row;
    }
}

// Consulta para obtener todas las propiedades en orden aleatorio, excluyendo las 5 mejor valoradas
$sql_all_properties = "SELECT properties.id, properties.name, properties.address, properties.city, properties.price_per_night, property_images.image 
                       FROM properties
                       LEFT JOIN property_images ON properties.id = property_images.property_id
                       WHERE properties.id NOT IN (SELECT properties.id 
                                                   FROM properties
                                                   LEFT JOIN property_images ON properties.id = property_images.property_id
                                                   ORDER BY RAND()
                                                   LIMIT 5)
                       ORDER BY RAND()";

$result_all_properties = $conn->query($sql_all_properties);

$all_properties = array();

if ($result_all_properties->num_rows > 0) {
    while ($row = $result_all_properties->fetch_assoc()) {
        // Convierte el BLOB a base64
        if ($row['image']) {
            $row['image'] = base64_encode($row['image']);
        }
        $all_properties[] = $row;
    }
}

$conn->close();

echo json_encode(['top_rated' => $top_rated_properties, 'all_properties' => $all_properties]);
?>
