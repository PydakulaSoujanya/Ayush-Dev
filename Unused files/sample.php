<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Calendar</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
    <style>
        /* Adjusting the calendar size */
        .fc td, .fc th {
            height: 30px;
            font-size: 12px;
            padding: 5px;
        }

        #calendar-container {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>

</head>

<body>
    <?php include('navbar.php'); ?>
    <div class="wrapper">
        <!-- Sidebar -->
        
        <!-- Main Content -->
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="/account.png" class="avatar img-fluid" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <h2 class="text-center">Calendar (2024-2025)</h2>
                    <div id="calendar-container" class="text-center">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="eventForm">
                                    <div class="mb-3">
                                        <label for="eventTitle" class="form-label">Event Title</label>
                                        <input type="text" class="form-control" id="eventTitle" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eventStart" class="form-label">Start Date</label>
                                        <input type="datetime-local" class="form-control" id="eventStart" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eventEnd" class="form-label">End Date</label>
                                        <input type="datetime-local" class="form-control" id="eventEnd" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Event</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#calendar').fullCalendar({
                editable: true,
                selectable: true,
                events: function (start, end, timezone, callback) {
                    $.ajax({
                        url: 'load_events.php',
                        dataType: 'json',
                        success: function (data) {
                            callback(data);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
