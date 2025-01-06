<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $query = "
        SELECT 
            ei.*,
            ea.address_line1, ea.address_line2, ea.landmark, ea.city, ea.state, ea.pincode,
            ed.file_path, ed.file_type, ed.document_name, ed.created_at
        FROM 
            emp_info ei
        LEFT JOIN 
            emp_addresses ea ON ei.id = ea.emp_id
        LEFT JOIN 
            emp_documents ed ON ei.id = ed.emp_id
        WHERE 
            ei.id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                // Append document links if they exist
                if (in_array($key, ['file_path', 'police_verification_document', 'adhar_upload_doc', 'other_doc_name']) && $value) {
                    $response[$key][] = $value; // Support multiple document entries
                } else {
                    $response[$key] = $value;
                }
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Employee not found.']);
    }

    $stmt->close();
}
?>
