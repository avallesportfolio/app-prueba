<?php
require_once 'db_connection.php'; // Asegúrate de que este archivo establece la conexión a la base de datos

if (isset($_GET['id'])) {
    $property_id = intval($_GET['id']);

    // Obtener detalles de la propiedad
    $property_query = $conn->prepare("SELECT p.id, p.user_id, p.name, p.type, p.address, p.city, p.price_per_night, p.description, u.name as host_name, u.email, u.phone, u.address as host_address, ui.image as host_image
                                      FROM properties p
                                      JOIN users u ON p.user_id = u.id
                                      LEFT JOIN user_images ui ON u.id = ui.user_id
                                      WHERE p.id = ?");
    $property_query->bind_param("i", $property_id);
    $property_query->execute();
    $property_result = $property_query->get_result();
    $property = $property_result->fetch_assoc();

    if ($property) {
        // Obtener imágenes de la propiedad
        $images_query = $conn->prepare("SELECT image FROM property_images WHERE property_id = ?");
        $images_query->bind_param("i", $property_id);
        $images_query->execute();
        $images_result = $images_query->get_result();

        $images = [];
        while ($row = $images_result->fetch_assoc()) {
            $images[] = base64_encode($row['image']);
        }

        // Obtener reseñas de la propiedad
        $reviews_query = $conn->prepare("SELECT r.rating, r.comment, r.created_at, u.name as reviewer_name
                                         FROM reviews r
                                         JOIN users u ON r.user_id = u.id
                                         WHERE r.property_id = ?");
        $reviews_query->bind_param("i", $property_id);
        $reviews_query->execute();
        $reviews_result = $reviews_query->get_result();

        $reviews = [];
        while ($row = $reviews_result->fetch_assoc()) {
            $reviews[] = $row;
        }

        // Formatear la respuesta JSON
        $response = [
            'id' => $property['id'],
            'name' => $property['name'],
            'type' => $property['type'],
            'address' => $property['address'],
            'city' => $property['city'],
            'price_per_night' => $property['price_per_night'],
            'description' => $property['description'],
            'host' => [
                'name' => $property['host_name'],
                'email' => $property['email'],
                'phone' => $property['phone'],
                'address' => $property['host_address'],
                'image' => $property['host_image'] ? base64_encode($property['host_image']) : null
            ],
            'images' => $images,
            'reviews' => $reviews
        ];

        echo json_encode($response);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Property not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid property ID']);
}
?>

