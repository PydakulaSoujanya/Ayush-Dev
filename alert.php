<?php
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert']; // Get the alert details from the session
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: '{$alert['title']}',
            text: '{$alert['message']}',
            icon: '{$alert['icon']}',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '{$alert['redirect']}';
        });
    </script>";
    unset($_SESSION['alert']); // Remove the alert from the session after displaying it
}
?>
